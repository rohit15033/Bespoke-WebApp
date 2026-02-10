<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    protected $baseUrl = 'https://graph.facebook.com/v19.0';
    protected $accessToken;
    protected $instagramAccountId;

    public function __construct()
    {
        $this->accessToken = config('services.instagram.access_token');
        $this->instagramAccountId = config('services.instagram.account_id');
    }

    public function fetchRecentMedia($limit = 20)
    {
        if (!$this->accessToken) {
            Log::warning('Instagram API: Missing Access Token');
            return [
                'success' => false, 
                'error' => 'Instagram Access Token is not configured. Please see setup_guide.md'
            ];
        }

        Log::debug('Instagram API: Using Token: ' . substr($this->accessToken, 0, 10) . '...' . substr($this->accessToken, -10));

        try {
            $instagramAccountId = $this->instagramAccountId;

            if (!$instagramAccountId) {
                // 1. Get the Facebook Page ID and connected Instagram Account ID
                // We use 'me/accounts' to find the Pages the user manages
                $pageResponse = Http::withoutVerifying()->get("{$this->baseUrl}/me/accounts", [
                    'access_token' => $this->accessToken,
                    'fields' => 'id,name,instagram_business_account',
                ]);

                if ($pageResponse->failed()) {
                    Log::error('Instagram API: Failed to fetch Pages. ' . $pageResponse->body());
                    return ['success' => false, 'error' => 'Failed to connect to Facebook. Check Token validity.'];
                }

                $pages = $pageResponse->json()['data'] ?? [];
                Log::debug('Instagram API: Pages found: ' . json_encode($pages));

                // Find the first page with a connected Instagram Business Account
                foreach ($pages as $page) {
                    if (isset($page['instagram_business_account']['id'])) {
                        $instagramAccountId = $page['instagram_business_account']['id'];
                        break;
                    }
                }
            }

            if (!$instagramAccountId) {
                return [
                    'success' => false, 
                    'error' => 'No Instagram Business Account found linked to your Facebook Pages. Please link them in Facebook Page Settings.'
                ];
            }

            // 2. Fetch Media from that Instagram Account
            Log::debug("Instagram API: Fetching media for account [{$instagramAccountId}]");
            $mediaResponse = Http::withoutVerifying()->get("{$this->baseUrl}/{$instagramAccountId}/media", [
                'fields' => 'id,caption,media_type,media_product_type,media_url,permalink,thumbnail_url,timestamp,like_count,comments_count',
                'access_token' => $this->accessToken,
                'limit' => $limit,
            ]);

            if ($mediaResponse->successful()) {
                $data = $mediaResponse->json()['data'] ?? [];
                
                // Prepare a pool of requests for all metrics across all posts to maximize speed
                $metricRequests = [];
                foreach ($data as $media) {
                    $mediaType = $media['media_type'] ?? '';
                    $productType = $media['media_product_type'] ?? '';

                    // Core metrics that usually work for all types (reach, total_interactions)
                    $baseMetrics = ['reach', 'total_interactions'];
                    
                    if ($mediaType === 'CAROUSEL_ALBUM') {
                        $baseMetrics[] = 'impressions';
                        $baseMetrics[] = 'carousel_album_impressions';
                    } else if ($mediaType === 'IMAGE') {
                        $baseMetrics[] = 'impressions';
                    }

                    // Content-specific view metrics
                    if ($mediaType === 'VIDEO') {
                        if ($productType === 'REELS') {
                            $baseMetrics[] = 'plays';
                        } else {
                            $baseMetrics[] = 'video_views';
                            $baseMetrics[] = 'impressions';
                        }
                    }

                    foreach ($baseMetrics as $metric) {
                        $metricRequests["{$media['id']}__{$metric}"] = [
                            'id' => $media['id'],
                            'metric' => $metric
                        ];
                    }
                }

                $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($metricRequests) {
                    foreach ($metricRequests as $key => $info) {
                        $pool->as($key)->withoutVerifying()->get("{$this->baseUrl}/{$info['id']}/insights", [
                            'metric' => $info['metric'],
                            'access_token' => $this->accessToken,
                        ]);
                    }
                });

                // Re-assemble the results back into the data array
                foreach ($data as &$media) {
                    foreach (['plays', 'video_views', 'reach', 'impressions', 'carousel_album_impressions', 'total_interactions'] as $mName) {
                        $key = "{$media['id']}__{$mName}";
                        $response = $responses[$key] ?? null;

                        // Pool results can be Response objects OR Exception objects if a request fails
                        if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                            $insights = $response->json()['data'] ?? [];
                            foreach ($insights as $insight) {
                                $value = $insight['values'][0]['value'] ?? 0;
                                $media['insight_' . $insight['name']] = $value;
                                Log::debug("Instagram API: Captured metric {$insight['name']} = {$value} for media {$media['id']}");
                            }
                        }
                    }

                    if (empty($media['insight_reach']) && empty($media['insight_impressions']) && empty($media['insight_plays'])) {
                        Log::info("Instagram API: No core insights captured for {$media['id']} after parallel attempt.");
                    }
                }

                Log::debug('Instagram API: Media data received count: ' . count($data));
                if (count($data) > 0) {
                    Log::debug('Instagram API: Sample media item [0] insights: ' . json_encode([
                        'reach' => $data[0]['insight_reach'] ?? 'N/A',
                        'views' => $data[0]['insight_plays'] ?? $data[0]['insight_video_views'] ?? 'N/A',
                    ]));
                }
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            Log::error('Instagram API Media Error: ' . $mediaResponse->body());
            
            return [
                'success' => false,
                'error' => 'API Error: ' . $mediaResponse->status()
            ];

        } catch (\Exception $e) {
            Log::error('Instagram API Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Connection Error: ' . $e->getMessage()
            ];
        }
    }
}

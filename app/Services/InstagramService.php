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
                'fields' => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,like_count,comments_count',
                'access_token' => $this->accessToken,
                'limit' => $limit,
            ]);

            if ($mediaResponse->successful()) {
                $data = $mediaResponse->json()['data'] ?? [];
                
                // Per-post metric recovery for Video/Reels
                foreach ($data as &$media) {
                    if (($media['media_type'] ?? '') === 'VIDEO') {
                        try {
                            $metric = ($media['media_type'] ?? '') === 'VIDEO' ? 'video_views' : 'plays';
                            $insightsRes = Http::withoutVerifying()->get("{$this->baseUrl}/{$media['id']}/insights", [
                                'metric' => $metric,
                                'access_token' => $this->accessToken,
                            ]);
                            if ($insightsRes->successful()) {
                                $insights = $insightsRes->json()['data'] ?? [];
                                foreach ($insights as $insight) {
                                    if ($insight['name'] === $metric) {
                                        $media['view_count'] = $insight['values'][0]['value'] ?? $media['view_count'] ?? 0;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            Log::warning("Instagram API: Insight fetch failed for {$media['id']}: " . $e->getMessage());
                        }
                    }
                }

                Log::debug('Instagram API: Media data received count: ' . count($data));
                if (count($data) > 0) {
                    Log::debug('Instagram API: Sample media item [0] keys: ' . implode(', ', array_keys($data[0])));
                    Log::debug('Instagram API: Sample media item [0] metrics: likes=' . ($data[0]['like_count'] ?? 'N/A') . ', comments=' . ($data[0]['comments_count'] ?? 'N/A') . ', play=' . ($data[0]['play_count'] ?? 'N/A') . ', view=' . ($data[0]['view_count'] ?? 'N/A'));
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

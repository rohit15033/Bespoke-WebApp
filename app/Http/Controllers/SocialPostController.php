<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialPost;
use Carbon\Carbon;
use App\Http\Controllers\Controller; // Ensure Controller base class is imported if needed, or remove if in same namespace/alias. Usually in same namespace or aliased.
// However, the issue described is duplicate Request import.

// Correcting the imports based on previous diff output:
// original had:
// use Illuminate\Http\Request;
// + use Illuminate\Http\Request;



class SocialPostController extends Controller
{
    public function index()
    {
        $posts = SocialPost::orderBy('posted_at', 'desc')->get();
        // Append calculated attributes
        $posts->append([
            'attributed_leads_count_12h', 
            'attributed_leads_count_24h',
            'attributed_leads_count_lifetime',
            'link_clicks_count_12h',
            'link_clicks_count_24h'
        ]);
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:instagram,tiktok,other',
            'url' => 'required|url',
            'posted_at' => 'required|date',
            'caption' => 'nullable|string',
        ]);

        $post = SocialPost::create($validated);
        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = SocialPost::findOrFail($id);
        $validated = $request->validate([
            'platform' => 'sometimes|in:instagram,tiktok,other',
            'url' => 'sometimes|url',
            'posted_at' => 'sometimes|date',
            'caption' => 'nullable|string',
        ]);

        $post->update($validated);
        return response()->json($post);
    }

    public function destroy($id)
    {
        SocialPost::findOrFail($id)->delete();
        return response()->json(['message' => 'Post deleted']);
    }

    public function sync(\App\Services\InstagramService $instagram)
    {
        $result = $instagram->fetchRecentMedia();

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 503);
        }

        $count = 0;
        foreach ($result['data'] as $media) {
            // Meta returns UTC. We must convert it to our app timezone (Jakarta) before saving.
            $postedAt = \Carbon\Carbon::parse($media['timestamp'])->setTimezone(config('app.timezone'));

            $metrics = [
                'likes' => $media['like_count'] ?? 0,
                'comments' => $media['comments_count'] ?? 0,
                'views' => $media['insight_plays'] 
                           ?? $media['insight_video_views'] 
                           ?? $media['insight_impressions'] 
                           ?? $media['insight_reach'] 
                           ?? 0,
                'reach' => $media['insight_reach'] ?? 0,
                'impressions' => $media['insight_impressions'] ?? $media['insight_carousel_album_impressions'] ?? 0,
            ];

            SocialPost::updateOrCreate(
                ['social_id' => $media['id']],
                [
                    'platform' => 'instagram',
                    'url' => $media['permalink'] ?? $media['media_url'],
                    'posted_at' => $postedAt,
                    'caption' => $media['caption'] ?? '',
                    'thumbnail_url' => $media['thumbnail_url'] ?? $media['media_url'],
                    'metrics' => $metrics,
                ]
            );
            $count++;
        }

        return response()->json([
            'message' => "Synced successfully. Added {$count} new posts.",
            'new_count' => $count
        ]);
    }
}

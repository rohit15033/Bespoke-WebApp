<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\LeadIntent;

class SocialPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'social_id',
        'url',
        'posted_at',
        'caption',
        'thumbnail_url',
        'metrics',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'metrics' => 'array',
    ];

    protected $appends = [
        'attributed_leads_count_12h', 
        'attributed_leads_count_24h',
        'attributed_leads_count_lifetime',
        'link_clicks_count_12h',
        'link_clicks_count_24h'
    ];

    // Helper to calculate attribution for a specific window
    public function calculateAttribution($hours = null)
    {
        $windowStart = $this->posted_at;
        $windowEnd = $hours ? $this->posted_at->copy()->addHours($hours) : Carbon::now()->addYear();

        // Fetch potential leads in the window
        // Use 'like' for case-insensitive platform match
        $potentialLeads = Customer::whereBetween('first_whatsapp_interaction_at', [$windowStart, $windowEnd])
            ->where('source', 'like', $this->platform . '%')
            ->get();

        $count = 0;

        foreach ($potentialLeads as $lead) {
            // Check for more recent posts before the interaction but after this post
            $overlaps = self::where('posted_at', '>', $this->posted_at)
                ->where('posted_at', '<', $lead->first_whatsapp_interaction_at)
                ->exists();

            if (!$overlaps) {
                $count++;
            }
        }

        return $count;
    }

    public function getAttributedLeadsCount12hAttribute()
    {
        return $this->calculateAttribution(12);
    }

    public function getAttributedLeadsCount24hAttribute()
    {
        return $this->calculateAttribution(24);
    }

    public function getAttributedLeadsCountLifetimeAttribute()
    {
        return $this->calculateAttribution(null);
    }

    // Link Clicks Calculation
    public function calculateLinkClicks($hours)
    {
        $windowStart = $this->posted_at;
        $windowEnd = $this->posted_at->copy()->addHours($hours);

        return LeadIntent::whereBetween('created_at', [$windowStart, $windowEnd])->count();
    }

    public function getLinkClicksCount12hAttribute()
    {
        return $this->calculateLinkClicks(12);
    }

    public function getLinkClicksCount24hAttribute()
    {
        return $this->calculateLinkClicks(24);
    }
    
    // Legacy support if needed, or alias to 12h
    public function getAttributedLeadsCountAttribute()
    {
        return $this->getAttributedLeadsCount12hAttribute();
    }
}

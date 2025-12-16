<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsImagesUrls extends Model
{
    protected $table = 'items_images_urls';

    protected $fillable = [
        'item_id',
        'image_url',
    ];

    protected $appends = [
        'resolved_url',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    /**
     * Automatically resolves image URL.
     * Prefers WebP if available, falls back to original.
     */
    public function getResolvedUrlAttribute()
    {
        // Example DB value:
        // items_images/xxxx.jpg
        $relativePath = $this->image_url;

        if (!$relativePath) {
            return null;
        }

        $base = pathinfo($relativePath, PATHINFO_FILENAME);
        $webpRelative = "items_images/webp/{$base}.webp";

        if (file_exists(public_path("storage/{$webpRelative}"))) {
            return asset("storage/{$webpRelative}");
        }

        return asset("storage/{$relativePath}");
    }
}

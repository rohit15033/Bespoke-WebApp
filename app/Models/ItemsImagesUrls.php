<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsImagesUrls extends Model
{
    //
    protected $table = 'items_images_urls';
    protected $fillable = [
        'item_id',
        'image_url',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}

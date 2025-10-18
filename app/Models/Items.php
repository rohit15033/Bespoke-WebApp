<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PDO;

class Items extends Model
{
    //
    protected $table = 'items';
    protected $fillable = [
        'name',
        'code',
        'type',
        'production_month',
        'production_year',
        'subcolor_id'
    ];

    public function subcolor()
    {
        return $this->belongsTo(SubColors::class);
    }

    public function images()
    {
        return $this->hasMany(ItemsImagesUrls::class, 'item_id');
    }

    public function firstImage()
    {
        return $this->hasOne(ItemsImagesUrls::class, 'item_id')->oldestOfMany();
    }
}

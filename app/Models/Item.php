<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'name',
        'type',
        'color',
        'size',
        'image_url',
    ];

    /**
     * Get the item blueprints that use this product as a placeholder.
     */
    public function itemBlueprints()
    {
        return $this->hasMany(ItemBlueprint::class, 'placeholder_sku', 'sku');
    }
}

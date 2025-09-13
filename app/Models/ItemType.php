<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the item blueprints that use this item type.
     */
    public function itemBlueprints()
    {
        return $this->belongsToMany(ItemBlueprint::class, 'item_blueprint_item_type')
                    ->withPivot('description', 'sort_order')
                    ->withTimestamps();
    }
}
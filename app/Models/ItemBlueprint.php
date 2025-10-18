<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBlueprint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'set_blueprint_id',
        'description',
        'sort_order',
        'is_custom',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
        ];
    }

    /**
     * Get the set blueprint that owns this item blueprint.
     */
    public function setBlueprint()
    {
        return $this->belongsTo(SetBlueprint::class);
    }

    /**
     * Get the item types for this item blueprint.
     */
    public function itemTypes()
    {
        return $this->belongsToMany(ItemType::class, 'item_blueprint_item_type')
                    ->withPivot('sort_order')
                    ->withTimestamps();
    }
}

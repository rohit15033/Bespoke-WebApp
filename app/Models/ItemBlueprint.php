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
        'item_type',
        'description',
        'sort_order',
    ];

    /**
     * Get the set blueprint that owns this item blueprint.
     */
    public function setBlueprint()
    {
        return $this->belongsTo(SetBlueprint::class);
    }
}

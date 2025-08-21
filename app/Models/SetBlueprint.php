<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetBlueprint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_blueprint_id',
        'name',
        'sort_order',
    ];

    /**
     * Get the package blueprint that owns this set blueprint.
     */
    public function packageBlueprint()
    {
        return $this->belongsTo(PackageBlueprint::class);
    }

    /**
     * Get the item blueprints that belong to this set blueprint.
     */
    public function itemBlueprints()
    {
        return $this->hasMany(ItemBlueprint::class)->orderBy('sort_order');
    }
}

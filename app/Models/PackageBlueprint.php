<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBlueprint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'default_price',
        'default_discount',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'default_discount' => 'decimal:2',
        ];
    }

    /**
     * Get the set blueprints that belong to this package blueprint.
     */
    public function setBlueprints()
    {
        return $this->hasMany(SetBlueprint::class)->orderBy('sort_order');
    }
}

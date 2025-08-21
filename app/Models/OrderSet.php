<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_package_id',
        'name',
        'sort_order',
    ];

    /**
     * Get the order package that owns this set.
     */
    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class);
    }

    /**
     * Get the order items for this set.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

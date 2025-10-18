<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_type',
        'sort_order',
    ];

    /**
     * Get the order that owns this order product.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the parent product model (Package or Item).
     */
    public function product()
    {
        return $this->morphTo('product', 'product_type', 'product_id');
    }

    /**
     * Scope to get only packages.
     */
    public function scopePackages($query)
    {
        return $query->where('product_type', 'package');
    }

    /**
     * Scope to get only items.
     */
    public function scopeItems($query)
    {
        return $query->where('product_type', 'item');
    }
}

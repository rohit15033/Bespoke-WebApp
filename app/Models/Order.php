<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'status',
        'customer_name',
        'customer_address',
        'customer_phone_number',
        'event_place',
        'event_date',
        'total_price',
        'total_discount',
        'final_price',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'total_price' => 'decimal:2',
            'total_discount' => 'decimal:2',
            'final_price' => 'decimal:2',
        ];
    }

    /**
     * Get the order products for this order.
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class)->orderBy('sort_order');
    }

    /**
     * Get the packages for this order.
     */
    public function packages()
    {
        return $this->hasManyThrough(OrderPackage::class, OrderProduct::class, 'order_id', 'id', 'id', 'product_id')
            ->where('order_products.product_type', 'package');
    }

    /**
     * Get the items for this order.
     */
    public function items()
    {
        return $this->hasManyThrough(OrderItem::class, OrderProduct::class, 'order_id', 'id', 'id', 'product_id')
            ->where('order_products.product_type', 'item');
    }
}

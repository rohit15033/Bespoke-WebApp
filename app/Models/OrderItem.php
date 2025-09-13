<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_set_id',
        'item_sku',
        'note',
        'status',
        'is_additional',
        'is_custom',
        'is_tentative',
        'rental_status',
        'description',
        'price',
        'discount',
        'custom_name',
        'custom_type',
        'custom_details',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_additional' => 'boolean',
            'is_custom' => 'boolean',
            'is_tentative' => 'boolean',
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    /**
     * Get the order set that owns this item.
     */
    public function orderSet()
    {
        return $this->belongsTo(OrderSet::class);
    }

    /**
     * Get the item for this order item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_sku', 'sku');
    }

    /**
     * Get the order product relationship.
     */
    public function orderProduct()
    {
        return $this->morphOne(OrderProduct::class, 'product', 'product_type', 'product_id');
    }

    /**
     * Get the order through the order product relationship.
     */
    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderProduct::class, 'product_id', 'id', 'id', 'order_id')
            ->where('order_products.product_type', 'item');
    }

    /**
     * Get the order item types for this order item.
     */
    public function orderItemTypes()
    {
        return $this->hasMany(OrderItemType::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'discount',
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
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    /**
     * Get the order sets for this package.
     */
    public function orderSets()
    {
        return $this->hasMany(OrderSet::class);
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
            ->where('order_products.product_type', 'package');
    }
}

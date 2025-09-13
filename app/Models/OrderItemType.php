<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_item_id',
        'name',
        'sort_order',
    ];

    /**
     * Get the order item that owns this type.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

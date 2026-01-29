<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class PaymentRecord extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_date',
        'payment_method',
        'proof_image_path',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

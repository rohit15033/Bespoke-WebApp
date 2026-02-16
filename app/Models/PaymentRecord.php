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

    protected $appends = ['type'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeAttribute()
    {
        // Logic to determine payment type based on position in order sequence
        // This might be N+1 but acceptable for small pagination (10 items)
        
        // 1. Get all payments for this order, ordered by date/ID
        // We use a static cache or eager loading if possible, but for attribute access 
        // we might just have to query. To avoid heavy load, we only select minimal fields.
        
        if (!$this->order_id) return 'Unknown';

        // Check if this is the first payment
        $isFirst = PaymentRecord::where('order_id', $this->order_id)
            ->where(function($q) {
                $q->where('payment_date', '<', $this->payment_date)
                  ->orWhere(function($sub) {
                      $sub->where('payment_date', '=', $this->payment_date)
                          ->where('id', '<', $this->id);
                  });
            })
            ->doesntExist();

        // Calculate total unpaid BEFORE this payment? 
        // Or check if this payment completes the order?
        
        // Let's grab the order's final price
        // Note: accessing $this->order might trigger a query if not eager loaded.
        // In PaymentRecordController, we use ::with('order'), so it should be fine.
        $order = $this->order;
        if (!$order) return 'Unknown';

        // Calculate accumulated total including this payment
        $totalPaid = PaymentRecord::where('order_id', $this->order_id)
            ->where(function($q) {
                $q->where('payment_date', '<', $this->payment_date)
                  ->orWhere(function($sub) {
                      $sub->where('payment_date', '=', $this->payment_date)
                          ->where('id', '<=', $this->id);
                  });
            })
            ->sum('amount');

        $isFullyPaid = $totalPaid >= ($order->final_price ?? 0);

        if ($isFirst) {
            return $isFullyPaid ? 'Lunas' : 'Down Payment';
        }

        if ($isFullyPaid) {
            return 'Pelunasan';
        }
        
        // If not first and not fully paid, it's an installment
        // We can check which installment it is
        $countBefore = PaymentRecord::where('order_id', $this->order_id)
             ->where(function($q) {
                $q->where('payment_date', '<', $this->payment_date)
                  ->orWhere(function($sub) {
                      $sub->where('payment_date', '=', $this->payment_date)
                          ->where('id', '<', $this->id);
                  });
            })
            ->count();
            
        return 'Penambahan ' . ($countBefore); 
    }
}

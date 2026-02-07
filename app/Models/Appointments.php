<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\LogsActivity;

class Appointments extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentsFactory> */
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'booking_status',
        'customer_name',
        'customer_phone',
        'at',
        'notes',
        'purpose',
        'result',
        'result_notes',
        'rescheduled_to_at',
        'customer_id',
        'order_id',
        'outcome_reasons',
    ];

    protected $casts = [
        'at' => 'datetime',
        'rescheduled_to_at' => 'datetime',
        'outcome_reasons' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getAppointmentList($payload){
        $query = self::query();
        if (isset($payload['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $payload['customer_name'] . '%');
        }
        if (isset($payload['customer_phone'])) {
            $query->where('customer_phone', 'like', '%' . $payload['customer_phone'] . '%');
        }
        if (isset($payload['booking_status'])) {
            $query->where('booking_status', $payload['booking_status']);
        }
        if (isset($payload['fromAt'])) {
            $query->where('at', '>=', $payload['fromAt']);
        }
        if (isset($payload['toAt'])) {
            $query->where('at', '<=', $payload['toAt']);
        }
        if (isset($payload['has_no_result']) && $payload['has_no_result'] === 'true') {
            $query->where('booking_status', 'Scheduled')
                  ->whereNull('result')
                  ->where('at', '<', now()->startOfDay());
        }
        $sort = $payload['sort'] ?? 'at';
        $limit = $payload['limit'] ?? 5;

        return $query->orderBy($sort)->paginate($limit);
    }

    public static function countAppointments($payload){
        $query = self::query();
        if (isset($payload['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $payload['customer_name'] . '%');
        }
        if (isset($payload['customer_phone'])) {
            $query->where('customer_phone', 'like', '%' . $payload['customer_phone'] . '%');
        }
        if (isset($payload['booking_status'])) {
            $query->where('booking_status', $payload['booking_status']);
        }
        if (isset($payload['fromAt'])) {
            $query->where('at', '>=', $payload['fromAt']);
        }
        if (isset($payload['toAt'])) {
            $query->where('at', '<=', $payload['toAt']);
        }
        if (isset($payload['has_no_result']) && $payload['has_no_result'] === 'true') {
            $query->where('booking_status', 'Scheduled')
                  ->whereNull('result')
                  ->where('at', '<', now()->startOfDay());
        }
        if (isset($payload['exceptId'])) {
            $query->where('id', '!=', $payload['exceptId']);
        }

        return $query->count();
    }

}

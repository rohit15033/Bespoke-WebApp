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

    ];

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
        if (isset($payload['exceptId'])) {
            $query->where('id', '!=', $payload['exceptId']);
        }

        return $query->count();
    }

}

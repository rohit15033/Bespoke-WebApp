<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentsFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'booking_status',
        'customer_name',
        'customer_phone',
        'at',
        'notes',

    ];
}

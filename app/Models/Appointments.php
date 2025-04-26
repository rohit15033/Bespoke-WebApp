<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentsFactory> */
    use HasFactory;

    protected $fillable = [
        'dateTime',
        'type',
        'note',
        'customer_name',
        'customer_phone'
    ];
}

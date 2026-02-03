<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'notes',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointments::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

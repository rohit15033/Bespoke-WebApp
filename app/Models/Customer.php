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
        'source',
        'first_whatsapp_interaction_at',
        'first_staff_reply_at',
        'source_meta',
        'lead_intent_id',
    ];

    protected $casts = [
        'first_whatsapp_interaction_at' => 'datetime',
        'first_staff_reply_at' => 'datetime',
        'source_meta' => 'array',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointments::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function leadIntent()
    {
        return $this->belongsTo(LeadIntent::class, 'lead_intent_id');
    }
}

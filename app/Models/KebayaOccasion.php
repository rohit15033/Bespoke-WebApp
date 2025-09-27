<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebayaOccasion extends Model
{
    /** @use HasFactory<\Database\Factories\KebayaOccasionFactory> */
    use HasFactory;

    protected $table = 'kebaya_occasion';

    protected $fillable = [
        'kebaya_id',
        'occasion_id',
    ];
    
   

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BeskapImages extends Model
{
    //
    use HasFactory;
    protected $table = 'beskap_images';
    protected $fillable = [
        'beskap_id',
        'image_url',
    ];
}

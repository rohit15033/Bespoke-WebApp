<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KebayaImages extends Model
{
    //
    protected $table = 'kebaya_images';
    use HasFactory;
    protected $fillable = [
        'kebaya_id',
        'image_url'
    ];
    
    public function kebaya()
    {
        return $this->belongsTo(Kebaya::class);
    }
}



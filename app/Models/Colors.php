<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colors extends Model
{
    //
    use HasFactory;
    protected $table = 'colors';

       protected $fillable = [
        'name'
    ];

     public function subcolors()
    {
        return $this->hasMany(SubColors::class);
    }

    public static function colorsList(){
        $colorsList = self::all()->map(
            function ($color) {
                return [
                    'id' => $color->id,
                    'name' => $color->name,
                ];
            }   
        );  

        return $colorsList;
    }
    
}

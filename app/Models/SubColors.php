<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubColors extends Model
{
    use HasFactory;
    protected $table = 'subcolors';

    protected $fillable = [
        'name',
        'color_id',
    ];

    // 🔹 Relationships

    public function color()
    {
        return $this->belongsTo(Colors::class);
    }

    public function kebayas()
    {
        return $this->hasMany(Kebaya::class);
    }

    public static function subcolorsList(){
        $subcolorsList = self::with('color')->get()->map(
            function ($subcolor) {
                return [
                    'id' => $subcolor->id,
                    'name' => $subcolor->name,
                 
                ];
            }   
        );  

        return $subcolorsList;
    }   

    public static function getSubcolorsByColorId($colorId){
        return self::where('color_id', $colorId)->get()->map(
            function ($subcolor) {
                return [
                    'id' => $subcolor->id,
                    'name' => $subcolor->name,
                 
                ];
            }   
        );  
    }   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occasions extends Model
{
    //
    protected $fillable = [
        'name'
    ];  

    public function kebayas()
    {
        return $this->belongsToMany(Kebaya::class, 'kebaya_occasion');
    }

    
     public static function occasionsList (){
        $occasionsList = self::all()->map(
            function ($occasion) {
                return [
                    'id' => $occasion->id,
                    'name' => $occasion->name,
                ];
            }   
        );  

        return $occasionsList;  
    }
}

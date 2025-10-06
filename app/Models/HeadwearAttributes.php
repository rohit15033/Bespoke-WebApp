<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadwearAttributes extends Model
{
    //
    protected $table = 'headwear_attributes';

    protected $fillable = ['name'];

    public function headwearAttributeValues()
    {
        return $this->hasMany(HeadwearAttributeValues::class, 'headwear_attribute_id');
    }

    public static function getAllAttributes()
    {
        return self::all();
    }

    public static function getAllAttributesWithValues()
    {
        return self::with('headwearAttributeValues')->get();
    }
}

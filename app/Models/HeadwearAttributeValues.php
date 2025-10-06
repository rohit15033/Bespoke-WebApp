<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadwearAttributeValues extends Model
{
    //
    protected $table = 'headwear_attribute_values';
    protected $fillable = ['value', 'headwear_attribute_id'];

    public function headwear()
    {
        return $this->belongsToMany(Headwear::class, 'headwear_id');
    }

    public function headwearAttribute()
    {
        return $this->belongsTo(HeadwearAttributes::class, 'headwear_attribute_id');
    }
}

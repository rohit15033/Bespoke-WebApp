<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Headwear extends Model
{
    //
    protected $table = 'headwears';
    protected $fillable = ['item_id', 'type'];

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public function headwearAttributeValues()
    {
        return $this->belongsToMany(HeadwearAttributeValues::class, 'headwear_attributes_junctions', 'headwear_id', 'headwear_attribute_value_id');
    }

    public static function getHeadwearList($payload)
    {
        $query = self::with(['item.subcolor.color', 'item.firstImage', 'headwearAttributeValues.headwearAttribute']);

        // // Search filter (code OR name)
        // if (!empty($payload['headwear_filter'])) {
        //     $query->whereHas('item', function ($q) use ($payload) {
        //         $q->where('code', 'like', '%' . $payload['headwear_filter'] . '%')
        //             ->orWhere('name', 'like', '%' . $payload['headwear_filter'] . '%');
        //     });
        // }

        // // Filter by color (via relation)
        // if (!empty($payload['color'])) {
        //     $query->whereHas('item.subcolor.color', function ($q) use ($payload) {
        //         $q->where('name', $payload['color']);
        //     });
        // }

        // // Filter by type (headwear type)
        // if (!empty($payload['type'])) {
        //     $query->where('type', $payload['type']);
        // }

        return $query->get();
    }
}

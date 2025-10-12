<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    //
    protected $table = 'accessories';
    protected $fillable = [
        'item_id',
        'accessories_type',
        'parent_type',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public static function getAccessoriesList($payload)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color'
        ]);
        if (!empty($payload['search_filter'])) {
            $query->whereHas('item', function ($q) use ($payload) {
                $q->where('code', 'like', '%' . $payload['search_filter'] . '%')
                    ->orWhere('name', 'like', '%' . $payload['search_filter'] . '%');
            });
        }
        // Filter by color (via relation)
        if (!empty($payload['color'])) {
            $query->whereHas('item.subcolor.color', function ($q) use ($payload) {
                $q->where('id', $payload['color']);
            });
        }

        // Filter by subcolor (via relation)
        if (!empty($payload['subcolor'])) {
            $query->whereHas('item.subcolor', function ($q) use ($payload) {
                $q->where('name', $payload['subcolor']);
            });
        }
        if (!empty($payload['fromMonth'])) {
            $query->whereHas('item', function ($q) use ($payload) {
                $q->where('production_month', '>=', $payload['fromMonth']);
            });
        }
        if (!empty($payload['toMonth'])) {
            $query->whereHas('item', function ($q) use ($payload) {
                $q->where('production_month', '<=', $payload['toMonth']);
            });
        }
        if (!empty($payload['fromYear'])) {
            $query->whereHas('item', function ($q) use ($payload) {
                $q->where('production_year', '>=', $payload['fromYear']);
            });
        }
        if (!empty($payload['toYear'])) {
            $query->whereHas('item', function ($q) use ($payload) {
                $q->where('production_year', '<=', $payload['toYear']);
            });
        }
        if (!empty($payload['accessories_type'])) {
            $query->where('accessories_type', $payload['accessories_type']);
        }
        if (!empty($payload['parent_type'])) {
            $query->where('parent_type', $payload['parent_type']);
        }


        $sort = $payload['sort'] ?? 'production_date';
        $limit = $payload['limit'] ?? 10;

        $accessories = $query->orderBy($sort, 'desc')
            ->paginate($limit)
            ->through(function ($accessory) {
                return [
                    'id' => $accessory->id,
                    'accessory_type' => ucfirst($accessory->accessories_type),
                    'parent_type' => $accessory->parent_type,
                    'code' => $accessory->item->code,
                    'name' => $accessory->item->name,
                    'color' => $accessory->item->subcolor->color->name,
                    'subcolor' => $accessory->item->subcolor->name,
                    'production_month' => $accessory->item->production_month,
                    'production_year' => $accessory->item->production_year,
                    'image_url' => asset('storage/' . $accessory->item->firstImage?->image_url),
                ];
            });

        return $accessories;
    }

    public static function getAccessoryById($id)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color'
        ])->findOrFail($id);
        $mapped = [
            'id' => $query->id,
            'parent_id' => $query->item->id,
            'accessories_type' => ucfirst($query->accessories_type),
            'parent_type' => $query->parent_type,
            'code' => $query->item->code,
            'name' => $query->item->name,
            'color_id' => $query->item->subcolor->color->id,
            'subcolor_id' => $query->item->subcolor->id,
            'production_month' => $query->item->production_month,
            'production_year' => $query->item->production_year,
            'images' => $query->item->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'url' => asset('storage/' . $img->image_url),
                ];
            }),
        ];
        return $mapped;
    }
}

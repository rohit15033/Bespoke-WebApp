<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Headwear extends Model
{
    //
    protected $table = 'headwears';
    protected $fillable = [
        'item_id',
        'headwear_type',
        'adat'
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public static function getHeadwearList($payload)
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
        if (!empty($payload['headwear_type'])) {
            $query->where('headwear_type', $payload['headwear_type']);
        }
        if (!empty($payload['adat'])) {
            $query->where('adat', $payload['adat']);
        }


        $sort = $payload['sort'] ?? 'production_date';
        $limit = $payload['limit'] ?? 10;

        $headwears = $query->orderBy($sort, 'desc')
            ->paginate($limit)
            ->through(function ($headwear) {
                return [
                    'id' => $headwear->id,
                    'headwear_type' => ucfirst($headwear->headwear_type),
                    'adat' => $headwear->adat,
                    'code' => $headwear->item->code,
                    'name' => $headwear->item->name,
                    'color' => $headwear->item->subcolor->color->name,
                    'subcolor' => $headwear->item->subcolor->name,
                    'production_month' => $headwear->item->production_month,
                    'production_year' => $headwear->item->production_year,
                    'image_url' => asset('storage/' . $headwear->item->firstImage?->image_url),
                ];
            });

        return $headwears;
    }

    public static function getHeadwearById($id)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color'
        ])->findOrFail($id);
        $mapped = [
            'id' => $query->id,
            'parent_id' => $query->item->id,
            'headwear_type' => ucfirst($query->headwear_type),
            'adat' => $query->adat,
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

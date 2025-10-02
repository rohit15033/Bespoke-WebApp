<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selop extends Model
{
    //
    protected $table = 'selops';
    protected $fillable = [
        'item_id',
        'size'
    ];

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public static function getSelopList($payload)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color'
        ]);
        // Search filter (code OR name)
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

        $sort = $payload['sort'] ?? 'production_date';
        $limit = $payload['limit'] ?? 10;
        $selops = $query->orderBy($sort, 'desc')
            ->paginate($limit)
            ->through(function ($selop) {
                return [
                    'id' => $selop->id,
                    'size' => $selop->size,
                    'code' => $selop->item->code,
                    'name' => $selop->item->name,
                    'color' => $selop->item->subcolor->color->name,
                    'subcolor' => $selop->item->subcolor->name,
                    'production_month' => $selop->item->production_month,
                    'production_year' => $selop->item->production_year,
                    'image_url' => asset('storage/' . $selop->item->firstImage?->image_url),
                ];
            });

        return $selops;
    }

    public static function getSelopById($id)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color'
        ])->findOrFail($id);
        $mapped = [
            'id' => $query->id,
            'code' => $query->item->code,
            'name' => $query->item->name,
            'size' => $query->size,
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

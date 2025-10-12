<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kebaya extends Model
{
    //
    protected $table = 'kebayas';
    protected $fillable = [
        'item_id',
        'length',
    ];

    public function occasions()
    {
        return $this->belongsToMany(Occasions::class, 'kebaya_occasions', 'kebaya_id', 'occasion_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public static function getKebayaList($payload)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color',
            'occasions.kebayas'
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

        if (!empty($payload['occasion'])) {
            $query->whereHas('occasions', function ($q) use ($payload) {
                $q->where('name', $payload['occasion']);
            });
        }


        $sort = $payload['sort'] ?? 'production_date';
        $limit = $payload['limit'] ?? 10;
        $kebayas = $query->orderBy($sort, 'desc')
            ->paginate($limit)
            ->through(function ($kebaya) {
                return [
                    'id' => $kebaya->id,
                    'code' => $kebaya->item->code,
                    'name' => $kebaya->item->name,
                    'color' => $kebaya->item->subcolor->color->name,
                    'subcolor' => $kebaya->item->subcolor->name,
                    'occasions' => $kebaya->occasions->pluck('name')->implode(', '),
                    'production_month' => $kebaya->item->production_month,
                    'production_year' => $kebaya->item->production_year,
                    'image_url' => asset('storage/' . $kebaya->item->firstImage?->image_url),
                ];
            });

        return $kebayas;
    }

    public static function getKebayaById($id)
    {
        $query = self::with([
            'item.firstImage',
            'item.subcolor.color',
            'occasions.kebayas'
        ])->findOrFail($id);
        $mapped = [
            'id' => $query->id,
            'parent_id' => $query->item->id,
            'code' => $query->item->code,
            'name' => $query->item->name,
            'length' => $query->length,
            'color_id' => $query->item->subcolor->color->id,
            'occasions' => $query->occasions->pluck('name')->implode(', '),
            'occasions_id' => $query->occasions->pluck('id'),
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;


class Kebaya extends Model
{
    //
    use HasFactory;
    protected $table = 'kebaya';

    protected $fillable = [
        'code',
        'name',
        'subcolor_id',
        'length',
        'production_date',
    ];
        public function subcolor()
    {
        return $this->belongsTo(SubColors::class);
    }

    public function images()
    {
        return $this->hasMany(KebayaImages::class);
    }

    public function firstImage()
    {
        return $this->hasOne(KebayaImages::class)->oldestOfMany();
    }

    public function occasions()
    {
        return $this->belongsToMany(Occasions::class, 'kebaya_occasion', 'kebaya_id', 'occasion_id');
    }

    public static function getKebayaList($payload)
    {

        $query = self::with(['subcolor.color', 'occasions', 'firstImage']);

        // Search filter (code OR name)
        if (!empty($payload['kebaya_filter'])) {
            $query->where(function ($q) use ($payload) {
                $q->where('code', 'like', '%' . $payload['kebaya_filter'] . '%')
                ->orWhere('name', 'like', '%' . $payload['kebaya_filter'] . '%');
            });
        }

        // Filter by color (via relation)
        if (!empty($payload['color'])) {
            $query->whereHas('subcolor.color', function ($q) use ($payload) {
                $q->where('name', $payload['color']);
            });
        }

        // Filter by subcolor (via relation)
        if (!empty($payload['subcolor'])) {
            $query->whereHas('subcolor', function ($q) use ($payload) {
                $q->where('name', $payload['subcolor']);
            });
        }
    
        if(!empty($payload['occasion'])){
            $query->whereHas('occasions', function($q) use ($payload){
                $q->where('name', $payload['occasion']);
            });
        }   
        if (!empty($payload['fromAt'])) {
            $query->where('production_date', '>=', $payload['fromAt']);
        }
        if (!empty($payload['toAt'])) {
            $query->where('production_date', '<=', $payload['toAt']);
        }

        $sort = $payload['sort'] ?? 'production_date';
        $limit = $payload['limit'] ?? 10;

        $kebayas = $query->orderBy($sort, 'desc')      
            ->paginate($limit)    
            ->through(function ($kebaya) { 
                return [
                    'id' => $kebaya->id,
                    'code' => $kebaya->code,
                    'name' => $kebaya->name,
                    'color' => $kebaya->subcolor->color->name,
                    'subcolor' => $kebaya->subcolor->name,
                    'length' => $kebaya->length,
                    'production_date' => $kebaya->production_date,
                    'occasions' => $kebaya->occasions->pluck('name')->implode(', '),
                    'image_url' => asset('storage/' . $kebaya->firstImage?->image_url) ,
                ];
            });

      
        return $kebayas;

    }

    public static function getKebayaById($id){
        $query = self::with(['subcolor.color', 'occasions', 'images'])
                ->findOrFail($id);
        $mapped = [
            'id' => $query->id,
            'code' => $query->code,
            'name' => $query->name,
            'color_id' => $query->subcolor->color->id,
            'subcolor_id' => $query->subcolor->id,
            'length' => $query->length,
            'production_date' => $query->production_date,
            'occasions' => $query->occasions->pluck('id'),
            
            'images' => $query->images->map(function ($img) {
                             return [
                                'id' => $img->id,
                                'url' => asset('storage/' . $img->image_url),
                            ];
                        }),
        ];
        return $mapped;
    }
}

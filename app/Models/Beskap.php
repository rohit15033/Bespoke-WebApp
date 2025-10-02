<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Beskap extends Model
{
    //
    use HasFactory;
    protected $table = 'beskap';
    protected $fillable = [
        'code',
        'name',
        'type',
        'production_year',
        'production_month',
        'subcolor_id'

    ];

    public function subcolor()
    {
        return $this->belongsTo(SubColors::class);
    }
    public function images()
    {
        return $this->hasMany(BeskapImages::class);
    }

    public function firstImage()
    {
        return $this->hasOne(BeskapImages::class)->oldestOfMany();
    }

    public static function getBeskapList($payload){
            $query = self::with(['subcolor.color', 'firstImage']);
            
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
            $beskaps = $query->orderBy($sort, 'desc')      
                ->paginate($limit)    
                ->through(function ($beskap) { 
                    return [
                        'id' => $beskap->id,
                        'code' => $beskap->code,
                        'name' => $beskap->name,
                        'color' => $beskap->subcolor->color->name,
                        'subcolor' => $beskap->subcolor->name,
                        'type' => $beskap->type,
                        'image_url' => asset('storage/' . $beskap->firstImage?->image_url) ,
                    ];
                });

            return $beskaps;


    } 
}

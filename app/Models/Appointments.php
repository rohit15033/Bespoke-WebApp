<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentsFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'booking_status',
        'customer_name',
        'customer_phone',
        'at',
        'notes',

    ];

    public static function getAppointmentList($filter){
        $query = self::query();


        foreach ($filter as $key => $value) {
            if (!is_array($value)) {
                $query->where($key, $value);
                continue;
            }

            foreach($value as $subKey => $subValue) {
                match ($subKey) {
                    '_contains ' => $query->where($key, 'like', '%' . $subValue . '%'),
                    '_starts_with' => $query->where($key, 'like', $subValue . '%'),
                    '_ends_with' => $query->where($key, 'like', '%' . $subValue),
                    default => null,
                };
            }
        }
        return $query->get();

        
    }


}

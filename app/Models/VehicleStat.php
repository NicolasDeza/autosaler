<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Vehicle;

class VehicleStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'views_count',
        'contact_count',
        'fav_count',
    ];


    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

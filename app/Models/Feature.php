<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Vehicle;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
    ];


    public function vehicles()
    {
        return $this->belongsToMany(
            Vehicle::class,
            'feature_vehicle',
            'feature_id',
            'vehicle_id'
        );
    }
}

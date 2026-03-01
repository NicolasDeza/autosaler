<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Vehicle;
use App\Models\VehicleModel;

class VehicleBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'brand_id');
    }
}

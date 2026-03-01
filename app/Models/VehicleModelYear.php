<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\VehicleModel;
use App\Models\Vehicle;

class VehicleModelYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'year',
    ];


    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'model_year_id');
    }
}

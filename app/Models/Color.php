<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
    ];

    public function vehiclesExterior()
    {
        return $this->hasMany(Vehicle::class, 'exterior_color_id');
    }

    public function vehiclesInterior()
    {
        return $this->hasMany(Vehicle::class, 'interior_color_id');
    }
}

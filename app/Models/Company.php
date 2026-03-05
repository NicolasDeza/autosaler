<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adress',
        'city_id',
        'country_id',
        'tva_number',
        'phone',
    ];

    // Company vers Users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Company vers VehicleAds
    public function vehicleAds(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            VehicleAd::class,
            User::class,
            'company_id',
            'user_id',
            'id',
            'id',
        );
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

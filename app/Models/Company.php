<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\City;
use App\Models\Country;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city_id',
        'country_id',
        'tva_number',
        'phone',
    ];

    
    public function user()
    {
        return $this->hasOne(User::class);
    }


    public function vehicles()
    {
        return $this->hasManyThrough(
            Vehicle::class,
            User::class
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

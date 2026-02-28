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

    // Company vers Vehicles
    public function vehicles()
    {
        return $this->hasManyThrough(
            Vehicle::class,
            User::class,
            'company_id', // FK de  users
            'user_id',    // FK  devehicles
            'id',
            'id'
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

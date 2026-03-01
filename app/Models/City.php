<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'zip_code',
    ];


    public function country()
   {
    return $this->belongsTo(Country::class);
   }


   public function companies()
  {
    return $this->hasMany(Company::class);
  }
}

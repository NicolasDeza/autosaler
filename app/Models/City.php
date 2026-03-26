<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'code',
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

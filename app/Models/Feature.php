<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
    ];

    /**
     * @return BelongsToMany<VehicleAd, $this>
     */
    public function vehicleAds(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleAd::class,
            'feature_vehicle',
            'feature_id',
            'vehicle_ad_id',
        );
    }
}

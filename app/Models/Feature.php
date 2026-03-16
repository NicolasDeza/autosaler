<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_category_id',
        'code',
        'sort_order',
        'is_active',
    ];

    /**
     * Category of the feature
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FeatureCategory::class, 'feature_category_id');
    }

    /**
     * Vehicles having this feature
     */
    public function vehicleAds(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleAd::class,
            'feature_vehicle',
            'feature_id',
            'vehicle_ad_id'
        );
    }
}

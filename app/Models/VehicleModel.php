<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'brand_id' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<VehicleBrand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    /**
     * @return HasMany<VehicleVersion, $this>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(VehicleVersion::class, 'model_id');
    }

    /**
     * @return HasMany<VehicleAd, $this>
     */
    public function vehicleAds(): HasMany
    {
        return $this->hasMany(VehicleAd::class, 'model_id');
    }
}

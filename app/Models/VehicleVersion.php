<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'name',
        'start_year',
        'end_year',
        'body_type_id',
        'fuel_type_id',
        'transmission_type_id',
        'euro_norm_id',
        'power_kw',
        'engine_displacement',
        'cylinder_count',
        'fuel_consumption_avg',
        'weight_kg',
        'co2_emission',
        'gear_count',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'fuel_consumption_avg' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<VehicleModel, $this>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    /**
     * @return BelongsTo<BodyType, $this>
     */
    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class);
    }

    /**
     * @return BelongsTo<FuelType, $this>
     */
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    /**
     * @return BelongsTo<TransmissionType, $this>
     */
    public function transmissionType(): BelongsTo
    {
        return $this->belongsTo(TransmissionType::class);
    }

    /**
     * @return BelongsTo<EuroNorm, $this>
     */
    public function euroNorm(): BelongsTo
    {
        return $this->belongsTo(EuroNorm::class);
    }

    /**
     * @return HasMany<VehicleAd, $this>
     */
    public function vehicleAds(): HasMany
    {
        return $this->hasMany(VehicleAd::class);
    }
}

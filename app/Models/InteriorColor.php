<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InteriorColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<VehicleAd, $this>
     */
    public function vehicleAds(): HasMany
    {
        return $this->hasMany(VehicleAd::class);
    }
}

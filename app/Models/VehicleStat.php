<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_ad_id',
        'views_count',
        'contact_count',
        'fav_count',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_ad_id' => 'integer',
            'views_count' => 'integer',
            'contact_count' => 'integer',
            'fav_count' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<VehicleAd, $this>
     */
    public function vehicleAd(): BelongsTo
    {
        return $this->belongsTo(VehicleAd::class);
    }
}

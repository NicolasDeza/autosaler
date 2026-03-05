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

    /**
     * @return BelongsTo<VehicleAd, $this>
     */
    public function vehicleAd(): BelongsTo
    {
        return $this->belongsTo(VehicleAd::class);
    }
}

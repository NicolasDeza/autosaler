<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'address',
        'city_id',
        'country_id',
        'tva_number',
        'phone',
    ];

    protected $appends = [
        'logo_url',
        'background_url',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('logo', 'thumb');

        return $url ? parse_url($url, PHP_URL_PATH) : null;
    }

    public function getBackgroundUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('background', 'bg_optimized');

        return $url ? parse_url($url, PHP_URL_PATH) : null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();

        $this->addMediaCollection('background')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('logo')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('bg_optimized')
            ->performOnCollections('background')
            ->width(800)
            ->height(400)
            ->quality(80)
            ->nonQueued();
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // Company vers VehicleAds
    public function vehicleAds(): HasManyThrough
    {
        return $this->hasManyThrough(
            VehicleAd::class,
            User::class,
            'company_id',
            'user_id',
            'id',
            'id',
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

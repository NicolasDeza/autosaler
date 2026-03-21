<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VehicleAd extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected static function booted(): void
    {
        static::deleting(function (VehicleAd $vehicleAd) {
            Storage::disk('public')->deleteDirectory("cars/{$vehicleAd->id}");
        });
    }

    protected $fillable = [
        'user_id',
        'status',

        'brand_id',
        'model_id',
        'vehicle_version_id',

        'exterior_color_id',
        'exterior_color_metalised',
        'interior_color_id',
        'interior_type_id',

        'fuel_type_id',
        'body_type_id',
        'euro_norm_id',
        'transmission_type_id',

        'vehicle_version_name',
        'price',
        'mileage',
        'power_kw',
        'engine_displacement',
        'cylinder_count',
        'fuel_consumption_avg',
        'weight_kg',
        'co2_emission',

        'gear_count',
        'doors',
        'seats',
        'previous_owner',

        'technical_inspection_status',
        'is_damaged',
        'has_accident',
        'complete_maintenance_book',
        'non_smoker',

        'vin',
        'first_registration_date',
        'purchase_date',

        'description',
    ];

    protected $appends = ['primary_image', 'gallery'];

    public function getPrimaryImageAttribute(): ?array
    {
        $media = $this->getFirstMedia('gallery');

        if (! $media) {
            return null;
        }

        return [
            'id' => $media->id,
            'thumb' => parse_url($media->getUrl('thumb'), PHP_URL_PATH),
            'card' => parse_url($media->getUrl('card'), PHP_URL_PATH),
            'large' => parse_url($media->getUrl('large'), PHP_URL_PATH),
        ];
    }

    public function getGalleryAttribute(): Collection
    {
        return $this->getMedia('gallery')->sortBy('order_column')->map(function ($media) {
            return [
                'id' => $media->id,
                'thumb' => parse_url($media->getUrl('thumb'), PHP_URL_PATH),
                'card' => parse_url($media->getUrl('card'), PHP_URL_PATH),
                'large' => parse_url($media->getUrl('large'), PHP_URL_PATH),
            ];
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'fuel_consumption_avg' => 'decimal:2',
            'exterior_color_metalised' => 'boolean',
            'technical_inspection_status' => 'boolean',
            'is_damaged' => 'boolean',
            'has_accident' => 'boolean',
            'complete_maintenance_book' => 'boolean',
            'non_smoker' => 'boolean',
            'first_registration_date' => 'date',
            'purchase_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<VehicleBrand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    /**
     * @return BelongsTo<VehicleModel, $this>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    /**
     * @return BelongsTo<VehicleVersion, $this>
     */
    public function vehicleVersion(): BelongsTo
    {
        return $this->belongsTo(VehicleVersion::class);
    }

    /**
     * @return BelongsTo<ExteriorColor, $this>
     */
    public function exteriorColor(): BelongsTo
    {
        return $this->belongsTo(ExteriorColor::class);
    }

    /**
     * @return BelongsTo<InteriorColor, $this>
     */
    public function interiorColor(): BelongsTo
    {
        return $this->belongsTo(InteriorColor::class);
    }

    /**
     * @return BelongsTo<InteriorType, $this>
     */
    public function interiorType(): BelongsTo
    {
        return $this->belongsTo(InteriorType::class);
    }

    /**
     * @return BelongsTo<FuelType, $this>
     */
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    /**
     * @return BelongsTo<BodyType, $this>
     */
    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class);
    }

    /**
     * @return BelongsTo<EuroNorm, $this>
     */
    public function euroNorm(): BelongsTo
    {
        return $this->belongsTo(EuroNorm::class);
    }

    /**
     * @return BelongsTo<TransmissionType, $this>
     */
    public function transmissionType(): BelongsTo
    {
        return $this->belongsTo(TransmissionType::class);
    }

    /**
     * @return HasOne<VehicleStat, $this>
     */
    public function stat(): HasOne
    {
        return $this->hasOne(VehicleStat::class, 'vehicle_ad_id');
    }

    /**
     * @return BelongsToMany<Feature, $this>
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_vehicle',
            'vehicle_ad_id',
            'feature_id',
        );
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function favoredByUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_vehicles',
            'vehicle_ad_id',
            'user_id',
        );
    }

    /**
     * Scope for searching vehicles.
     */
    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('id', 'like', "%{$search}%")
                    ->orWhere('vehicle_version_name', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('model', fn ($m) => $m->where('name', 'like', "%{$search}%"));
            });
        });
    }

    /**
     * Scope for sorting vehicles.
     */
    public function scopeSort($query, ?string $sort = 'latest')
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'id_asc' => $query->orderBy('id', 'asc'),
            'id_desc' => $query->orderBy('id', 'desc'),
            'oldest', 'created_at_asc', 'date_asc' => $query->orderBy('created_at', 'asc'),
            'latest', 'created_at_desc', 'date_desc' => $query->orderBy('created_at', 'desc'),
            'views_desc' => $query->withAggregate('stat', 'views_count')->orderBy('stat_views_count', 'desc'),
            'contacts_desc' => $query->withAggregate('stat', 'contact_count')->orderBy('stat_contact_count', 'desc'),
            'favs_desc' => $query->withAggregate('stat', 'fav_count')->orderBy('stat_fav_count', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(100)
            ->sharpen(10)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(600)
            ->height(400)
            ->sharpen(10)
            ->format('webp')
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1600) // important
            ->height(1200)
            ->format('webp')
            ->sharpen(10)
            ->optimize()
            ->nonQueued();
    }

    // Active le tri
    public function getMediaCollectionNames(): array
    {
        return ['gallery']; // une seule collection
    }
}

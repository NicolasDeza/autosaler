<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\VehicleModelYear;
use App\Models\Color;
use App\Models\VehicleStat;
use App\Models\Feature;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'status',

    'brand_id',
    'model_id',
    'model_year_id',

    'exterior_color_id',
    'exterior_color_metalised',
    'interior_color_id',
    'interior_type',

    'fuel_type',
    'body_type',

    'power_kw',
    'engine_displacement',
    'cylinder_count',
    'weight_kg',

    'price',
    'mileage',
    'fuel_consumption_avg',
    'co2_emission',
    'euro_norm',

    'description',

    'transmission',
    'gear_count',
    'doors',
    'sieges',

    'previous_owner',
    'complete_maintenance_book',
    'non_smoker',

    'vin',

    'technical_inspection_status',
    'is_damaged',
    'has_accident',

    'registration_number',
    'first_registration_date',
    'purchase_date',
];


    protected function casts(): array
    {
        return [
            'exterior_color_metalised'   => 'boolean',
            'complete_maintenance_book'  => 'boolean',
            'non_smoker'                 => 'boolean',
            'first_registration_date'    => 'date',
            'purchase_date'              => 'date',
        ];
    }

    // Owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Brand
    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    // Model
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    // Model Year
    public function modelYear()
    {
        return $this->belongsTo(
            VehicleModelYear::class,
            'model_year_id'
        );
    }

    // Exterior Color
    public function exteriorColor()
    {
        return $this->belongsTo(Color::class, 'exterior_color_id');
    }

    // Interior Color
    public function interiorColor()
    {
        return $this->belongsTo(Color::class, 'interior_color_id');
    }

    // Images | IMAGE VIA SPATIE
    // public function images()
    // {
    //     return $this->hasMany(VehicleImage::class);
    // }

    // Stats
    public function stat()
    {
        return $this->hasOne(VehicleStat::class);
    }

    // Features
    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_vehicle',
            'vehicle_id',
            'feature_id'
        );
    }

    // FAVORITES ( user vehicle )
    public function favoredByUsers()
   {
    return $this->belongsToMany(
        User::class,
        'user_vehicles',
        'vehicle_for_sale_id',
        'user_id'
     );
   }
 }

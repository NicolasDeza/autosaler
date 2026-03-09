<?php

namespace Database\Factories;

use App\Models\BodyType;
use App\Models\EuroNorm;
use App\Models\ExteriorColor;
use App\Models\FuelType;
use App\Models\InteriorColor;
use App\Models\InteriorType;
use App\Models\TransmissionType;
use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\VehicleVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleAd>
 */
class VehicleAdFactory extends Factory
{
    public function definition(): array
    {
        $brand = VehicleBrand::firstOrCreate(['name' => 'Volkswagen']);
        $model = VehicleModel::firstOrCreate(['brand_id' => $brand->id, 'name' => 'Golf']);

        $fuelType = FuelType::firstOrCreate(['code' => 'petrol'], ['sort_order' => 1, 'is_active' => true]);
        $bodyType = BodyType::firstOrCreate(['code' => 'hatchback'], ['sort_order' => 2, 'is_active' => true]);
        $transmissionType = TransmissionType::firstOrCreate(['code' => 'manual'], ['sort_order' => 1, 'is_active' => true]);
        $euroNorm = EuroNorm::firstOrCreate(['code' => 'euro_6d'], ['sort_order' => 9, 'is_active' => true]);
        $exteriorColor = ExteriorColor::firstOrCreate(['code' => 'gray'], ['sort_order' => 3, 'is_active' => true]);
        $interiorColor = InteriorColor::firstOrCreate(['code' => 'black'], ['sort_order' => 1, 'is_active' => true]);
        $interiorType = InteriorType::firstOrCreate(['code' => 'fabric'], ['sort_order' => 1, 'is_active' => true]);

        $version = VehicleVersion::firstOrCreate(
            ['model_id' => $model->id, 'name' => '1.5 TSI 130 ch'],
            [
                'model_id' => $model->id,
                'name' => '1.5 TSI 130 ch',
                'start_year' => 2020,
                'body_type_id' => $bodyType->id,
                'fuel_type_id' => $fuelType->id,
                'transmission_type_id' => $transmissionType->id,
                'euro_norm_id' => $euroNorm->id,
                'power_kw' => 96,
                'engine_displacement' => 1498,
                'cylinder_count' => 4,
                'fuel_consumption_avg' => 6.1,
                'co2_emission' => 140,
                'gear_count' => 6,
            ]
        );

        return [
            'user_id' => User::factory(),
            'status' => 'active',
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'vehicle_version_id' => $version->id,
            'exterior_color_id' => $exteriorColor->id,
            'exterior_color_metalised' => false,
            'interior_color_id' => $interiorColor->id,
            'interior_type_id' => $interiorType->id,
            'fuel_type_id' => $fuelType->id,
            'body_type_id' => $bodyType->id,
            'euro_norm_id' => $euroNorm->id,
            'transmission_type_id' => $transmissionType->id,
            'price' => fake()->randomFloat(2, 5000, 80000),
            'mileage' => fake()->numberBetween(0, 200000),
            'power_kw' => 96,
            'engine_displacement' => 1498,
            'cylinder_count' => 4,
            'fuel_consumption_avg' => 6.1,
            'co2_emission' => 140,
            'weight_kg' => 1335,
            'gear_count' => 6,
            'doors' => 5,
            'seats' => 5,
            'previous_owner' => fake()->numberBetween(0, 3),
            'technical_inspection_status' => true,
            'is_damaged' => false,
            'has_accident' => false,
            'complete_maintenance_book' => true,
            'non_smoker' => true,
            'vin' => strtoupper(Str::random(17)),
            'registration_number' => strtoupper(fake()->bothify('??-###-??')),
            'first_registration_date' => fake()->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'),
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'description' => fake()->paragraph(),
        ];
    }
}

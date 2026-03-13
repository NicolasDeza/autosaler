<?php

namespace Database\Factories;

use App\Models\BodyType;
use App\Models\EuroNorm;
use App\Models\ExteriorColor;
use App\Models\Feature;
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
    public function configure(): static
    {
        return $this->afterCreating(function ($vehicleAd): void {
            $featureIds = Feature::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(6, 16))
                ->pluck('id');

            if ($featureIds->isNotEmpty()) {
                $vehicleAd->features()->sync($featureIds);
            }
        });
    }

    public function definition(): array
    {
        $brand = VehicleBrand::inRandomOrder()->first() ?? VehicleBrand::firstOrCreate(['name' => 'Volkswagen']);
        $model = VehicleModel::where('brand_id', $brand->id)->inRandomOrder()->first() ?? VehicleModel::firstOrCreate(['brand_id' => $brand->id, 'name' => 'Golf']);

        $fuelType = FuelType::inRandomOrder()->first() ?? FuelType::firstOrCreate(['code' => 'petrol'], ['sort_order' => 1, 'is_active' => true]);
        $bodyType = BodyType::inRandomOrder()->first() ?? BodyType::firstOrCreate(['code' => 'hatchback'], ['sort_order' => 2, 'is_active' => true]);
        $transmissionType = TransmissionType::inRandomOrder()->first() ?? TransmissionType::firstOrCreate(['code' => 'manual'], ['sort_order' => 1, 'is_active' => true]);
        $euroNorm = EuroNorm::inRandomOrder()->first() ?? EuroNorm::firstOrCreate(['code' => 'euro_6d'], ['sort_order' => 9, 'is_active' => true]);
        $exteriorColor = ExteriorColor::inRandomOrder()->first() ?? ExteriorColor::firstOrCreate(['code' => 'gray'], ['sort_order' => 3, 'is_active' => true]);
        $interiorColor = InteriorColor::inRandomOrder()->first() ?? InteriorColor::firstOrCreate(['code' => 'black'], ['sort_order' => 1, 'is_active' => true]);
        $interiorType = InteriorType::inRandomOrder()->first() ?? InteriorType::firstOrCreate(['code' => 'fabric'], ['sort_order' => 1, 'is_active' => true]);

        $engines = ['1.0 TSI 110ch', '1.5 TSI 130ch', '1.5 TSI 150ch', '2.0 TDI 150ch', '2.0 TDI 200ch', 'Hybrid 204ch', 'Electric 204ch'];
        $trims = ['Life', 'Style', 'R-Line', 'Business', 'Performance', 'Executive', 'Lounge'];

        $randomEngine = fake()->randomElement($engines);
        $randomTrim = fake()->randomElement($trims);
        $versionName = "{$randomEngine} {$randomTrim}";

        $version = VehicleVersion::where('model_id', $model->id)->inRandomOrder()->first() ?? VehicleVersion::firstOrCreate(
            ['model_id' => $model->id, 'name' => $versionName],
            [
                'start_year' => fake()->numberBetween(2018, 2024),
                'body_type_id' => $bodyType->id,
                'fuel_type_id' => $fuelType->id,
                'transmission_type_id' => $transmissionType->id,
                'euro_norm_id' => $euroNorm->id,
                'power_kw' => fake()->numberBetween(80, 150),
                'engine_displacement' => fake()->numberBetween(999, 1998),
                'cylinder_count' => 4,
                'fuel_consumption_avg' => fake()->randomFloat(1, 4.5, 7.5),
                'co2_emission' => fake()->numberBetween(95, 160),
                'gear_count' => fake()->randomElement([5, 6, 7, 8]),
            ]
        );

        return [
            'user_id' => User::factory(),
            'status' => 'active',
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'vehicle_version_id' => $version->id,
            'vehicle_version_name' => $version->name,
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
            'first_registration_date' => fake()->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'),
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'description' => fake()->paragraph(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\FuelType;
use Illuminate\Database\Seeder;

class FuelTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            [
                'code' => 'petrol',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'diesel',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'electric',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'hybrid',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'plug_in_hybrid',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'lpg',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'cng',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'code' => 'hydrogen',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'code' => 'ethanol',
                'sort_order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($fuelTypes as $fuelType) {
            FuelType::create($fuelType);
        }

        $this->command->info('Fuel types seeded successfully');
    }
}

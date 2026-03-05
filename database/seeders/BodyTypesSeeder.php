<?php

namespace Database\Seeders;

use App\Models\BodyType;
use Illuminate\Database\Seeder;

class BodyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bodyTypes = [
            [
                'code' => 'sedan',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'hatchback',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'suv',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'coupe',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'convertible',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'station_wagon',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'pickup',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'code' => 'van',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'code' => 'minivan',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'code' => 'roadster',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'crossover',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'code' => 'sports',
                'sort_order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($bodyTypes as $bodyType) {
            BodyType::create($bodyType);
        }

        $this->command->info('Body types seeded successfully');
    }
}

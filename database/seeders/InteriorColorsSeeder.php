<?php

namespace Database\Seeders;

use App\Models\InteriorColor;
use Illuminate\Database\Seeder;

class InteriorColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interiorColors = [
            [
                'code' => 'black',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'white',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'gray',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'blue',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'red',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'green',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'yellow',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'code' => 'orange',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'code' => 'purple',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'code' => 'brown',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($interiorColors as $interiorColor) {
            InteriorColor::create($interiorColor);
        }

        $this->command->info('Interior colors seeded successfully');
    }
}

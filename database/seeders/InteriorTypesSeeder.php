<?php

namespace Database\Seeders;

use App\Models\InteriorType;
use Illuminate\Database\Seeder;

class InteriorTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interiorTypes = [
            [
                'code' => 'fabric',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'leather',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'alcantara',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'synthetic',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'suede',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($interiorTypes as $interiorType) {
            InteriorType::create($interiorType);
        }

        $this->command->info('Interior types seeded successfully');
    }
}

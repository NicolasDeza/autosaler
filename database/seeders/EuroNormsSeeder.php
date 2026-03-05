<?php

namespace Database\Seeders;

use App\Models\EuroNorm;
use Illuminate\Database\Seeder;

class EuroNormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $euroNorms = [
            [
                'code' => 'euro_1',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'euro_2',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'euro_3',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'euro_4',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'euro_5',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6b',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6c',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6d',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6d_temp',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'euro_6e',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'code' => 'euro_7',
                'sort_order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($euroNorms as $euroNorm) {
            EuroNorm::create($euroNorm);
        }

        $this->command->info('Euro norms seeded successfully');
    }
}

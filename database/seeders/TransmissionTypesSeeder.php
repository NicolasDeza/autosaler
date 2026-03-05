<?php

namespace Database\Seeders;

use App\Models\TransmissionType;
use Illuminate\Database\Seeder;

class TransmissionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transmissionTypes = [
            [
                'code' => 'manual',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'automatic',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'semi_automatic',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'cvt',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'dual_clutch',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'sequential',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($transmissionTypes as $transmissionType) {
            TransmissionType::create($transmissionType);
        }

        $this->command->info('Transmission types seeded successfully');
    }
}

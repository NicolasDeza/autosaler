<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VehicleAd;
use Illuminate\Database\Seeder;

class VehicleAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dealers = User::role('dealer')->get();

        if ($dealers->isEmpty()) {
            $this->command->warn('No users with the dealer role found. Creating 3 default dealers.');
            
            $dealers = User::factory()->count(3)->create()->each(function ($user) {
                $user->assignRole('dealer');
            });
        }

        VehicleAd::factory()
            ->count(30)
            ->recycle($dealers)
            ->create();

        $this->command->info('30 vehicle ads created for random dealers.');
    }
}

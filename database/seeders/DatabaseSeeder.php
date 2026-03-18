<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CountriesCitiesSeeder::class,
            UserSeeder::class,
            BodyTypesSeeder::class,
            EuroNormsSeeder::class,
            FuelTypesSeeder::class,
            InteriorTypesSeeder::class,
            InteriorColorsSeeder::class,
            ExteriorColorsSeeder::class,
            TransmissionTypesSeeder::class,
            BrandsAndModelsSeeder::class,
            FeatureSeeder::class,
            SubscriptionPlansSeeder::class,
            VehicleAdSeeder::class,
            SubscriptionPlansSeeder::class,
        ]);
    }
}

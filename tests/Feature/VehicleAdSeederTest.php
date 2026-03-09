<?php

use App\Models\FuelType;
use App\Models\User;
use App\Models\UserVehicle;
use App\Models\VehicleAd;
use Database\Seeders\BodyTypesSeeder;
use Database\Seeders\EuroNormsSeeder;
use Database\Seeders\ExteriorColorsSeeder;
use Database\Seeders\FuelTypesSeeder;
use Database\Seeders\InteriorColorsSeeder;
use Database\Seeders\InteriorTypesSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\TransmissionTypesSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleAdSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(UserSeeder::class);
    $this->seed(BodyTypesSeeder::class);
    $this->seed(EuroNormsSeeder::class);
    $this->seed(FuelTypesSeeder::class);
    $this->seed(InteriorTypesSeeder::class);
    $this->seed(InteriorColorsSeeder::class);
    $this->seed(ExteriorColorsSeeder::class);
    $this->seed(TransmissionTypesSeeder::class);
});

it('creates 10 vehicle ads when seeded', function () {
    $this->seed(VehicleAdSeeder::class);

    expect(VehicleAd::count())->toBe(10);
});

it('links 10 user vehicle records when seeded', function () {
    $this->seed(VehicleAdSeeder::class);

    expect(UserVehicle::count())->toBe(10);
});

it('assigns 5 vehicles to seb@autosaler.com', function () {
    $this->seed(VehicleAdSeeder::class);

    $sebastien = User::where('email', 'seb@autosaler.com')->first();

    expect(VehicleAd::where('user_id', $sebastien->id)->count())->toBe(5);
});

it('assigns 5 vehicles to nico@autosaler.com', function () {
    $this->seed(VehicleAdSeeder::class);

    $nicolas = User::where('email', 'nico@autosaler.com')->first();

    expect(VehicleAd::where('user_id', $nicolas->id)->count())->toBe(5);
});

it('creates vehicle ads with correct fuel types', function () {
    $this->seed(VehicleAdSeeder::class);

    $dieselId = FuelType::where('code', 'diesel')->value('id');
    $petrolId = FuelType::where('code', 'petrol')->value('id');
    $hybridId = FuelType::where('code', 'hybrid')->value('id');

    expect(VehicleAd::where('fuel_type_id', $dieselId)->count())->toBe(4);
    expect(VehicleAd::where('fuel_type_id', $petrolId)->count())->toBe(5);
    expect(VehicleAd::where('fuel_type_id', $hybridId)->count())->toBe(1);
});

it('creates vehicle ads with active status', function () {
    $this->seed(VehicleAdSeeder::class);

    expect(VehicleAd::where('status', 'active')->count())->toBe(10);
});

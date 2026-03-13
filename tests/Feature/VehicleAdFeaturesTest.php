<?php

use App\Models\Feature;
use App\Models\VehicleAd;
use Database\Seeders\FeatureSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('seeds the expected vehicle feature catalog', function () {
    $this->seed(FeatureSeeder::class);

    expect(Feature::query()->whereIn('key', [
        'ABS',
        'Bluetooth',
        'Climatisation automatique',
        'Jantes alliage',
        'Systeme de navigation',
        'Verrouillage centralise avec telecommande',
    ])->count())->toBe(6);
});

it('attaches random features when a vehicle ad is created from the factory', function () {
    $this->seed(FeatureSeeder::class);

    $vehicleAd = VehicleAd::factory()->create();

    expect($vehicleAd->features()->count())->toBeGreaterThan(0);
});

it('exposes attached features on the vehicle ad show page', function () {
    $this->seed(FeatureSeeder::class);

    $vehicleAd = VehicleAd::factory()->create();
    $feature = Feature::query()->firstWhere('key', 'ABS');
    $vehicleAd->features()->sync([$feature->id]);

    $response = $this->get(route('vehicles.show', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->where('ad.features.0.key', 'ABS')
        );
});

<?php

use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\VehicleAd;
use Database\Seeders\FeatureSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('seeds the expected vehicle feature catalog', function () {
    $this->seed(FeatureSeeder::class);

    expect(Feature::query()->whereIn('code', [
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
    $feature = Feature::query()->firstWhere('code', 'ABS');
    $vehicleAd->features()->sync([$feature->id]);

    $response = $this->get(route('vehicles.show', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->where('ad.features.0.code', 'ABS')
        );
});

it('exposes flat feature options for vehicle index filters', function () {
    $comfortCategory = FeatureCategory::query()->create([
        'code' => 'comfort',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $safetyCategory = FeatureCategory::query()->create([
        'code' => 'safety',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    Feature::query()->create([
        'feature_category_id' => $comfortCategory->id,
        'code' => 'Airco',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Feature::query()->create([
        'feature_category_id' => $safetyCategory->id,
        'code' => 'ABS',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('vehicles.index'));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Index')
            ->loadDeferredProps('filters', fn (Assert $deferredPage) => $deferredPage
                ->has('features', 2)
                ->where('features.0.code', 'ABS')
                ->where('features.1.code', 'Airco')
                ->missing('features.0.category')
                ->missing('features.1.category')
            )
        );
});

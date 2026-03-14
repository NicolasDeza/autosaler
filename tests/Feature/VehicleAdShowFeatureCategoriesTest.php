<?php

use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\VehicleAd;
use Inertia\Testing\AssertableInertia as Assert;

it('exposes attached features with category data on the vehicle show page', function () {
    $vehicleAd = VehicleAd::factory()->create();

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

    $airco = Feature::query()->create([
        'feature_category_id' => $comfortCategory->id,
        'code' => 'Airco',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $abs = Feature::query()->create([
        'feature_category_id' => $safetyCategory->id,
        'code' => 'ABS',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $vehicleAd->features()->sync([$airco->id, $abs->id]);

    $response = $this->get(route('vehicles.show', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->has('ad.features', 2)
            ->where('ad.features.0.code', 'Airco')
            ->where('ad.features.0.category.code', 'comfort')
            ->where('ad.features.1.code', 'ABS')
            ->where('ad.features.1.category.code', 'safety')
        );
});

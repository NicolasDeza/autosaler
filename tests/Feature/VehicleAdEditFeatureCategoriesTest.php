<?php

use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\User;
use App\Models\VehicleAd;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('exposes active features grouped by active category on the edit page', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $dealer = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $dealer->assignRole('dealer');

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $dealer->id,
    ]);

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

    $inactiveCategory = FeatureCategory::query()->create([
        'code' => 'inactive-category',
        'sort_order' => 3,
        'is_active' => false,
    ]);

    $airco = Feature::query()->create([
        'feature_category_id' => $comfortCategory->id,
        'code' => 'Airco',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Feature::query()->create([
        'feature_category_id' => $comfortCategory->id,
        'code' => 'ABS',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    Feature::query()->create([
        'feature_category_id' => $comfortCategory->id,
        'code' => 'Hidden comfort feature',
        'sort_order' => 3,
        'is_active' => false,
    ]);

    Feature::query()->create([
        'feature_category_id' => $safetyCategory->id,
        'code' => 'Blind spot',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Feature::query()->create([
        'feature_category_id' => $inactiveCategory->id,
        'code' => 'Should not appear',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $vehicleAd->features()->sync([$airco->id]);

    $response = $this->actingAs($dealer)->get(route('vehicles.edit', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Edit')
            ->has('featureCategories', 2)
            ->where('featureCategories.0.code', 'comfort')
            ->where('featureCategories.0.features.0.code', 'Airco')
            ->where('featureCategories.0.features.1.code', 'ABS')
            ->where('featureCategories.1.code', 'safety')
            ->where('featureCategories.1.features.0.code', 'Blind spot')
            ->has('ad.features', 1)
            ->where('ad.features.0.id', $airco->id)
            ->where('selectedFeatureIds.0', (string) $airco->id)
        );
});

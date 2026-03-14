<?php

use App\Models\User;
use App\Models\VehicleAd;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('sets canEdit to true on show page for the owner dealer', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $owner->assignRole('dealer');

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $owner->id,
    ]);

    $response = $this->actingAs($owner)->get(route('vehicles.show', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->where('canEdit', true)
        );
});

it('sets canEdit to false on show page for another dealer', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $owner->assignRole('dealer');

    $otherDealer = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $otherDealer->assignRole('dealer');

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $owner->id,
    ]);

    $response = $this->actingAs($otherDealer)->get(route('vehicles.show', $vehicleAd));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->where('canEdit', false)
        );
});

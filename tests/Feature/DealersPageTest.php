<?php

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('dealers page can be rendered', function () {
    SubscriptionPlan::query()->create([
        'key' => 'pro',
        'price' => 19.99,
        'listing_limit' => 25,
        'featured_limit' => 0,
        'duration_days' => 30,
        'image_limit_per_vehicle' => 20,
    ]);

    $response = $this->get(route('dealers.index'));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dealers/Index')
            ->has('plans', 1)
            ->where('plans.0.key', 'pro')
            ->where('plans.0.listing_limit', 25));
});

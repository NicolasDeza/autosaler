<?php

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('garages index page can be rendered', function () {
    $company = Company::factory()->create();

    $response = $this->get(route('garages.index'));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/Index')
            ->has('garages.data', 1)
            ->has('garages.data.0.logo_url')
            ->has('garages.data.0.background_url')
            ->where('garages.data.0.id', $company->id));
});

test('garage show route is removed', function () {
    $garageRoutes = collect(Route::getRoutes()->getRoutesByName())
        ->keys()
        ->filter(fn (string $name): bool => Str::startsWith($name, 'garages.'))
        ->values()
        ->all();

    expect($garageRoutes)->toBe(['garages.index']);
});

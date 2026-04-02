<?php

use App\Models\City;
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

test('garages index can be filtered by search', function () {
    $matchingCompany = Company::factory()->create([
        'name' => 'Garage Atlas',
    ]);
    Company::factory()->create([
        'name' => 'Garage Neptune',
    ]);

    $response = $this->get(route('garages.index', [
        'search' => 'atlas',
    ]));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/Index')
            ->where('filters.search', 'atlas')
            ->has('garages.data', 1)
            ->where('garages.data.0.id', $matchingCompany->id));
});

test('garages index can be sorted by name', function () {
    Company::factory()->create(['name' => 'Garage Alpha']);
    Company::factory()->create(['name' => 'Garage Zulu']);

    $ascendingResponse = $this->get(route('garages.index', [
        'sort' => 'name_asc',
    ]));

    $ascendingResponse
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/Index')
            ->where('filters.sort', 'name_asc')
            ->where('garages.data.0.name', 'Garage Alpha'));

    $descendingResponse = $this->get(route('garages.index', [
        'sort' => 'name_desc',
    ]));

    $descendingResponse
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/Index')
            ->where('filters.sort', 'name_desc')
            ->where('garages.data.0.name', 'Garage Zulu'));
});

test('garages index can be filtered by city id', function () {
    $matchingCity = City::factory()->create([
        'code' => 'Waterloo',
        'zip_code' => '1410',
    ]);
    $otherCity = City::factory()->create([
        'code' => 'Namur',
        'zip_code' => '5000',
    ]);

    $matchingCompany = Company::factory()->create([
        'city_id' => $matchingCity->id,
    ]);
    Company::factory()->create([
        'city_id' => $otherCity->id,
    ]);

    $response = $this->get(route('garages.index', [
        'city' => '1410 Waterloo',
        'city_id' => $matchingCity->id,
    ]));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/Index')
            ->where('filters.city', '1410 Waterloo')
            ->where('filters.city_id', $matchingCity->id)
            ->has('garages.data', 1)
            ->where('garages.data.0.id', $matchingCompany->id));
});

test('garage show route is removed', function () {
    $garageRoutes = collect(Route::getRoutes()->getRoutesByName())
        ->keys()
        ->filter(fn (string $name): bool => Str::startsWith($name, 'garages.'))
        ->values()
        ->all();

    expect($garageRoutes)->toBe(['garages.index']);
});

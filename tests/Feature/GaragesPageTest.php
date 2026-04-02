<?php

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('garages index page can be rendered', function () {
    $company = Company::factory()->create();

    $response = $this->get(route('garages.index'));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/index')
            ->has('garages.data', 1)
            ->has('garages.data.0.logo_url')
            ->has('garages.data.0.background_url')
            ->where('garages.data.0.id', $company->id));
});

test('garage show page can be rendered', function () {
    $company = Company::factory()->create();

    $response = $this->get(route('garages.show', $company));

    $response
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Garages/show')
            ->has('garage.logo_url')
            ->has('garage.background_url')
            ->where('garage.id', $company->id));
});

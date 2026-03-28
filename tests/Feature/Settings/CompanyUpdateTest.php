<?php

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'dealer']);

    $this->country = Country::factory()->create();
    $this->city = City::factory()->create(['country_id' => $this->country->id]);
    $this->company = Company::factory()->create([
        'city_id' => $this->city->id,
        'country_id' => $this->country->id,
    ]);
    $this->user = User::factory()->create(['company_id' => $this->company->id]);
    $this->user->assignRole('dealer');
});

test('authenticated user can update company information', function () {
    $response = $this->actingAs($this->user)
        ->post(route('company.update'), [
            '_method' => 'PATCH',
            'name' => 'New Company Name',
            'email' => 'new@example.com',
            'address' => '123 New Street',
            'city_id' => $this->city->id,
            'country_id' => $this->country->id,
            'phone' => '123456789',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status', 'company-updated');
    $this->assertEquals('New Company Name', $this->company->fresh()->name);
    $this->assertEquals('new@example.com', $this->company->fresh()->email);
});

test('authenticated user can upload a logo', function () {
    Storage::fake('public');

    $logo = UploadedFile::fake()->image('logo.jpg', 300, 300);

    $response = $this->actingAs($this->user)
        ->post(route('company.update'), [
            '_method' => 'PATCH',
            'name' => $this->company->name,
            'email' => $this->company->email,
            'address' => $this->company->address,
            'city_id' => $this->city->id,
            'country_id' => $this->country->id,
            'phone' => $this->company->phone,
            'logo' => $logo,
        ]);

    $response->assertSessionHas('status', 'company-updated');
    $this->assertCount(1, $this->company->fresh()->getMedia('logo'));
});

test('authenticated user can upload a background', function () {
    Storage::fake('public');

    $background = UploadedFile::fake()->image('background.jpg', 800, 400);

    $response = $this->actingAs($this->user)
        ->post(route('company.update'), [
            '_method' => 'PATCH',
            'name' => $this->company->name,
            'email' => $this->company->email,
            'address' => $this->company->address,
            'city_id' => $this->city->id,
            'country_id' => $this->country->id,
            'phone' => $this->company->phone,
            'background' => $background,
        ]);

    $response->assertSessionHas('status', 'company-updated');
    $this->assertCount(1, $this->company->fresh()->getMedia('background'));
});

test('uploading a new logo replaces the old one', function () {
    Storage::fake('public');

    $oldLogo = UploadedFile::fake()->image('old_logo.jpg');
    $this->company->addMedia($oldLogo)->toMediaCollection('logo');
    $this->assertCount(1, $this->company->fresh()->getMedia('logo'));

    $newLogo = UploadedFile::fake()->image('new_logo.jpg');

    $this->actingAs($this->user)
        ->post(route('company.update'), [
            '_method' => 'PATCH',
            'name' => $this->company->name,
            'email' => $this->company->email,
            'address' => $this->company->address,
            'city_id' => $this->city->id,
            'country_id' => $this->country->id,
            'phone' => $this->company->phone,
            'logo' => $newLogo,
        ]);

    $this->assertCount(1, $this->company->fresh()->getMedia('logo'));
    // Check path instead of exact filename if Spatie renames it
    $this->assertStringContainsString('new_logo', $this->company->fresh()->getFirstMedia('logo')->file_name);
});

<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Models\VehicleAd;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VehicleAdShowCompanyMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_show_page_receives_company_media_urls()
    {
        Storage::fake('public');

        $country = Country::factory()->create();
        $city = City::factory()->create(['country_id' => $country->id]);

        $user = User::factory()->create();
        $user->assignRole('dealer');

        $company = Company::factory()->create([
            'city_id' => $city->id,
            'country_id' => $country->id,
        ]);
        $user->update(['company_id' => $company->id]);

        // Add media to company
        $company->addMedia(UploadedFile::fake()->image('logo.jpg'))
            ->toMediaCollection('logo');
        $company->addMedia(UploadedFile::fake()->image('background.jpg'))
            ->toMediaCollection('background');

        $vehicleAd = VehicleAd::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('vehicles.show', $vehicleAd));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('VehicleAds/Show')
            ->has('ad.user.company', fn (Assert $page) => $page
                ->has('logo_url')
                ->has('background_url')
                ->whereNot('logo_url', null)
                ->whereNot('background_url', null)
                ->etc()
            )
        );
    }
}

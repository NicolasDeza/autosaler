<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\VehicleAd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response()
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->has('recentVehicles')
                ->has('featuredGarages'));
    }

    public function test_home_returns_up_to_four_random_featured_garages_from_existing_companies(): void
    {
        $allGarageIds = collect();

        Company::factory()->count(5)->create()->each(function (Company $company) use ($allGarageIds): void {
            $user = User::factory()->create(['company_id' => $company->id]);

            VehicleAd::factory()->create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            $allGarageIds->push($company->id);
        });

        $inactiveCompany = Company::factory()->create();
        $inactiveUser = User::factory()->create(['company_id' => $inactiveCompany->id]);
        $allGarageIds->push($inactiveCompany->id);

        VehicleAd::factory()->create([
            'user_id' => $inactiveUser->id,
            'status' => 'draft',
        ]);

        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/Index')
                ->has('featuredGarages', 4)
                ->where('featuredGarages', fn ($garages): bool => collect($garages)->every(
                    fn ($garage): bool => in_array(data_get($garage, 'id'), $allGarageIds->all(), true),
                )));
    }
}

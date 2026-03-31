<?php

use App\Mail\DealerRegistrationConfirmation;
use App\Mail\DealerRegistrationSubmitted;
use App\Models\City;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

function createSubscriptionPlansForDealerTests(): void
{
    SubscriptionPlan::query()->insert([
        [
            'key' => 'starter',
            'price' => 9.99,
            'listing_limit' => 10,
            'featured_limit' => 0,
            'duration_days' => 30,
            'image_limit_per_vehicle' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'key' => 'pro',
            'price' => 19.99,
            'listing_limit' => 25,
            'featured_limit' => 0,
            'duration_days' => 30,
            'image_limit_per_vehicle' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'key' => 'business',
            'price' => 39.99,
            'listing_limit' => 50,
            'featured_limit' => 0,
            'duration_days' => 30,
            'image_limit_per_vehicle' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'key' => 'premium',
            'price' => 79.99,
            'listing_limit' => 100,
            'featured_limit' => 0,
            'duration_days' => 30,
            'image_limit_per_vehicle' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}

it('registers a dealer lead, logs user in, and notifies admin', function () {
    Mail::fake();

    config()->set('mail.admin_address', 'admin@example.com');
    createSubscriptionPlansForDealerTests();
    $subscriptionPlan = SubscriptionPlan::query()->where('key', 'pro')->firstOrFail();

    $countryId = DB::table('countries')->insertGetId([
        'code' => 'BEL',
        'iso2' => 'BE',
        'iso3' => 'BEL',
        'phone_code' => '32',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $city = City::query()->create([
        'country_id' => $countryId,
        'code' => 'Brussels',
        'zip_code' => '1000',
    ]);

    $response = $this->from(route('dealers.index'))->post(route('dealers.register'), [
        'first_name' => 'Alice',
        'last_name' => 'Martin',
        'email' => 'alice@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_name' => 'Garage Martin',
        'company_email' => 'contact@garagemartin.test',
        'company_phone' => '+32470111222',
        'tva_number' => 'BE0123456789',
        'company_address' => 'Rue de la Loi 1',
        'city_id' => $city->id,
        'subscription_plan_id' => $subscriptionPlan->id,
        'message' => 'Nous souhaitons commencer rapidement.',
    ]);

    $response
        ->assertRedirect(route('dealers.index'))
        ->assertSessionHas('dealerRegistrationSuccess', true);

    $company = Company::query()->where('email', 'contact@garagemartin.test')->first();
    $user = User::query()->where('email', 'alice@example.com')->first();

    expect($company)->not->toBeNull();
    expect($user)->not->toBeNull();
    expect($company?->country_id)->toBe($countryId);
    expect($company?->city_id)->toBe($city->id);
    expect($user?->company_id)->toBe($company?->id);
    $this->assertAuthenticatedAs($user);

    Mail::assertSent(DealerRegistrationSubmitted::class, function (DealerRegistrationSubmitted $mail) use ($company, $user): bool {
        $html = $mail->render();

        return $mail->registration['company']->id === $company?->id
            && $mail->registration['user']->id === $user?->id
            && $mail->registration['subscription_plan']->key === 'pro'
            && ! str_contains($html, 'Ã')
            && ! str_contains($html, 'Â©');
    });

    Mail::assertSent(DealerRegistrationConfirmation::class, function (DealerRegistrationConfirmation $mail) use ($user, $company): bool {
        $html = $mail->render();

        return $user !== null
            && $mail->hasTo($user->email)
            && $mail->user->is($user)
            && $company !== null
            && $mail->company->is($company)
            && (
                str_contains($html, 'Demande d’inscription professionnelle reçue')
                || str_contains($html, 'Professional registration request received')
            )
            && ! str_contains($html, 'Ã')
            && ! str_contains($html, 'Â©');
    });
});

it('validates requested subscription plan', function () {
    createSubscriptionPlansForDealerTests();
    $countryId = DB::table('countries')->insertGetId([
        'code' => 'BEL',
        'iso2' => 'BE',
        'iso3' => 'BEL',
        'phone_code' => '32',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $city = City::query()->create([
        'country_id' => $countryId,
        'code' => 'Brussels',
        'zip_code' => '1000',
    ]);

    $response = $this->from(route('dealers.index'))->post(route('dealers.register'), [
        'first_name' => 'Alice',
        'last_name' => 'Martin',
        'email' => 'alice@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_name' => 'Garage Martin',
        'company_email' => 'contact@garagemartin.test',
        'company_phone' => '+32470111222',
        'tva_number' => 'BE0123456789',
        'company_address' => 'Rue de la Loi 1',
        'city_id' => $city->id,
        'subscription_plan_id' => 999999,
    ]);

    $response
        ->assertRedirect(route('dealers.index'))
        ->assertSessionHasErrors(['subscription_plan_id']);

    expect(Company::query()->count())->toBe(0);
    expect(User::query()->count())->toBe(0);
});

it('requires company phone for dealer registration', function () {
    createSubscriptionPlansForDealerTests();
    $subscriptionPlan = SubscriptionPlan::query()->where('key', 'pro')->firstOrFail();
    $countryId = DB::table('countries')->insertGetId([
        'code' => 'BEL',
        'iso2' => 'BE',
        'iso3' => 'BEL',
        'phone_code' => '32',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $city = City::query()->create([
        'country_id' => $countryId,
        'code' => 'Brussels',
        'zip_code' => '1000',
    ]);

    $response = $this->from(route('dealers.index'))->post(route('dealers.register'), [
        'first_name' => 'Alice',
        'last_name' => 'Martin',
        'email' => 'alice@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_name' => 'Garage Martin',
        'company_email' => 'contact@garagemartin.test',
        'company_phone' => '',
        'tva_number' => 'BE0123456789',
        'company_address' => 'Rue de la Loi 1',
        'city_id' => $city->id,
        'subscription_plan_id' => $subscriptionPlan->id,
    ]);

    $response
        ->assertRedirect(route('dealers.index'))
        ->assertSessionHasErrors(['company_phone']);
});

it('shares success flash on dealers page and keeps user authenticated', function () {
    createSubscriptionPlansForDealerTests();
    $subscriptionPlan = SubscriptionPlan::query()->where('key', 'pro')->firstOrFail();
    $countryId = DB::table('countries')->insertGetId([
        'code' => 'BEL',
        'iso2' => 'BE',
        'iso3' => 'BEL',
        'phone_code' => '32',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $city = City::query()->create([
        'country_id' => $countryId,
        'code' => 'Brussels',
        'zip_code' => '1000',
    ]);

    $response = $this->from(route('dealers.index'))->followingRedirects()->post(route('dealers.register'), [
        'first_name' => 'Alice',
        'last_name' => 'Martin',
        'email' => 'alice@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_name' => 'Garage Martin',
        'company_email' => 'contact@garagemartin.test',
        'company_phone' => '+32470111222',
        'tva_number' => 'BE0123456789',
        'company_address' => 'Rue de la Loi 1',
        'city_id' => $city->id,
        'subscription_plan_id' => $subscriptionPlan->id,
    ]);

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dealers/Index')
            ->where('flash.dealerRegistrationSuccess', true));

    $user = User::query()->where('email', 'alice@example.com')->first();

    expect($user)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});

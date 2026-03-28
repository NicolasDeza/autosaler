<?php

use App\Mail\VehicleSellerContact;
use App\Models\City;
use App\Models\Company;
use App\Models\User;
use App\Models\VehicleAd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function createCityForSellerContactTests(): City
{
    $countryId = DB::table('countries')->insertGetId([
        'code' => 'BEL',
        'iso2' => 'BE',
        'iso3' => 'BEL',
        'phone_code' => '32',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return City::query()->create([
        'country_id' => $countryId,
        'code' => 'Bruxelles',
        'zip_code' => '1000',
    ]);
}

function sellerContactPayload(array $overrides = []): array
{
    return array_merge([
        'message' => 'Bonjour, ce vehicule est-il toujours disponible ?',
        'first_name' => 'Nicolas',
        'last_name' => 'Martin',
        'email' => 'nicolas@example.com',
        'phone' => '+32470000000',
    ], $overrides);
}

it('sends a contact email to the seller company and increments contact count', function () {
    Mail::fake();

    $city = createCityForSellerContactTests();

    $company = Company::query()->create([
        'name' => 'John Garage',
        'email' => 'seller-company@example.com',
        'address' => '240 Avenue Louise',
        'city_id' => $city->id,
        'country_id' => $city->country_id,
        'tva_number' => 'BE0123456789',
        'phone' => '+3221112233',
    ]);

    $seller = User::factory()->create([
        'company_id' => $company->id,
        'email' => 'seller-user@example.com',
    ]);

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $seller->id,
        'status' => 'active',
    ]);

    $response = $this
        ->from(route('vehicles.show', $vehicleAd))
        ->post(route('vehicles.contact', $vehicleAd), sellerContactPayload());

    $response
        ->assertRedirect(route('vehicles.show', $vehicleAd))
        ->assertSessionHas('success', true);

    Mail::assertSent(VehicleSellerContact::class, function (VehicleSellerContact $mail) use ($company, $vehicleAd): bool {
        return $mail->hasTo($company->email)
            && $mail->vehicleAd->is($vehicleAd)
            && $mail->contact['email'] === 'nicolas@example.com'
            && $mail->contact['message'] === 'Bonjour, ce vehicule est-il toujours disponible ?';
    });

    $this->assertDatabaseHas('vehicle_stats', [
        'vehicle_ad_id' => $vehicleAd->id,
        'contact_count' => 1,
    ]);
});

it('falls back to seller user email when company is missing', function () {
    Mail::fake();

    $seller = User::factory()->create([
        'company_id' => null,
        'email' => 'seller-user@example.com',
    ]);

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $seller->id,
        'status' => 'active',
    ]);

    $this
        ->from(route('vehicles.show', $vehicleAd))
        ->post(route('vehicles.contact', $vehicleAd), sellerContactPayload([
            'email' => 'buyer@example.com',
        ]))
        ->assertRedirect(route('vehicles.show', $vehicleAd))
        ->assertSessionHas('success', true);

    Mail::assertSent(VehicleSellerContact::class, function (VehicleSellerContact $mail) use ($seller): bool {
        return $mail->hasTo($seller->email)
            && $mail->contact['email'] === 'buyer@example.com';
    });
});

it('renders the seller contact email with the branded autosaler layout', function () {
    $city = createCityForSellerContactTests();

    $company = Company::query()->create([
        'name' => 'John Garage',
        'email' => 'seller-company@example.com',
        'address' => '240 Avenue Louise',
        'city_id' => $city->id,
        'country_id' => $city->country_id,
        'tva_number' => 'BE0123456789',
        'phone' => '+3221112233',
    ]);

    $seller = User::factory()->create([
        'company_id' => $company->id,
    ]);

    $vehicleAd = VehicleAd::factory()->create([
        'user_id' => $seller->id,
        'status' => 'active',
    ]);

    $html = (new VehicleSellerContact(
        sellerContactPayload(),
        $vehicleAd,
    ))->render();

    expect($html)
        ->toContain('AutoSaler')
        ->toContain('Nouvelle demande de contact')
        ->toContain('Contact acheteur')
        ->toContain('Voir l\'annonce')
        ->toContain('Tous droits reserves');
});

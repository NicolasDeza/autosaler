<?php

use App\Mail\VehicleSellerContact;
use App\Models\VehicleAd;
use Illuminate\Support\Facades\Mail;

it('sends a contact email to the seller and increments contact count', function () {
    Mail::fake();

    $vehicleAd = VehicleAd::factory()->create();

    $response = $this->post(route('vehicles.contact', $vehicleAd), [
        'message' => 'Bonjour, ce vehicule est-il toujours disponible ?',
        'first_name' => 'Nicolas',
        'last_name' => 'Martin',
        'email' => 'nicolas@example.com',
        'phone' => '+32 477 123 456',
    ]);

    $response->assertRedirect();

    Mail::assertSent(VehicleSellerContact::class, function (VehicleSellerContact $mail) use ($vehicleAd) {
        return $mail->hasTo($vehicleAd->user->email)
            && $mail->contact['email'] === 'nicolas@example.com'
            && $mail->vehicleAd->is($vehicleAd);
    });

    expect($vehicleAd->fresh()->stat?->contact_count)->toBe(1);
});

it('validates required seller contact fields', function () {
    $vehicleAd = VehicleAd::factory()->create();

    $response = $this->post(route('vehicles.contact', $vehicleAd), []);

    $response->assertSessionHasErrors([
        'message',
        'first_name',
        'last_name',
        'email',
    ]);
});

it('allows sending a message with only first name and without phone', function () {
    Mail::fake();

    $vehicleAd = VehicleAd::factory()->create();

    $response = $this->post(route('vehicles.contact', $vehicleAd), [
        'message' => 'Je souhaite planifier un rendez-vous.',
        'first_name' => 'Lina',
        'email' => 'lina@example.com',
    ]);

    $response->assertRedirect();

    Mail::assertSent(VehicleSellerContact::class);
});

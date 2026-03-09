<?php

use App\Mail\SubscriptionInquiry;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);

it('sends a subscription inquiry email to admin', function () {
    Mail::fake();

    $response = post('/subscription-inquiry', [
        'company_name' => 'Garage Dupont SA',
        'contact_name' => 'Jean Dupont',
        'email' => 'jean@garage-dupont.be',
        'phone' => '+32 477 123 456',
        'plan_listings' => 25,
        'message' => 'Intéressé par le plan 25 annonces.',
    ]);

    $response->assertRedirect();

    Mail::assertSent(SubscriptionInquiry::class, function ($mail) {
        return $mail->inquiry['company_name'] === 'Garage Dupont SA'
            && $mail->inquiry['plan_listings'] === 25;
    });
});

it('validates required fields for subscription inquiry', function () {
    $response = $this->post('/subscription-inquiry', []);

    $response->assertSessionHasErrors([
        'company_name',
        'contact_name',
        'email',
        'phone',
        'plan_listings',
    ]);
});

it('rejects invalid plan listings value', function () {
    $response = $this->post('/subscription-inquiry', [
        'company_name' => 'Test',
        'contact_name' => 'Test',
        'email' => 'test@test.be',
        'phone' => '+32 477 000 000',
        'plan_listings' => 999,
    ]);

    $response->assertSessionHasErrors(['plan_listings']);
});

it('allows empty message for subscription inquiry', function () {
    Mail::fake();

    $response = $this->post('/subscription-inquiry', [
        'company_name' => 'Garage Test',
        'contact_name' => 'Test User',
        'email' => 'test@garage.be',
        'phone' => '+32 477 111 222',
        'plan_listings' => 50,
        'message' => '',
    ]);

    $response->assertRedirect();
    Mail::assertSent(SubscriptionInquiry::class);
});

it('sends email to the configured admin address', function () {
    Mail::fake();

    $this->post('/subscription-inquiry', [
        'company_name' => 'Garage Admin Test',
        'contact_name' => 'Admin',
        'email' => 'admin@test.be',
        'phone' => '+32 477 000 111',
        'plan_listings' => 10,
    ]);

    Mail::assertSent(SubscriptionInquiry::class, function ($mail) {
        return $mail->hasTo(config('mail.admin_address', config('mail.from.address')));
    });
});

it('sends email with correct subject containing company name', function () {
    Mail::fake();

    $this->post('/subscription-inquiry', [
        'company_name' => 'Auto Dupont',
        'contact_name' => 'Jean',
        'email' => 'jean@test.be',
        'phone' => '+32 477 000 222',
        'plan_listings' => 25,
    ]);

    Mail::assertSent(SubscriptionInquiry::class, function ($mail) {
        $envelope = $mail->envelope();

        return str_contains($envelope->subject, 'Auto Dupont');
    });
});

it('email content renders company and plan details', function () {
    $mailable = new SubscriptionInquiry([
        'company_name' => 'Garage Rendu',
        'contact_name' => 'Pierre',
        'email' => 'pierre@garage.be',
        'phone' => '+32 477 999 888',
        'plan_listings' => 100,
        'message' => 'Test rendu du template.',
    ]);

    $mailable->assertSeeInHtml('Garage Rendu');
    $mailable->assertSeeInHtml('pierre@garage.be');
    $mailable->assertSeeInHtml('100');
    $mailable->assertSeeInHtml('Test rendu du template.');
});

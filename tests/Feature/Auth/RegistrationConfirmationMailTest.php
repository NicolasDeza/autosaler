<?php

use App\Mail\UserRegistrationConfirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function registrationPayload(array $overrides = []): array
{
    return array_merge([
        'first_name' => 'Nicolas',
        'last_name' => 'Martin',
        'email' => 'nicolas@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ], $overrides);
}

it('sends a french confirmation mail when registration locale is french', function () {
    Mail::fake();

    $response = $this
        ->withSession(['locale' => 'fr'])
        ->post(route('register.store'), registrationPayload());

    $response->assertRedirect();
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'nicolas@example.com')->first();

    expect($user)->not->toBeNull();

    Mail::assertSent(UserRegistrationConfirmation::class, function (UserRegistrationConfirmation $mail): bool {
        $html = $mail->render();

        return $mail->locale === 'fr'
            && $mail->hasTo('nicolas@example.com')
            && $mail->user->first_name === 'Nicolas'
            && $mail->user->last_name === 'Martin'
            && str_contains($html, 'Inscription confirmée')
            && ! str_contains($html, 'Ã')
            && ! str_contains($html, 'Â©');
    });
});

it('sends an english confirmation mail when registration locale is english', function () {
    Mail::fake();

    $response = $this
        ->withSession(['locale' => 'en'])
        ->post(route('register.store'), registrationPayload([
            'email' => 'john@example.com',
        ]));

    $response->assertRedirect();
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'john@example.com')->first();

    expect($user)->not->toBeNull();

    Mail::assertSent(UserRegistrationConfirmation::class, function (UserRegistrationConfirmation $mail): bool {
        $html = $mail->render();

        return $mail->locale === 'en'
            && $mail->hasTo('john@example.com')
            && str_contains($html, 'Registration confirmed')
            && ! str_contains($html, 'Ã')
            && ! str_contains($html, 'Â©');
    });

    expect(User::query()->count())->toBe(1);
});

<?php

use App\Mail\UserRegistrationConfirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('sends a registration confirmation mail to newly registered users', function () {
    Mail::fake();

    $response = $this->post(route('register.store'), [
        'first_name' => 'Nicolas',
        'last_name' => 'Martin',
        'email' => 'nicolas@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect();
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'nicolas@example.com')->first();

    expect($user)->not->toBeNull();

    Mail::assertSent(UserRegistrationConfirmation::class, function (UserRegistrationConfirmation $mail): bool {
        return $mail->hasTo('nicolas@example.com')
            && $mail->user->first_name === 'Nicolas'
            && $mail->user->last_name === 'Martin';
    });

    expect(User::query()->count())->toBe(1);
});

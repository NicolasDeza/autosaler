<?php

use App\Mail\ContactAdminMessage;
use Illuminate\Support\Facades\Mail;

function contactAdminPayload(array $overrides = []): array
{
    return array_merge([
        'topic' => 'bug',
        'message' => 'Le bouton contact du footer ne repond pas.',
        'first_name' => 'Nicolas',
        'last_name' => 'Martin',
        'email' => 'nicolas@example.com',
        'phone' => '+32470000000',
    ], $overrides);
}

it('sends a contact message to the admin email', function () {
    Mail::fake();

    config()->set('mail.admin_address', 'admin@example.com');

    $response = $this
        ->from(route('home'))
        ->post(route('contact.send'), contactAdminPayload());

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHas('success', true);

    Mail::assertSent(ContactAdminMessage::class, function (ContactAdminMessage $mail): bool {
        $html = $mail->render();

        return $mail->hasTo('admin@example.com')
            && $mail->contact['topic'] === 'bug'
            && $mail->contact['email'] === 'nicolas@example.com'
            && $mail->contact['message'] === 'Le bouton contact du footer ne repond pas.'
            && str_contains($html, 'Détails de la demande')
            && str_contains($html, 'Téléphone')
            && str_contains($html, 'Message envoyé depuis le formulaire de contact du site.')
            && ! str_contains($html, 'Ã')
            && ! str_contains($html, 'Â©');
    });
});

it('validates required contact fields', function () {
    $response = $this->post(route('contact.send'), []);

    $response->assertSessionHasErrors([
        'topic',
        'message',
        'first_name',
        'last_name',
        'email',
    ]);
});

it('accepts a message with only first name and no phone', function () {
    Mail::fake();

    config()->set('mail.admin_address', 'admin@example.com');

    $response = $this->post(route('contact.send'), contactAdminPayload([
        'topic' => 'info',
        'last_name' => '',
        'phone' => '',
    ]));

    $response->assertRedirect();

    Mail::assertSent(ContactAdminMessage::class, function (ContactAdminMessage $mail): bool {
        $html = $mail->render();

        return $mail->hasTo('admin@example.com')
            && $mail->contact['topic'] === 'info'
            && $mail->contact['first_name'] === 'Nicolas'
            && str_contains($html, 'Information');
    });
});

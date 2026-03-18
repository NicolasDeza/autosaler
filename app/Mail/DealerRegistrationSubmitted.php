<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerRegistrationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $registration
     */
    public function __construct(
        public readonly array $registration,
    ) {}

    public function envelope(): Envelope
    {
        $companyName = $this->registration['company']->name;

        return new Envelope(
            subject: "Nouvelle inscription pro - {$companyName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.dealer-registration-submitted',
        );
    }
}

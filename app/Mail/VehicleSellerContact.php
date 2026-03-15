<?php

namespace App\Mail;

use App\Models\VehicleAd;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleSellerContact extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $contact,
        public readonly VehicleAd $vehicleAd,
    ) {}

    public function envelope(): Envelope
    {
        $senderName = trim(
            sprintf(
                '%s %s',
                (string) ($this->contact['first_name'] ?? ''),
                (string) ($this->contact['last_name'] ?? ''),
            )
        );

        return new Envelope(
            subject: "Nouvelle demande de contact pour l'annonce #{$this->vehicleAd->id}",
            replyTo: [
                new Address(
                    $this->contact['email'],
                    $senderName !== '' ? $senderName : null,
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.vehicle-seller-contact',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

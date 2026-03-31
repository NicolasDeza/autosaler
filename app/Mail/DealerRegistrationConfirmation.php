<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Company $company,
        public readonly SubscriptionPlan $subscriptionPlan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('dealerRegistrationMail.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.dealer-registration-confirmation',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $contact) {}

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
            subject: __('contactAdminMail.subject', ['topic' => $this->topicLabel()]),
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
            view: 'mail.contact-admin-message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    protected function topicLabel(): string
    {
        return match ($this->contact['topic'] ?? null) {
            'info' => __('contactAdminMail.topics.info'),
            'bug' => __('contactAdminMail.topics.bug'),
            default => __('contactAdminMail.topics.other'),
        };
    }
}

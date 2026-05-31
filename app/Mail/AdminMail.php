<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $backoff = [10, 30, 60];

    public int $tries = 3;

    public function __construct(
        public string $emailSubject,
        public string $emailBody,
        public ?string $template = null,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = match ($this->template) {
            'announcement' => 'mail.admin-email-announcement',
            'notice' => 'mail.admin-email-notice',
            'update' => 'mail.admin-email-update',
            default => 'mail.admin-email',
        };

        return new Content(view: $view);
    }
}

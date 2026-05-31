<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewFeatureLaunchMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(
        public string $name,
        public string $ctaUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎤 AI Mock Interviews are now live on '.config('app.name'),
            from: new Address(config('mail.from.address', 'noreply@seratyai.com'), config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.interview-feature-launch',
        );
    }
}

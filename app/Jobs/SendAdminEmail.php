<?php

namespace App\Jobs;

use App\Mail\AdminMail;
use App\Models\SentMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Send a single admin-composed email to a single user and record the SentMail row.
 *
 * Runs on the queue so the admin action returns immediately. Each job is
 * scoped to exactly one recipient — there is no To-list — which avoids the
 * historical "one user gets a copy addressed to everyone" leak.
 */
class SendAdminEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [10, 30, 60];

    public $timeout = 60;

    public function __construct(
        public int $recipientId,
        public string $subject,
        public string $body,
        public ?string $template,
    ) {}

    public function handle(): void
    {
        $recipient = User::find($this->recipientId);

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new AdminMail(
                emailSubject: $this->subject,
                emailBody: $this->body,
                template: $this->template,
            ));

            SentMail::create([
                'user_id' => $recipient->id,
                'recipient_email' => $recipient->email,
                'subject' => $this->subject,
                'body' => $this->body,
                'template' => $this->template,
                'status' => SentMail::STATUS_SENT,
            ]);
        } catch (\Throwable $e) {
            // Mark the send as failed but don't crash the batch — other
            // recipients should still get their copies.
            SentMail::create([
                'user_id' => $recipient->id,
                'recipient_email' => $recipient->email,
                'subject' => $this->subject,
                'body' => $this->body,
                'template' => $this->template,
                'status' => SentMail::STATUS_FAILED,
                'failed_reason' => mb_substr($e->getMessage(), 0, 1000),
            ]);

            throw $e;
        }
    }
}

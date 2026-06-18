<?php

use App\Jobs\SendAdminEmail;
use App\Mail\AdminMail;
use App\Models\SentMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

it('sends exactly one email to the single recipient and records a sent row', function () {
    $recipient = User::factory()->create(['email' => 'alice@example.com']);
    $otherUser = User::factory()->create(['email' => 'bob@example.com']);

    (new SendAdminEmail(
        recipientId: $recipient->id,
        subject: 'Hello Alice',
        body: '<p>Only Alice should see this.</p>',
        template: 'announcement',
    ))->handle();

    // Only Alice gets the mail — this is the explicit regression guard for
    // the historical "every recipient gets a copy addressed to everyone" bug.
    // AdminMail implements ShouldQueue, so assert via the queued mailbox.
    Mail::assertQueued(AdminMail::class, fn (AdminMail $mail) => $mail->hasTo('alice@example.com'));
    Mail::assertNotQueued(AdminMail::class, fn (AdminMail $mail) => $mail->hasTo('bob@example.com'));

    // The mailable was queued exactly once.
    Mail::assertQueued(AdminMail::class, 1);

    expect(SentMail::count())->toBe(1)
        ->and(SentMail::first()->recipient_email)->toBe('alice@example.com')
        ->and(SentMail::first()->subject)->toBe('Hello Alice')
        ->and(SentMail::first()->status)->toBe(SentMail::STATUS_SENT);
});

it('records a failed row instead of crashing when mail delivery throws', function () {
    $recipient = User::factory()->create();

    Mail::shouldReceive('to->send')
        ->andThrow(new RuntimeException('SMTP down'));

    try {
        (new SendAdminEmail(
            recipientId: $recipient->id,
            subject: 'Will fail',
            body: 'body',
            template: null,
        ))->handle();
    } catch (RuntimeException) {
        // The job re-throws after recording the failure so the queue can retry.
    }

    expect(SentMail::count())->toBe(1)
        ->and(SentMail::first()->status)->toBe(SentMail::STATUS_FAILED)
        ->and(SentMail::first()->failed_reason)->toBe('SMTP down');
});

it('does nothing when the recipient no longer exists', function () {
    (new SendAdminEmail(
        recipientId: 999999,
        subject: 'no one',
        body: 'no body',
        template: null,
    ))->handle();

    Mail::assertNothingSent();
    expect(SentMail::count())->toBe(0);
});

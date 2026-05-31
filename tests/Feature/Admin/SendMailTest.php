<?php

use App\Filament\Pages\SendMail;
use App\Mail\AdminMail;
use App\Models\SentMail;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::where('email', 'mamounprogrammer@gmail.com')->first()
        ?? User::factory()->create(['email' => 'mamounprogrammer@gmail.com']);
});

test('admin can access the send mail page', function () {
    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->assertSuccessful();
});

test('non-admin cannot access the send mail page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(SendMail::class)
        ->assertForbidden();
});

test('admin can send email to a specific user with template', function () {
    Mail::fake();

    $recipient = User::factory()->create();

    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'individual',
            'userId' => $recipient->id,
            'subject' => 'Test Subject',
            'template' => 'announcement',
            'body' => '<p>Test Body</p>',
        ])
        ->assertNotified();

    Mail::assertQueued(AdminMail::class, function (AdminMail $mail) use ($recipient) {
        return $mail->hasTo($recipient->email)
            && $mail->emailSubject === 'Test Subject'
            && $mail->template === 'announcement';
    });

    expect(SentMail::where('recipient_email', $recipient->email)->exists())->toBeTrue();
});

test('admin can send email to all users with notice template', function () {
    Mail::fake();

    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'all',
            'subject' => 'Broadcast Subject',
            'template' => 'notice',
            'body' => '<p>Broadcast Body</p>',
        ])
        ->assertNotified();

    Mail::assertQueued(AdminMail::class, function (AdminMail $mail) {
        return $mail->emailSubject === 'Broadcast Subject'
            && $mail->template === 'notice';
    });
});

test('template is stored on sent mail record', function () {
    Mail::fake();

    $recipient = User::factory()->create();

    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'individual',
            'userId' => $recipient->id,
            'subject' => 'Template Test',
            'template' => 'update',
            'body' => '<p>Body</p>',
        ])
        ->assertNotified();

    $sentMail = SentMail::where('recipient_email', $recipient->email)->first();

    expect($sentMail)->not->toBeNull()
        ->and($sentMail->template)->toBe('update');
});

test('sending to individual user requires userId', function () {
    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'individual',
            'subject' => 'Test Subject',
            'template' => 'announcement',
            'body' => '<p>Test Body</p>',
        ])
        ->assertHasActionErrors(['userId']);
});

test('subject, body, and template are required', function () {
    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'all',
        ])
        ->assertHasActionErrors(['subject', 'body', 'template']);
});

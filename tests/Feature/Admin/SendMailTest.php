<?php

use App\Filament\Pages\SendMail;
use App\Mail\AdminMail;
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

test('admin can send email to a specific user via action', function () {
    Mail::fake();

    $recipient = User::factory()->create();

    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'individual',
            'userId' => $recipient->id,
            'subject' => 'Test Subject',
            'body' => '<p>Test Body</p>',
        ])
        ->assertNotified();

    Mail::assertQueued(AdminMail::class, function (AdminMail $mail) use ($recipient) {
        return $mail->hasTo($recipient->email)
            && $mail->emailSubject === 'Test Subject';
    });
});

test('admin can send email to all users via action', function () {
    Mail::fake();

    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'all',
            'subject' => 'Broadcast Subject',
            'body' => '<p>Broadcast Body</p>',
        ])
        ->assertNotified();

    Mail::assertQueued(AdminMail::class, function (AdminMail $mail) {
        return $mail->emailSubject === 'Broadcast Subject';
    });
});

test('sending to individual user requires userId', function () {
    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'individual',
            'subject' => 'Test Subject',
            'body' => '<p>Test Body</p>',
        ])
        ->assertHasActionErrors(['userId']);
});

test('subject and body are required', function () {
    $this->actingAs($this->admin);

    Livewire::test(SendMail::class)
        ->callAction(TestAction::make('sendMail'), [
            'recipientType' => 'all',
        ])
        ->assertHasActionErrors(['subject', 'body']);
});

<?php

use App\Livewire\OtpVerification;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    // Set up pending registration data in session
    session([
        'pending_registration' => [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ref_code' => null,
        ],
    ]);
});

test('otp verification page can be rendered with pending registration', function () {
    $response = $this->get(route('otp.verify'));

    $response->assertOk()
        ->assertSee('Verify Your Email')
        ->assertSee('test@example.com');
});

test('otp verification page redirects to login without pending registration', function () {
    session()->forget('pending_registration');

    $response = $this->get(route('otp.verify'));

    $response->assertRedirect(route('login'));
});

test('user is created after successful otp verification', function () {
    $otp = '123456';
    session([
        'otp_code' => $otp,
        'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
    ]);

    Livewire::test(OtpVerification::class)
        ->set('otp', $otp)
        ->call('verify')
        ->assertRedirect(route('drafts', absolute: false));

    // Verify user was created
    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('John Doe')
        ->and($user->otp_verified_at)->not->toBeNull();

    // Verify session was cleaned up
    expect(session('pending_registration'))->toBeNull()
        ->and(session('otp_code'))->toBeNull();

    // Verify user is logged in
    $this->assertAuthenticatedAs($user);
});

test('otp verification fails with invalid code', function () {
    session([
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
    ]);

    Livewire::test(OtpVerification::class)
        ->set('otp', '654321')
        ->call('verify')
        ->assertHasErrors(['otp']);

    // Verify user was NOT created
    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();

    // Verify session data is preserved
    expect(session('pending_registration'))->not->toBeNull();
});

test('otp verification fails with expired code', function () {
    session([
        'otp_code' => '123456',
        'otp_expires_at' => now()->subMinute()->toIso8601String(), // Expired
    ]);

    Livewire::test(OtpVerification::class)
        ->set('otp', '123456')
        ->call('verify')
        ->assertHasErrors(['otp']);

    // Verify user was NOT created
    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});

test('otp can be resent', function () {
    session([
        'otp_code' => '123456',
        'otp_sent_at' => now()->subMinutes(2), // Allow resend
    ]);

    Livewire::test(OtpVerification::class)
        ->call('sendOtp')
        ->assertDispatched('otp-sent');

    // Verify new OTP was generated
    expect(session('otp_code'))->not->toBe('123456')
        ->and(session('otp_expires_at'))->not->toBeNull();
});

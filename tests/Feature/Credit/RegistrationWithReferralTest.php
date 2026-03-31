<?php

use App\Livewire\OtpVerification;
use App\Models\ReferralCode;
use App\Models\User;
use Livewire\Livewire;

describe('Registration with Referral', function () {
    it('stores referral code in pending registration during registration', function () {
        $referrer = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $referrer->id, 'code' => 'REG001']);

        $this->withSession(['ref' => 'REG001'])->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // User should NOT be created yet (pending OTP verification)
        expect(User::where('email', 'test@example.com')->exists())->toBeFalse();

        // Referral code should be stored in pending registration
        expect(session('pending_registration')['ref_code'])->toBe('REG001');

        // Set up OTP in session and verify
        $otp = '123456';
        session([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
        ]);

        Livewire::test(OtpVerification::class)
            ->set('otp', $otp)
            ->call('verify');

        // Now user should be created with referral processed
        $newUser = User::where('email', 'test@example.com')->first();
        expect($newUser)->not->toBeNull();

        // Verify the referral was created
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $referrer->id,
            'referred_id' => $newUser->id,
        ]);
    });

    it('creates user after OTP verification without referral', function () {
        // Start registration without referral code
        $this->post('/register', [
            'name' => 'No Ref',
            'email' => 'nore@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Set up OTP in session and verify
        $otp = '123456';
        session([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
        ]);

        Livewire::test(OtpVerification::class)
            ->set('otp', $otp)
            ->call('verify');

        $user = User::where('email', 'nore@example.com')->first();
        expect($user)->not->toBeNull()
            ->and($user->otp_verified_at)->not->toBeNull();
    });
});

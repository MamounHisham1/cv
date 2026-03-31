<?php

use App\Models\CreditBalance;
use App\Models\ReferralCode;
use App\Models\User;

describe('Registration with Referral', function () {
    it('captures referral code from URL and stores in session', function () {
        $response = $this->get('/register?ref=ABC123');

        $response->assertSuccessful();
        expect(session('ref'))->toBe('ABC123');
    });

    it('normalizes referral code to uppercase', function () {
        $this->get('/register?ref=abc123');

        expect(session('ref'))->toBe('ABC123');
    });

    it('ignores invalid referral codes', function () {
        $this->get('/register?ref=toolongcode');

        expect(session('ref'))->toBeNull();
    });

    it('registers user and processes referral', function () {
        $referrer = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $referrer->id, 'code' => 'REG001']);

        $this->withSession(['ref' => 'REG001'])->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $newUser = User::where('email', 'test@example.com')->first();

        expect($newUser)->not->toBeNull();

        $referrerBalance = CreditBalance::where('user_id', $referrer->id)->first();
        expect($referrerBalance->balance)->toBe(10); // referral reward only
    });

    it('registers user without referral and gets monthly credits', function () {
        $this->post('/register', [
            'name' => 'No Ref User',
            'email' => 'nore@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'nore@example.com')->first();

        expect($user)->not->toBeNull();

        $balance = CreditBalance::where('user_id', $user->id)->first();
        expect($balance->balance)->toBe(30);
    });
});

<?php

use App\Models\Referral;
use App\Models\User;

describe('Referral Model', function () {
    it('creates a referral between two users', function () {
        $referrer = User::factory()->create();
        $referred = User::factory()->create();

        $referral = Referral::factory()->create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
        ]);

        expect($referral->referrer_id)->toBe($referrer->id)
            ->and($referral->referred_id)->toBe($referred->id)
            ->and($referral->signup_rewarded)->toBeTrue()
            ->and($referral->purchase_rewarded)->toBeFalse();
    });

    it('casts booleans correctly', function () {
        $referral = Referral::factory()->create([
            'signup_rewarded' => false,
            'purchase_rewarded' => false,
        ]);

        expect($referral->signup_rewarded)->toBeFalse()
            ->and($referral->purchase_rewarded)->toBeFalse();

        $referral->update(['signup_rewarded' => true]);

        expect($referral->fresh()->signup_rewarded)->toBeTrue();
    });

    it('belongs to a referrer', function () {
        $referral = Referral::factory()->create();

        expect($referral->referrer)->toBeInstanceOf(User::class);
    });

    it('belongs to a referred user', function () {
        $referral = Referral::factory()->create();

        expect($referral->referred)->toBeInstanceOf(User::class);
    });
});

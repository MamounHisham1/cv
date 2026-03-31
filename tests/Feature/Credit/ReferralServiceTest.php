<?php

use App\Models\CreditBalance;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;
use App\Services\ReferralService;

describe('ReferralService', function () {
    beforeEach(function () {
        $this->service = app(ReferralService::class);
    });

    it('generates a referral code for user', function () {
        $user = User::factory()->create();

        $code = $this->service->generateCodeForUser($user);

        expect($code)->toBeInstanceOf(ReferralCode::class)
            ->and($code->user_id)->toBe($user->id)
            ->and(strlen($code->code))->toBe(6);
    });

    it('does not create duplicate code for same user', function () {
        $user = User::factory()->create();

        $code1 = $this->service->generateCodeForUser($user);
        $code2 = $this->service->generateCodeForUser($user);

        expect($code1->id)->toBe($code2->id);
    });

    it('returns null for user without code', function () {
        $user = User::factory()->create();

        expect($this->service->getCodeForUser($user))->toBeNull();
    });

    it('returns code for user with code', function () {
        $user = User::factory()->create();
        $code = ReferralCode::factory()->create(['user_id' => $user->id]);

        expect($this->service->getCodeForUser($user)->id)->toBe($code->id);
    });

    it('generates referral link with code', function () {
        $user = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $user->id, 'code' => 'ABC123']);

        $link = $this->service->getReferralLink($user);

        expect($link)->toContain('ref=ABC123');
    });

    it('auto-generates code when getting link', function () {
        $user = User::factory()->create();

        $link = $this->service->getReferralLink($user);

        expect($link)->toContain('ref=');
    });

    it('awards credits to both parties on registration with referral', function () {
        $referrer = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $referrer->id, 'code' => 'ABCD12']);

        $newUser = User::factory()->create();

        $this->service->processReferralOnRegistration($newUser, 'ABCD12');

        $referrerBalance = CreditBalance::where('user_id', $referrer->id)->first();
        $newUserBalance = CreditBalance::where('user_id', $newUser->id)->first();

        expect($referrerBalance->balance)->toBe(10); // referral reward only
        expect($newUserBalance->balance)->toBe(38); // 8 invitee bonus + 30 monthly
    });

    it('creates referral record on registration', function () {
        $referrer = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $referrer->id, 'code' => 'XYZ789']);

        $newUser = User::factory()->create();

        $this->service->processReferralOnRegistration($newUser, 'XYZ789');

        expect(Referral::where('referrer_id', $referrer->id)->where('referred_id', $newUser->id)->exists())->toBeTrue();
    });

    it('only grants monthly credits without referral', function () {
        $user = User::factory()->create();

        $this->service->processReferralOnRegistration($user, null);

        $balance = CreditBalance::where('user_id', $user->id)->first();

        expect($balance->balance)->toBe(30); // monthly only
    });

    it('does not allow user to refer themselves', function () {
        $user = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $user->id, 'code' => 'SELF01']);

        $this->service->processReferralOnRegistration($user, 'SELF01');

        $balance = CreditBalance::where('user_id', $user->id)->first();

        expect($balance->balance)->toBe(30); // monthly only, no self-referral bonus
    });

    it('does not allow same user to be referred twice', function () {
        $referrer = User::factory()->create();
        ReferralCode::factory()->create(['user_id' => $referrer->id, 'code' => 'DBL001']);

        $newUser = User::factory()->create();

        $this->service->processReferralOnRegistration($newUser, 'DBL001');

        $balanceBefore = CreditBalance::where('user_id', $referrer->id)->first()->balance;

        $this->service->processReferralOnRegistration($newUser, 'DBL001');

        $balanceAfter = CreditBalance::where('user_id', $referrer->id)->first()->balance;

        expect($balanceBefore)->toBe($balanceAfter);
    });

    it('ignores invalid referral codes', function () {
        $user = User::factory()->create();

        $this->service->processReferralOnRegistration($user, 'INVALID');

        $balance = CreditBalance::where('user_id', $user->id)->first();

        expect($balance->balance)->toBe(30); // monthly only
    });
});

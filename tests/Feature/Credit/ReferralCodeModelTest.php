<?php

use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Database\QueryException;

describe('ReferralCode Model', function () {
    it('generates a unique code on creation', function () {
        $code = ReferralCode::factory()->create();

        expect($code->code)->toBeString()
            ->and(strlen($code->code))->toBe(6);
    });

    it('generates a unique 6-character code', function () {
        $code = ReferralCode::factory()->create();

        expect($code->code)->toBeString()
            ->and(strlen($code->code))->toBe(6)
            ->and(ctype_upper($code->code) || ctype_alnum($code->code))->toBeTrue();
    });

    it('belongs to a user', function () {
        $code = ReferralCode::factory()->create();

        expect($code->user)->toBeInstanceOf(User::class);
    });

    it('one user can only have one referral code', function () {
        $user = User::factory()->create();

        ReferralCode::factory()->create(['user_id' => $user->id]);

        $this->expectException(QueryException::class);

        ReferralCode::factory()->create(['user_id' => $user->id]);
    });

    it('auto-generates code if not provided', function () {
        $user = User::factory()->create();

        $code = ReferralCode::create(['user_id' => $user->id]);

        expect($code->code)->not->toBeEmpty()
            ->and(strlen($code->code))->toBe(6);
    });
});

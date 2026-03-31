<?php

use App\Models\CreditBalance;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

describe('CreditBalance Model', function () {
    it('creates a credit balance for a user', function () {
        $balance = CreditBalance::factory()->create();

        expect($balance)->toBeInstanceOf(CreditBalance::class)
            ->and($balance->user_id)->not->toBeNull()
            ->and($balance->plan)->toBe('free');
    });

    it('defaults balance to 0', function () {
        $user = User::factory()->create();

        $balance = CreditBalance::factory()->create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        expect($balance->balance)->toBe(0);
    });

    it('casts balance as integer', function () {
        $balance = CreditBalance::factory()->create(['balance' => 42]);

        expect($balance->balance)->toBeInt()->toBe(42);
    });

    it('casts monthly_credits_reset_at as datetime', function () {
        $balance = CreditBalance::factory()->create([
            'monthly_credits_reset_at' => '2026-03-30 12:00:00',
        ]);

        expect($balance->monthly_credits_reset_at)->toBeInstanceOf(CarbonInterface::class);
    });

    it('belongs to a user', function () {
        $balance = CreditBalance::factory()->create();

        expect($balance->user)->toBeInstanceOf(User::class);
    });

    it('has many transactions', function () {
        $balance = CreditBalance::factory()->create();

        expect($balance->transactions())->toBeInstanceOf(HasMany::class);
    });

    it('auto-sets monthly_credits_reset_at on creation', function () {
        $user = User::factory()->create();

        $balance = CreditBalance::create([
            'user_id' => $user->id,
            'balance' => 10,
            'plan' => 'free',
        ]);

        expect($balance->monthly_credits_reset_at)->not->toBeNull();
    });
});

<?php

use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\User;

describe('Monthly Reset', function () {
    it('resets balance for users past 30 days', function () {
        $user = User::factory()->create();
        CreditBalance::factory()->create([
            'user_id' => $user->id,
            'balance' => 5,
            'plan' => 'free',
            'monthly_credits_reset_at' => now()->subDays(31),
        ]);

        $this->artisan('credits:reset-monthly')
            ->assertSuccessful();

        $balance = $user->fresh()->creditBalance;

        expect($balance->balance)->toBe(30);
    });

    it('does not reset users not past 30 days', function () {
        $user = User::factory()->create();
        CreditBalance::factory()->create([
            'user_id' => $user->id,
            'balance' => 10,
            'plan' => 'free',
            'monthly_credits_reset_at' => now()->subDays(15),
        ]);

        $this->artisan('credits:reset-monthly')
            ->assertSuccessful();

        $balance = $user->fresh()->creditBalance;

        expect($balance->balance)->toBe(10);
    });

    it('logs a monthly_grant transaction', function () {
        $user = User::factory()->create();
        CreditBalance::factory()->create([
            'user_id' => $user->id,
            'balance' => 0,
            'plan' => 'free',
            'monthly_credits_reset_at' => now()->subDays(35),
        ]);

        $this->artisan('credits:reset-monthly')
            ->assertSuccessful();

        $transaction = CreditTransaction::where('user_id', $user->id)
            ->where('type', 'monthly_grant')
            ->latest()
            ->first();

        expect($transaction)->not->toBeNull()
            ->and($transaction->amount)->toBe(30);
    });

    it('handles empty set gracefully', function () {
        $this->artisan('credits:reset-monthly')
            ->expectsOutput('No users require monthly credit reset.')
            ->assertSuccessful();
    });
});

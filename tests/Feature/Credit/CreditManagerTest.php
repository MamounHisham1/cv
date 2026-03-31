<?php

use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\CvEvaluation;
use App\Models\User;
use App\Services\CreditManager;
use Laravel\Ai\Responses\Data\Usage;

describe('CreditManager', function () {
    beforeEach(function () {
        $this->manager = app(CreditManager::class);
    });

    describe('getBalance', function () {
        it('returns 0 for new user', function () {
            $user = User::factory()->create();

            expect($this->manager->getBalance($user))->toBe(0);
        });

        it('returns existing balance', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 42]);

            expect($this->manager->getBalance($user))->toBe(42);
        });
    });

    describe('hasCredits', function () {
        it('returns false for new user', function () {
            $user = User::factory()->create();

            expect($this->manager->hasCredits($user))->toBeFalse();
        });

        it('returns true when balance > 0', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);

            expect($this->manager->hasCredits($user))->toBeTrue();
        });

        it('returns false when balance is 0', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0]);

            expect($this->manager->hasCredits($user))->toBeFalse();
        });
    });

    describe('add', function () {
        it('increases balance and creates transaction', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);

            $transaction = $this->manager->add($user, 5, 'monthly_grant');

            expect($this->manager->getBalance($user))->toBe(15)
                ->and($transaction->amount)->toBe(5)
                ->and($transaction->type)->toBe('monthly_grant');
        });

        it('creates balance if missing', function () {
            $user = User::factory()->create();

            $this->manager->add($user, 30, 'monthly_grant');

            expect($this->manager->getBalance($user))->toBe(30);
            expect(CreditTransaction::where('user_id', $user->id)->exists())->toBeTrue();
        });

        it('stores metadata', function () {
            $user = User::factory()->create();

            $this->manager->add($user, 10, 'referral_signup', ['referral_code' => 'ABC123']);

            $transaction = CreditTransaction::where('user_id', $user->id)->first();
            expect($transaction->metadata)->toBe(['referral_code' => 'ABC123']);
        });
    });

    describe('deduct', function () {
        it('decreases balance and creates transaction', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);

            $transaction = $this->manager->deduct($user, 3, 'ai_evaluation');

            expect($this->manager->getBalance($user))->toBe(7)
                ->and($transaction->amount)->toBe(-3)
                ->and($transaction->type)->toBe('ai_evaluation');
        });

        it('caps deduction at current balance (no negative)', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 2]);

            $transaction = $this->manager->deduct($user, 5, 'ai_evaluation');

            expect($this->manager->getBalance($user))->toBe(0)
                ->and($transaction->amount)->toBe(-2);
        });

        it('stores reference when provided', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);
            $evaluation = CvEvaluation::factory()->create(['user_id' => $user->id]);

            $transaction = $this->manager->deduct($user, 3, 'ai_evaluation', $evaluation, [
                'prompt_tokens' => 1500,
                'completion_tokens' => 800,
            ]);

            expect($transaction->reference_type)->toBe(CvEvaluation::class)
                ->and($transaction->reference_id)->toBe($evaluation->id)
                ->and($transaction->metadata['prompt_tokens'])->toBe(1500);
        });

        it('creates balance if missing and sets to 0', function () {
            $user = User::factory()->create();

            $this->manager->deduct($user, 5, 'ai_evaluation');

            expect($this->manager->getBalance($user))->toBe(0);
        });
    });

    describe('calculateFromUsage', function () {
        it('calculates credits from token counts', function () {
            $usage = new Usage(promptTokens: 1500, completionTokens: 800);

            $credits = $this->manager->calculateFromUsage($usage);

            expect($credits)->toBe(3); // ceil(2300/1000) * 1 = 3
        });

        it('rounds up to nearest credit', function () {
            $usage = new Usage(promptTokens: 100, completionTokens: 50);

            $credits = $this->manager->calculateFromUsage($usage);

            expect($credits)->toBe(1); // ceil(150/1000) * 1 = 1
        });

        it('falls back to minimum charge when tokens are 0', function () {
            $usage = new Usage(promptTokens: 0, completionTokens: 0);

            $credits = $this->manager->calculateFromUsage($usage, 'ai_evaluation');

            expect($credits)->toBe(1); // minimum_charge.ai_evaluation = 1
        });
    });

    describe('canPerformOperation', function () {
        it('returns true when user has credits for evaluation', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);

            expect($this->manager->canPerformOperation($user, 'ai_evaluation'))->toBeTrue();
        });

        it('returns false when user has no credits for evaluation', function () {
            $user = User::factory()->create();

            expect($this->manager->canPerformOperation($user, 'ai_evaluation'))->toBeFalse();
        });

        it('returns true for builder message within free cap', function () {
            $user = User::factory()->create();

            expect($this->manager->canPerformOperation($user, 'ai_builder_message'))->toBeTrue();
        });
    });

    describe('grantMonthlyCredits', function () {
        it('resets balance to plan monthly amount', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0, 'plan' => 'free']);

            $this->manager->grantMonthlyCredits($user);

            expect($this->manager->getBalance($user))->toBe(30);
        });

        it('logs a monthly_grant transaction', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 5, 'plan' => 'free']);

            $this->manager->grantMonthlyCredits($user);

            $transaction = CreditTransaction::where('user_id', $user->id)
                ->where('type', 'monthly_grant')
                ->latest()
                ->first();

            expect($transaction)->not->toBeNull()
                ->and($transaction->amount)->toBe(30);
        });

        it('does not stack credits (resets, not adds)', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 25, 'plan' => 'free']);

            $this->manager->grantMonthlyCredits($user);

            expect($this->manager->getBalance($user))->toBe(30);
        });
    });
});

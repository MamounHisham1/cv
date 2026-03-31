<?php

use App\Models\CreditTransaction;
use App\Models\CvEvaluation;
use App\Models\User;

describe('CreditTransaction Model', function () {
    it('creates a transaction with all fields', function () {
        $transaction = CreditTransaction::factory()->create([
            'amount' => 10,
            'type' => 'monthly_grant',
            'metadata' => ['source' => 'system'],
        ]);

        expect($transaction->amount)->toBe(10)
            ->and($transaction->type)->toBe('monthly_grant')
            ->and($transaction->metadata)->toBe(['source' => 'system']);
    });

    it('casts amount as integer', function () {
        $transaction = CreditTransaction::factory()->create(['amount' => -5]);

        expect($transaction->amount)->toBeInt()->toBe(-5);
    });

    it('casts metadata as array', function () {
        $transaction = CreditTransaction::factory()->create([
            'metadata' => ['prompt_tokens' => 1500, 'completion_tokens' => 800],
        ]);

        expect($transaction->metadata)->toBeArray()
            ->and($transaction->metadata['prompt_tokens'])->toBe(1500);
    });

    it('allows null metadata', function () {
        $transaction = CreditTransaction::factory()->create(['metadata' => null]);

        expect($transaction->metadata)->toBeNull();
    });

    it('belongs to a user', function () {
        $transaction = CreditTransaction::factory()->create();

        expect($transaction->user)->toBeInstanceOf(User::class);
    });

    it('supports polymorphic reference', function () {
        $evaluation = CvEvaluation::factory()->create();
        $transaction = CreditTransaction::factory()->create([
            'reference_type' => CvEvaluation::class,
            'reference_id' => $evaluation->id,
        ]);

        expect($transaction->reference_type)->toBe(CvEvaluation::class)
            ->and($transaction->reference_id)->toBe($evaluation->id);
    });
});

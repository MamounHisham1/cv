<?php

use App\Exceptions\InsufficientCreditsException;
use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Services\CreditManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    CreditBalance::factory()->create(['user_id' => $this->user->id, 'balance' => 50, 'plan' => 'pro']);
    $this->manager = app(CreditManager::class);
});

it('reserves credits by immediately deducting the balance', function () {
    $tx = $this->manager->reserve($this->user, 20, 'ai_interview');

    expect($tx->amount)->toBe(-20)
        ->and($tx->type)->toBe('reservation')
        ->and($this->user->fresh()->creditBalance->balance)->toBe(30)
        ->and($tx->metadata['status'])->toBe('reserved')
        ->and($tx->metadata['operation_type'])->toBe('ai_interview');
});

it('refuses to reserve more than the current balance', function () {
    $this->manager->reserve($this->user, 100, 'ai_interview');
})->throws(InsufficientCreditsException::class);

it('refuses to reserve when the balance is zero', function () {
    $this->user->creditBalance()->update(['balance' => 0]);

    $this->manager->reserve($this->user, 1, 'ai_interview');
})->throws(InsufficientCreditsException::class);

it('settles a reservation, refunding any unused excess', function () {
    $tx = $this->manager->reserve($this->user, 20, 'ai_interview');

    // Actual cost was only 12 — 8 should be refunded.
    $this->manager->settle($this->user, $tx->id, 12);

    $balance = $this->user->fresh()->creditBalance->balance;
    // Started at 50, reserved 20 (down to 30), refunded 8 → 38.
    expect($balance)->toBe(38);

    $reservation = CreditTransaction::find($tx->id);
    expect($reservation->metadata['status'])->toBe('settled')
        ->and($reservation->metadata['actual_charge'])->toBe(12);

    // A refund transaction was created.
    $refund = CreditTransaction::where('user_id', $this->user->id)
        ->where('type', 'refund')
        ->first();
    expect($refund)->not->toBeNull()
        ->and($refund->amount)->toBe(8);
});

it('does not refund when actual cost equals the reserved amount', function () {
    $tx = $this->manager->reserve($this->user, 20, 'ai_interview');

    $this->manager->settle($this->user, $tx->id, 20);

    expect($this->user->fresh()->creditBalance->balance)->toBe(30)
        ->and(CreditTransaction::where('user_id', $this->user->id)->where('type', 'refund')->exists())->toBeFalse();
});

it('cancels a reservation, refunding the full reserved amount', function () {
    $tx = $this->manager->reserve($this->user, 20, 'ai_interview');

    $this->manager->cancelReservation($this->user, $tx->id);

    // Full refund — back to 50.
    expect($this->user->fresh()->creditBalance->balance)->toBe(50);

    $reservation = CreditTransaction::find($tx->id);
    expect($reservation->metadata['status'])->toBe('cancelled');

    $refund = CreditTransaction::where('user_id', $this->user->id)
        ->where('type', 'refund')
        ->first();
    expect($refund)->not->toBeNull()
        ->and($refund->amount)->toBe(20);
});

it('does not double-refund if cancelReservation is called on an already-settled reservation', function () {
    $tx = $this->manager->reserve($this->user, 20, 'ai_interview');
    $this->manager->settle($this->user, $tx->id, 12); // refunded 8 → balance 38

    $this->manager->cancelReservation($this->user, $tx->id); // should be a no-op

    expect($this->user->fresh()->creditBalance->balance)->toBe(38);
});

it('ignores settle/cancel calls for a non-existent reservation id', function () {
    $this->manager->settle($this->user, 999999, 5);
    $this->manager->cancelReservation($this->user, 999999);

    // Balance unchanged.
    expect($this->user->fresh()->creditBalance->balance)->toBe(50);
});

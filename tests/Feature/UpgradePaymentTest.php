<?php

use App\Livewire\Upgrade;
use App\Models\CreditBalance;
use App\Models\User;
use App\Models\VfcashPayment;
use App\Services\VfcashService;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

beforeEach(function () {
    config(['vfcash.api_key' => 'test_key', 'vfcash.webhook_secret' => 'test_secret']);
});

function userWithOtp(): User
{
    $user = User::factory()->create(['otp_verified_at' => now()]);
    CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0, 'plan' => 'free']);

    return $user;
}

it('opens the phone modal when a plan is selected', function () {
    $user = userWithOtp();

    Livewire::actingAs($user)
        ->test(Upgrade::class)
        ->call('selectPlan', 'pro')
        ->assertSet('selectedPlan', 'pro')
        ->assertSet('showPhoneModal', true);
});

it('validates the phone number with the Egyptian format', function () {
    $user = userWithOtp();

    Livewire::actingAs($user)
        ->test(Upgrade::class)
        ->call('selectPlan', 'pro')
        ->set('phone', 'not-a-phone')
        ->call('confirmPurchase')
        ->assertHasErrors(['phone']);
});

it('creates a pending VfcashPayment on successful purchase', function () {
    Http::fake(fn () => Http::response([
        'id' => 99,
        'payment_number' => 'PAY-TEST-999',
        'source' => 'Test',
        'status' => 'pending',
    ], 201));

    $user = userWithOtp();

    Livewire::actingAs($user)
        ->test(Upgrade::class)
        ->call('selectPlan', 'pro')
        ->set('phone', '01012345678')
        ->call('confirmPurchase')
        ->assertHasNoErrors()
        ->assertNotSet('pendingPaymentId', null)
        ->assertSet('pendingStatus', 'pending');

    $payment = VfcashPayment::where('user_id', $user->id)->first();
    expect($payment)->not->toBeNull()
        ->and($payment->status)->toBe('pending')
        ->and($payment->type)->toBe('plan_upgrade')
        ->and($payment->item_key)->toBe('pro')
        ->and($payment->customer_phone)->toBe('01012345678');
});

it('creates a pending payment even when the upstream VFCash API fails (known gap)', function () {
    // VfcashService::createPayment does not currently throw on HTTP failure —
    // it persists a pending payment without a vfcash_payment_id and returns
    // success. This test documents that current behavior so a future fix to
    // make the service fail loudly will trip this test rather than silently
    // change user-facing flow.
    Http::fake(fn () => Http::response(['error' => 'upstream'], 500));

    $user = userWithOtp();

    Livewire::actingAs($user)
        ->test(Upgrade::class)
        ->call('selectPlan', 'pro')
        ->set('phone', '01012345678')
        ->call('confirmPurchase')
        ->assertHasNoErrors();

    // A dangling pending payment exists with no external id.
    $payment = VfcashPayment::where('user_id', $user->id)->first();
    expect($payment)->not->toBeNull()
        ->and($payment->status)->toBe('pending')
        ->and($payment->vfcash_payment_id)->toBeNull();
});

it('confirms and clears state when the payment transitions to confirmed', function () {
    Http::fake(fn () => Http::response([
        'id' => 100,
        'payment_number' => 'PAY-TEST-100',
        'source' => 'Test',
        'status' => 'pending',
    ], 201));

    $user = userWithOtp();

    $component = Livewire::actingAs($user)
        ->test(Upgrade::class)
        ->call('selectPlan', 'pro')
        ->set('phone', '01012345678')
        ->call('confirmPurchase');

    $payment = VfcashPayment::where('user_id', $user->id)->first();
    $payment->update(['status' => 'confirmed', 'confirmed_at' => now()]);

    $component->call('checkPaymentStatus')
        ->assertDispatched('notify');

    // After confirmation the pending state is cleared so polling stops.
    expect($component->get('pendingPaymentId'))->toBeNull();
});

it('only shows payment status for the authenticated user (IDOR guard)', function () {
    $attacker = userWithOtp();
    $victim = userWithOtp();

    $victimPayment = VfcashPayment::factory()->create([
        'user_id' => $victim->id,
        'status' => 'confirmed',
    ]);

    // Attacker tries to poll the victim's payment id via public Livewire state.
    $component = Livewire::actingAs($attacker)
        ->test(Upgrade::class)
        ->set('pendingPaymentId', $victimPayment->id)
        ->call('checkPaymentStatus');

    // The victim's confirmed status must NOT leak to the attacker.
    expect($component->get('pendingStatus'))->not->toBe('confirmed');
    expect(VfcashPayment::where('user_id', $attacker->id)->exists())->toBeFalse();
});

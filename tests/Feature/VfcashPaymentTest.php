<?php

use App\Models\CreditBalance;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Models\VfcashPayment;
use App\Services\VfcashService;
use Illuminate\Support\Facades\Http;

describe('VfcashService', function () {
    beforeEach(function () {
        $this->service = app(VfcashService::class);
        config(['vfcash.api_key' => 'test_key', 'vfcash.webhook_secret' => 'test_secret']);
    });

    describe('createPayment', function () {
        it('creates a pending payment for a credit topup', function () {
            Http::fake(fn () => Http::response(['id' => 42, 'payment_number' => 'PAY-TEST-001', 'source' => 'Test', 'status' => 'pending'], 201));
            $user = User::factory()->create();

            ['payment' => $payment, 'vfcash_response' => $response] = $this->service->createPayment($user, 'credit_topup', 'topup_50', '01012345678');

            expect($payment)->toBeInstanceOf(VfcashPayment::class)
                ->and($payment->status)->toBe('pending')
                ->and($payment->type)->toBe('credit_topup')
                ->and($payment->item_key)->toBe('topup_50')
                ->and($payment->credits_granted)->toBe(50)
                ->and((float) $payment->amount_egp)->toBe(250.0)
                ->and($payment->customer_phone)->toBe('01012345678')
                ->and($payment->vfcash_payment_id)->toBe(42);
        });

        it('creates a pending payment for a plan upgrade', function () {
            Http::fake(fn () => Http::response(['id' => 43, 'payment_number' => 'PAY-TEST-002', 'source' => 'Test', 'status' => 'pending'], 201));
            $user = User::factory()->create();

            ['payment' => $payment] = $this->service->createPayment($user, 'plan_upgrade', 'pro', '01098765432');

            expect($payment->type)->toBe('plan_upgrade')
                ->and($payment->item_key)->toBe('pro')
                ->and($payment->credits_granted)->toBe(100)
                ->and((float) $payment->amount_egp)->toBe(300.0);
        });

        it('generates a unique payment_number', function () {
            Http::fake(fn () => Http::response(['id' => 44, 'payment_number' => 'PAY-TEST-003', 'source' => 'Test', 'status' => 'pending'], 201));
            $user = User::factory()->create();

            ['payment' => $payment] = $this->service->createPayment($user, 'credit_topup', 'topup_120', '01011111111');

            expect($payment->payment_number)->toStartWith('VFC-')
                ->and(strlen($payment->payment_number))->toBeGreaterThan(10);
        });
    });

    describe('handleConfirmed', function () {
        it('grants credits and upgrades plan on confirmed plan upgrade', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10, 'plan' => 'free']);
            $payment = VfcashPayment::factory()->planUpgrade('pro')->create(['user_id' => $user->id]);

            $this->service->handleConfirmed([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            $payment->refresh();
            expect($payment->status)->toBe('confirmed')
                ->and($payment->confirmed_at)->not->toBeNull()
                ->and($user->fresh()->creditBalance->plan)->toBe('pro')
                ->and($user->fresh()->creditBalance->balance)->toBe(110);
        });

        it('grants credits on confirmed credit topup', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 5, 'plan' => 'free']);
            $payment = VfcashPayment::factory()->create([
                'user_id' => $user->id,
                'type' => 'credit_topup',
                'item_key' => 'topup_120',
                'credits_granted' => 120,
                'amount_egp' => 120,
                'status' => 'pending',
            ]);

            $this->service->handleConfirmed([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            $payment->refresh();
            expect($payment->status)->toBe('confirmed')
                ->and($user->fresh()->creditBalance->balance)->toBe(125)
                ->and($user->fresh()->creditBalance->plan)->toBe('free');
        });

        it('creates a vfcash_topup credit transaction', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0]);
            $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'credits_granted' => 50, 'status' => 'pending']);

            $this->service->handleConfirmed([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            $transaction = CreditTransaction::where('user_id', $user->id)->where('type', 'vfcash_topup')->first();
            expect($transaction)->not->toBeNull()
                ->and($transaction->amount)->toBe(50);
        });

        it('creates a vfcash_plan_purchase credit transaction for plan upgrades', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0, 'plan' => 'free']);
            $payment = VfcashPayment::factory()->planUpgrade('enterprise')->create(['user_id' => $user->id, 'status' => 'pending']);

            $this->service->handleConfirmed([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            $transaction = CreditTransaction::where('user_id', $user->id)->where('type', 'vfcash_plan_purchase')->first();
            expect($transaction)->not->toBeNull()
                ->and($transaction->amount)->toBe(500);
        });

        it('does nothing if payment is already confirmed', function () {
            $user = User::factory()->create();
            CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10]);
            $payment = VfcashPayment::factory()->confirmed()->create(['user_id' => $user->id]);

            $this->service->handleConfirmed([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            expect($user->fresh()->creditBalance->balance)->toBe(10);
        });

        it('returns null when payment id is missing from metadata', function () {
            $result = $this->service->handleConfirmed(['metadata' => []]);

            expect($result)->toBeNull();
        });
    });

    describe('handleExpired', function () {
        it('marks pending payment as expired', function () {
            $user = User::factory()->create();
            $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

            $this->service->handleExpired([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            expect($payment->fresh()->status)->toBe('expired');
        });

        it('does not change a confirmed payment', function () {
            $user = User::factory()->create();
            $payment = VfcashPayment::factory()->confirmed()->create(['user_id' => $user->id]);

            $this->service->handleExpired([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            expect($payment->fresh()->status)->toBe('confirmed');
        });
    });

    describe('handleCancelled', function () {
        it('marks pending payment as cancelled', function () {
            $user = User::factory()->create();
            $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

            $this->service->handleCancelled([
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ]);

            expect($payment->fresh()->status)->toBe('cancelled');
        });
    });

    describe('verifySignature', function () {
        it('returns true for a valid signature', function () {
            $payload = '{"event":"payment.confirmed"}';
            $signature = hash_hmac('sha256', $payload, 'test_secret');

            expect($this->service->verifySignature($payload, $signature))->toBeTrue();
        });

        it('returns false for an invalid signature', function () {
            expect($this->service->verifySignature('{"event":"payment.confirmed"}', 'bad_signature'))->toBeFalse();
        });

        it('returns false when webhook secret is not configured', function () {
            config(['vfcash.webhook_secret' => null]);

            expect($this->service->verifySignature('payload', 'sig'))->toBeFalse();
        });
    });
});

describe('VfcashWebhookController', function () {
    beforeEach(function () {
        config(['vfcash.api_key' => 'test_key', 'vfcash.webhook_secret' => 'test_secret']);
    });

    it('accepts a valid payment.confirmed webhook', function () {
        $user = User::factory()->create();
        CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 0, 'plan' => 'free']);
        $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'credits_granted' => 50, 'amount_egp' => 250, 'status' => 'pending']);

        $payload = [
            'event' => 'payment.confirmed',
            'data' => [
                'metadata' => ['vfcash_payment_id' => $payment->id],
                'payment_number' => $payment->payment_number,
                'amount' => 60.00,
                'status' => 'confirmed',
            ],
        ];
        $body = json_encode($payload);
        $signature = hash_hmac('sha256', $body, 'test_secret');

        $this->postJson(route('webhooks.vfcash'), $payload, [
            'X-VFCash-Signature' => $signature,
            'X-VFCash-Event' => 'payment.confirmed',
        ])->assertOk();

        expect($payment->fresh()->status)->toBe('confirmed')
            ->and($user->fresh()->creditBalance->balance)->toBe(50);
    });

    it('rejects a webhook with an invalid signature', function () {
        $payload = ['event' => 'payment.confirmed', 'data' => []];

        $this->postJson(route('webhooks.vfcash'), $payload, [
            'X-VFCash-Signature' => 'invalid',
        ])->assertStatus(401);
    });

    it('rejects a webhook with a missing signature', function () {
        $this->postJson(route('webhooks.vfcash'), [
            'event' => 'payment.confirmed',
            'data' => [],
        ])->assertStatus(401);
    });

    it('handles payment.expired webhook', function () {
        $user = User::factory()->create();
        $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $payload = [
            'event' => 'payment.expired',
            'data' => [
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ],
        ];
        $body = json_encode($payload);
        $signature = hash_hmac('sha256', $body, 'test_secret');

        $this->postJson(route('webhooks.vfcash'), $payload, [
            'X-VFCash-Signature' => $signature,
        ])->assertOk();

        expect($payment->fresh()->status)->toBe('expired');
    });

    it('handles payment.cancelled webhook', function () {
        $user = User::factory()->create();
        $payment = VfcashPayment::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $payload = [
            'event' => 'payment.cancelled',
            'data' => [
                'metadata' => ['vfcash_payment_id' => $payment->id],
            ],
        ];
        $body = json_encode($payload);
        $signature = hash_hmac('sha256', $body, 'test_secret');

        $this->postJson(route('webhooks.vfcash'), $payload, [
            'X-VFCash-Signature' => $signature,
        ])->assertOk();

        expect($payment->fresh()->status)->toBe('cancelled');
    });
});

describe('Upgrade page', function () {
    it('requires authentication', function () {
        $this->get(route('upgrade'))
            ->assertRedirect(route('login'));
    });

    it('renders for authenticated users', function () {
        $user = User::factory()->create(['otp_verified_at' => now()]);
        CreditBalance::factory()->create(['user_id' => $user->id, 'balance' => 10, 'plan' => 'free']);

        $this->actingAs($user)
            ->get(route('upgrade'))
            ->assertOk()
            ->assertSee('Upgrade Your Plan')
            ->assertSee('Pro')
            ->assertSee('Enterprise')
            ->assertSee('Starter')
            ->assertSee('Standard')
            ->assertSee('Pro Pack');
    });

    it('shows current plan badge when on pro', function () {
        $user = User::factory()->create(['otp_verified_at' => now()]);
        CreditBalance::factory()->pro()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('upgrade'))
            ->assertSee('Current Plan')
            ->assertSee('Current plan: Pro');
    });
});

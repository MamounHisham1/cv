<?php

namespace App\Services;

use App\Models\User;
use App\Models\VfcashPayment;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VfcashService
{
    private function createHttp(): PendingRequest
    {
        return Http::baseUrl(config('vfcash.api_url'))
            ->withToken(config('vfcash.api_key'))
            ->acceptJson()
            ->contentType('application/json');
    }

    /**
     * Create a VFCash payment for a plan upgrade or credit topup.
     *
     * @return array{payment: VfcashPayment, vfcash_response: array}
     */
    public function createPayment(User $user, string $type, string $itemKey, string $customerPhone): array
    {
        $item = $this->resolveItem($type, $itemKey);

        $payment = VfcashPayment::create([
            'user_id' => $user->id,
            'payment_number' => 'VFC-'.now()->format('Ymd').'-'.strtoupper(Str::random(8)),
            'type' => $type,
            'item_key' => $itemKey,
            'credits_granted' => $item['credits'],
            'amount_egp' => $item['price_egp'],
            'customer_phone' => $customerPhone,
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->createHttp()->post('/payments', [
            'amount' => (float) $item['price_egp'],
            'customer_phone' => $customerPhone,
            'customer_name' => $user->name,
            'description' => $item['name'].($type === 'plan_upgrade' ? ' Plan Upgrade' : ' Credit Pack'),
            'metadata' => [
                'vfcash_payment_id' => $payment->id,
                'user_id' => (string) $user->id,
                'type' => $type,
                'item_key' => $itemKey,
            ],
            'expires_in' => 86400,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $payment->update([
                'vfcash_payment_id' => $data['id'],
                'source' => $data['source'] ?? null,
            ]);
        }

        return [
            'payment' => $payment,
            'vfcash_response' => $response->json(),
        ];
    }

    /**
     * Handle a confirmed webhook event.
     * Grants credits and/or upgrades plan.
     */
    public function handleConfirmed(array $data): ?VfcashPayment
    {
        $payment = $this->resolvePendingPayment($data);

        if (! $payment || ! $payment->isPending()) {
            return $payment;
        }

        $payment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $user = $payment->user;
        $creditManager = app(CreditManager::class);

        if ($payment->isPlanUpgrade()) {
            $plan = $payment->item_key;
            $planCredits = config("vfcash.plans.{$plan}.credits", 0);

            $user->creditBalance()->updateOrCreate(
                ['user_id' => $user->id],
                ['plan' => $plan]
            );

            $creditManager->add($user, $planCredits, 'vfcash_plan_purchase', [
                'payment_id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'plan' => $plan,
            ]);
        } elseif ($payment->isCreditTopup()) {
            $creditManager->add($user, $payment->credits_granted, 'vfcash_topup', [
                'payment_id' => $payment->id,
                'payment_number' => $payment->payment_number,
                'item_key' => $payment->item_key,
            ]);
        }

        return $payment;
    }

    /**
     * Handle an expired webhook event.
     */
    public function handleExpired(array $data): ?VfcashPayment
    {
        return $this->markPendingPayment($data, 'expired');
    }

    /**
     * Handle a cancelled webhook event.
     */
    public function handleCancelled(array $data): ?VfcashPayment
    {
        return $this->markPendingPayment($data, 'cancelled');
    }

    /**
     * Resolve the pending payment referenced by a webhook payload and,
     * if it is still pending, mark it with the given status.
     *
     * @return ?VfcashPayment The payment if a valid id was present, null otherwise.
     */
    private function markPendingPayment(array $data, string $status): ?VfcashPayment
    {
        $payment = $this->resolvePendingPayment($data);

        if ($payment && $payment->isPending()) {
            $payment->update(['status' => $status]);
        }

        return $payment;
    }

    /**
     * Resolve the pending payment referenced by a webhook payload.
     *
     * @return ?VfcashPayment The payment if a valid id was present, null otherwise.
     *                        Does NOT check isPending() — callers decide that.
     */
    private function resolvePendingPayment(array $data): ?VfcashPayment
    {
        $paymentId = $data['metadata']['vfcash_payment_id'] ?? null;

        if (! $paymentId) {
            return null;
        }

        return VfcashPayment::find($paymentId);
    }

    /**
     * Verify webhook signature.
     */
    public function verifySignature(string $payload, string $signature): bool
    {
        $secret = config('vfcash.webhook_secret');

        if (! $secret) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    private function resolveItem(string $type, string $itemKey): array
    {
        if ($type === 'plan_upgrade') {
            return config("vfcash.plans.{$itemKey}");
        }

        return config("vfcash.topups.{$itemKey}");
    }
}

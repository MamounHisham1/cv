<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VfcashPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VfcashPayment>
 */
class VfcashPaymentFactory extends Factory
{
    protected $model = VfcashPayment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_number' => 'VFC-'.now()->format('Ymd').'-'.strtoupper(fake()->bothify('********')),
            'type' => 'credit_topup',
            'item_key' => 'topup_50',
            'credits_granted' => 50,
            'amount_egp' => 60.00,
            'customer_phone' => '01'.fake()->numerify('#########'),
            'status' => 'pending',
            'vfcash_payment_id' => null,
            'source' => null,
            'metadata' => null,
            'expires_at' => now()->addDay(),
            'confirmed_at' => null,
        ];
    }

    public function planUpgrade(string $plan = 'pro'): static
    {
        $plans = config('vfcash.plans');

        return $this->state(fn () => [
            'type' => 'plan_upgrade',
            'item_key' => $plan,
            'credits_granted' => $plans[$plan]['credits'],
            'amount_egp' => $plans[$plan]['price_egp'],
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'vfcash_payment_id' => fake()->numerify('#####'),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
        ]);
    }
}

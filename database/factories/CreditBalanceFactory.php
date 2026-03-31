<?php

namespace Database\Factories;

use App\Models\CreditBalance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditBalance>
 */
class CreditBalanceFactory extends Factory
{
    protected $model = CreditBalance::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'balance' => fake()->numberBetween(0, 100),
            'plan' => 'free',
            'monthly_credits_reset_at' => now(),
        ];
    }

    public function pro(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => 'pro',
            'balance' => fake()->numberBetween(0, 300),
        ]);
    }

    public function enterprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => 'enterprise',
            'balance' => fake()->numberBetween(0, 1000),
        ]);
    }
}

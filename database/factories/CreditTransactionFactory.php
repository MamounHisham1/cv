<?php

namespace Database\Factories;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditTransaction>
 */
class CreditTransactionFactory extends Factory
{
    protected $model = CreditTransaction::class;

    public function definition(): array
    {
        $types = [
            'monthly_grant',
            'referral_signup',
            'invitee_signup_bonus',
            'ai_evaluation',
            'ai_parse',
            'ai_builder_message',
            'admin_adjustment',
        ];

        return [
            'user_id' => User::factory(),
            'amount' => fake()->randomElement([-1, 1]) * fake()->numberBetween(1, 10),
            'type' => fake()->randomElement($types),
            'reference_type' => null,
            'reference_id' => null,
            'metadata' => null,
        ];
    }

    public function earned(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->numberBetween(1, 15),
            'type' => fake()->randomElement(['monthly_grant', 'referral_signup', 'invitee_signup_bonus']),
        ]);
    }

    public function spent(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => -fake()->numberBetween(1, 10),
            'type' => fake()->randomElement(['ai_evaluation', 'ai_parse', 'ai_builder_message']),
            'metadata' => [
                'prompt_tokens' => fake()->numberBetween(500, 3000),
                'completion_tokens' => fake()->numberBetween(200, 1500),
            ],
        ]);
    }
}

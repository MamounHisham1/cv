<?php

namespace Database\Factories;

use App\Models\SentMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SentMail>
 */
class SentMailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_email' => $this->faker->safeEmail(),
            'subject' => $this->faker->sentence(),
            'body' => '<p>'.$this->faker->paragraph().'</p>',
            'template' => $this->faker->randomElement(array_keys(SentMail::TEMPLATE_OPTIONS)),
            'status' => SentMail::STATUS_SENT,
        ];
    }
}

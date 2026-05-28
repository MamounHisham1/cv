<?php

namespace Database\Factories;

use App\Models\InterviewMessage;
use App\Models\InterviewSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InterviewMessage>
 */
class InterviewMessageFactory extends Factory
{
    protected $model = InterviewMessage::class;

    public function definition(): array
    {
        return [
            'interview_session_id' => InterviewSession::factory(),
            'role' => $this->faker->randomElement(['interviewer', 'candidate']),
            'content' => $this->faker->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 20),
        ];
    }
}

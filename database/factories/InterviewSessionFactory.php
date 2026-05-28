<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\InterviewSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<InterviewSession>
 */
class InterviewSessionFactory extends Factory
{
    protected $model = InterviewSession::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cv_id' => Cv::factory(),
            'job_description' => $this->faker->paragraph(),
            'status' => 'setup',
            'interview_type' => $this->faker->randomElement(['behavioral', 'technical', 'mixed']),
            'total_questions' => null,
            'duration_seconds' => null,
            'conversation_id' => Str::uuid()->toString(),
            'started_at' => null,
            'completed_at' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'total_questions' => 8,
            'duration_seconds' => 600,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\CoverLetter;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoverLetter>
 */
class CoverLetterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cv_id' => Cv::factory(),
            'title' => 'Cover Letter — '.$this->faker->jobTitle(),
            'body' => "Dear Hiring Manager,\n\n".$this->faker->paragraph(4),
            'template_id' => $this->faker->randomElement(['classic', 'modern', 'concise']),
            'metadata' => null,
        ];
    }

    /**
     * A standalone letter with no source CV.
     */
    public function standalone(): static
    {
        return $this->state(fn (array $attributes) => [
            'cv_id' => null,
        ]);
    }
}

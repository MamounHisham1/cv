<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvJobMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvJobMatch>
 */
class CvJobMatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cv_id' => Cv::factory(),
            'status' => CvJobMatch::STATUS_COMPLETED,
            'job_description' => $this->faker->paragraph(6),
            'job_title' => $this->faker->jobTitle(),
            'compatibility_score' => $this->faker->numberBetween(20, 95),
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'F']),
            'summary' => $this->faker->paragraph(2),
            'matched_keywords' => $this->faker->words(4),
            'missing_keywords' => $this->faker->words(3),
            'gap_analysis' => array_map(fn ($g) => $this->faker->sentence(), range(1, 3)),
            'suggestions' => array_map(fn ($s) => $this->faker->sentence(), range(1, 3)),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => CvJobMatch::STATUS_PENDING,
            'compatibility_score' => null,
            'grade' => null,
            'summary' => null,
            'matched_keywords' => null,
            'missing_keywords' => null,
            'gap_analysis' => null,
            'suggestions' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => CvJobMatch::STATUS_FAILED,
            'error_message' => 'test failure',
            'compatibility_score' => null,
            'grade' => null,
            'summary' => null,
            'matched_keywords' => null,
            'missing_keywords' => null,
            'gap_analysis' => null,
            'suggestions' => null,
        ]);
    }
}

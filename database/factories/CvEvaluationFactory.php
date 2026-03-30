<?php

namespace Database\Factories;

use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvEvaluation>
 */
class CvEvaluationFactory extends Factory
{
    protected $model = CvEvaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $criteria = [
            'contact_information' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'professional_summary' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'work_experience' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'skills_section' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'education' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'ats_compatibility' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'formatting_readability' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'achievements_impact' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'keyword_optimisation' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
            'overall_completeness' => ['score' => fake()->numberBetween(5, 10), 'reason' => fake()->sentence()],
        ];

        $overallScore = (int) round(array_sum(array_column($criteria, 'score')) / count($criteria) * 10);

        return [
            'user_id' => User::factory(),
            'filename' => fake()->optional()->word().'.pdf',
            'overall_score' => $overallScore,
            'grade' => fake()->randomElement(['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F']),
            'summary' => fake()->paragraph(),
            'criteria' => $criteria,
            'top_strengths' => fake()->sentences(3),
            'critical_improvements' => fake()->sentences(3),
            'cv_text' => fake()->paragraphs(5, true),
        ];
    }
}

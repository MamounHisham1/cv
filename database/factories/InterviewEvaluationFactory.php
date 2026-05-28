<?php

namespace Database\Factories;

use App\Models\InterviewEvaluation;
use App\Models\InterviewSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InterviewEvaluation>
 */
class InterviewEvaluationFactory extends Factory
{
    protected $model = InterviewEvaluation::class;

    public function definition(): array
    {
        return [
            'interview_session_id' => InterviewSession::factory()->completed(),
            'overall_score' => $this->faker->numberBetween(60, 100),
            'grade' => $this->faker->randomElement(['A+', 'A', 'B+', 'B', 'C+', 'C']),
            'summary' => $this->faker->paragraph(),
            'criteria' => [
                'communication_clarity' => ['score' => 8, 'reason' => 'Clear answers.'],
                'technical_depth' => ['score' => 7, 'reason' => 'Good basics, missed some details.'],
                'confidence' => ['score' => 9, 'reason' => 'Very confident.'],
            ],
            'strengths' => [
                $this->faker->sentence(),
                $this->faker->sentence(),
            ],
            'improvements' => [
                $this->faker->sentence(),
                $this->faker->sentence(),
            ],
        ];
    }
}

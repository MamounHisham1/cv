<?php

namespace Database\Factories;

use App\Models\ResumeSample;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeSample>
 */
class ResumeSampleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->uuid(),
            'source' => $this->faker->randomElement(['rjd', 'kaggle', 'manual']),
            'role' => $this->faker->jobTitle(),
            'content' => $this->faker->paragraphs(4, true),
            'decision' => $this->faker->randomElement(['accepted', 'rejected', 'screening']),
            'reason' => $this->faker->sentence(),
            'job_description' => $this->faker->optional()->paragraphs(2, true),
            'structured_data' => null,
        ];
    }
}

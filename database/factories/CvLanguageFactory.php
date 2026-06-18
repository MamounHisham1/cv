<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvLanguage>
 */
class CvLanguageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'language' => $this->faker->languageCode(),
            'proficiency' => $this->faker->randomElement([
                'beginner', 'elementary', 'intermediate',
                'upper_intermediate', 'advanced', 'fluent', 'native',
            ]),
            'sort_order' => 0,
        ];
    }
}

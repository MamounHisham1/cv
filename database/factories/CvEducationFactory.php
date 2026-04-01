<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvEducation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvEducation>
 */
class CvEducationFactory extends Factory
{
    protected $model = CvEducation::class;

    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'institution' => $this->faker->company().' University',
            'degree' => $this->faker->randomElement([
                'Bachelor of Science',
                'Master of Science',
                'Bachelor of Engineering',
                'Master of Engineering',
            ]),
            'field_of_study' => $this->faker->randomElement([
                'Computer Science',
                'Information Technology',
                'Software Engineering',
                'Cloud Computing',
            ]),
            'location' => $this->faker->city().', '.$this->faker->country(),
            'start_date' => $this->faker->dateTimeBetween('-10 years', '-6 years'),
            'end_date' => $this->faker->dateTimeBetween('-5 years', '-2 years'),
            'is_current' => false,
            'description' => $this->faker->optional()->paragraph(),
            'sort_order' => 0,
        ];
    }
}

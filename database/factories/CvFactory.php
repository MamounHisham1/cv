<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cv>
 */
class CvFactory extends Factory
{
    protected $model = Cv::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'template_id' => $this->faker->randomElement([
                'professional-classic',
                'technical-ats',
                'modern-minimal',
                'creative',
                'executive',
            ]),
            'status' => $this->faker->randomElement(['draft', 'completed']),
            'personal_info' => [
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
                'location' => $this->faker->city().', '.$this->faker->country(),
                'linkedin' => 'https://linkedin.com/in/'.$this->faker->userName(),
                'website' => $this->faker->url(),
                'summary' => $this->faker->paragraph(),
            ],
            'summary' => $this->faker->paragraph(3),
            'sort_order' => 0,
        ];
    }

    public function forAwsProfessional(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'AWS Cloud Engineer CV',
            'summary' => 'Results-driven AWS Cloud Engineer with 5+ years of experience designing, implementing, and managing scalable cloud infrastructure solutions. Proven expertise in AWS services, infrastructure as code, and DevOps practices.',
        ]);
    }
}

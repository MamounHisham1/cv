<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvSkill>
 */
class CvSkillFactory extends Factory
{
    protected $model = CvSkill::class;

    public function definition(): array
    {
        $skills = [
            'Python', 'Java', 'JavaScript', 'TypeScript', 'Go', 'Ruby',
            'Docker', 'Kubernetes', 'Terraform', 'Ansible', 'Jenkins',
            'Git', 'Linux', 'SQL', 'NoSQL', 'REST APIs', 'Project Management',
            'Agile', 'Scrum', 'Data Analysis', 'Communication', 'Leadership',
        ];

        return [
            'cv_id' => Cv::factory(),
            'name' => $this->faker->randomElement($skills),
            'category' => $this->faker->randomElement([
                'general', 'technical', 'software', 'industry', 'soft',
            ]),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'sort_order' => 0,
        ];
    }
}

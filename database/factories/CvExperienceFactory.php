<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvExperience>
 */
class CvExperienceFactory extends Factory
{
    protected $model = CvExperience::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $isCurrent = $this->faker->boolean(30);

        return [
            'cv_id' => Cv::factory(),
            'company' => $this->faker->company(),
            'title' => $this->faker->jobTitle(),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'start_date' => $startDate,
            'end_date' => $isCurrent ? null : $this->faker->dateTimeBetween($startDate, 'now'),
            'is_current' => $isCurrent,
            'description' => $this->faker->paragraphs(2, true),
            'aws_services' => $this->faker->randomElements([
                'EC2', 'Lambda', 'S3', 'RDS', 'DynamoDB', 'CloudFormation',
                'ECS', 'EKS', 'CloudWatch', 'IAM', 'VPC', 'API Gateway',
            ], $this->faker->numberBetween(2, 5)),
            'achievements' => [
                'Reduced infrastructure costs by 40% through optimization',
                'Implemented CI/CD pipelines reducing deployment time by 60%',
                'Led migration of 50+ services to AWS cloud',
            ],
            'sort_order' => 0,
        ];
    }

    public function awsFocused(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'AWS Cloud Engineer',
            'description' => 'Designed and implemented highly available, fault-tolerant AWS infrastructure supporting 1M+ daily active users. Architected serverless solutions using Lambda, API Gateway, and DynamoDB.',
            'aws_services' => ['EC2', 'Lambda', 'S3', 'DynamoDB', 'CloudFormation', 'CloudWatch', 'IAM'],
        ]);
    }
}

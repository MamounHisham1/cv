<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvProject>
 */
class CvProjectFactory extends Factory
{
    protected $model = CvProject::class;

    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'name' => $this->faker->catchPhrase() . ' Platform',
            'description' => $this->faker->paragraph(3),
            'aws_services_used' => $this->faker->randomElements([
                'EC2', 'Lambda', 'S3', 'RDS', 'DynamoDB', 'CloudFormation',
                'ECS', 'EKS', 'CloudWatch', 'IAM', 'VPC', 'API Gateway',
                'SQS', 'SNS', 'Cognito', 'CloudFront', 'Route 53',
            ], $this->faker->numberBetween(3, 6)),
            'architecture_type' => $this->faker->randomElement([
                'serverless', 'microservices', 'event-driven', 'containerized',
            ]),
            'key_achievements' => [
                'Processed 10M+ events daily with 99.99% uptime',
                'Reduced latency by 60% through optimization',
                'Saved $50K annually through resource optimization',
            ],
            'project_url' => $this->faker->optional()->url(),
            'github_url' => $this->faker->optional()->url(),
            'start_date' => $this->faker->dateTimeBetween('-2 years', '-6 months'),
            'end_date' => $this->faker->optional(0.3)->dateTimeBetween('-3 months', 'now'),
            'sort_order' => 0,
        ];
    }

    public function serverless(): static
    {
        return $this->state(fn (array $attributes) => [
            'architecture_type' => 'serverless',
            'aws_services_used' => ['Lambda', 'API Gateway', 'DynamoDB', 'S3', 'CloudWatch', 'Step Functions'],
        ]);
    }
}

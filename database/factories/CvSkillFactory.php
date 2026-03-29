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
        $awsServices = [
            'EC2', 'Lambda', 'S3', 'RDS', 'DynamoDB', 'CloudFormation',
            'ECS', 'EKS', 'CloudWatch', 'IAM', 'VPC', 'API Gateway',
            'SQS', 'SNS', 'Step Functions', 'EventBridge', 'CodePipeline',
        ];

        $generalSkills = [
            'Python', 'Java', 'JavaScript', 'TypeScript', 'Go', 'Ruby',
            'Docker', 'Kubernetes', 'Terraform', 'Ansible', 'Jenkins',
            'Git', 'Linux', 'SQL', 'NoSQL', 'Microservices', 'REST APIs',
        ];

        $isAws = $this->faker->boolean(40);
        $name = $isAws
            ? $this->faker->randomElement($awsServices)
            : $this->faker->randomElement($generalSkills);

        return [
            'cv_id' => Cv::factory(),
            'name' => $name,
            'category' => $isAws ? 'cloud' : $this->faker->randomElement([
                'general', 'programming', 'infrastructure', 'data', 'security',
            ]),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'is_aws_service' => $isAws,
            'aws_metadata' => $isAws ? [
                'years_experience' => $this->faker->numberBetween(1, 5),
                'certifications_related' => $this->faker->boolean(),
            ] : null,
            'sort_order' => 0,
        ];
    }

    public function awsService(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'cloud',
            'is_aws_service' => true,
        ]);
    }
}

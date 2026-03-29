<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\CvCertification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvCertification>
 */
class CvCertificationFactory extends Factory
{
    protected $model = CvCertification::class;

    public function definition(): array
    {
        $awsCerts = [
            ['name' => 'AWS Certified Cloud Practitioner', 'level' => 'foundational'],
            ['name' => 'AWS Certified Solutions Architect - Associate', 'level' => 'associate'],
            ['name' => 'AWS Certified Developer - Associate', 'level' => 'associate'],
            ['name' => 'AWS Certified SysOps Administrator - Associate', 'level' => 'associate'],
            ['name' => 'AWS Certified Solutions Architect - Professional', 'level' => 'professional'],
            ['name' => 'AWS Certified DevOps Engineer - Professional', 'level' => 'professional'],
            ['name' => 'AWS Certified Security - Specialty', 'level' => 'specialty'],
            ['name' => 'AWS Certified Machine Learning - Specialty', 'level' => 'specialty'],
        ];

        $otherCerts = [
            ['name' => 'Certified Kubernetes Administrator', 'org' => 'CNCF'],
            ['name' => 'HashiCorp Certified: Terraform Associate', 'org' => 'HashiCorp'],
            ['name' => 'Docker Certified Associate', 'org' => 'Docker'],
        ];

        $isAws = $this->faker->boolean(70);

        if ($isAws) {
            $cert = $this->faker->randomElement($awsCerts);
            return [
                'cv_id' => Cv::factory(),
                'name' => $cert['name'],
                'issuing_organization' => 'Amazon Web Services (AWS)',
                'issue_date' => $this->faker->dateTimeBetween('-3 years', '-6 months'),
                'expiration_date' => $this->faker->optional(0.7)->dateTimeBetween('+6 months', '+2 years'),
                'credential_id' => 'AWS-' . $this->faker->regexify('[A-Z0-9]{10}'),
                'credential_url' => $this->faker->optional()->url(),
                'is_aws_certification' => true,
                'aws_level' => $cert['level'],
                'sort_order' => 0,
            ];
        }

        $cert = $this->faker->randomElement($otherCerts);
        return [
            'cv_id' => Cv::factory(),
            'name' => $cert['name'],
            'issuing_organization' => $cert['org'],
            'issue_date' => $this->faker->dateTimeBetween('-3 years', '-6 months'),
            'expiration_date' => $this->faker->optional(0.5)->dateTimeBetween('+6 months', '+2 years'),
            'credential_id' => $this->faker->regexify('[A-Z0-9]{8}'),
            'credential_url' => $this->faker->optional()->url(),
            'is_aws_certification' => false,
            'aws_level' => null,
            'sort_order' => 0,
        ];
    }

    public function aws(): static
    {
        return $this->state(fn (array $attributes) => [
            'issuing_organization' => 'Amazon Web Services (AWS)',
            'is_aws_certification' => true,
        ]);
    }
}

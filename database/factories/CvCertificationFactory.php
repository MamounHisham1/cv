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
        $certs = [
            ['name' => 'Project Management Professional (PMP)', 'org' => 'PMI'],
            ['name' => 'Certified ScrumMaster (CSM)', 'org' => 'Scrum Alliance'],
            ['name' => 'Google Project Management Certificate', 'org' => 'Google'],
            ['name' => 'Certified Kubernetes Administrator', 'org' => 'CNCF'],
            ['name' => 'HashiCorp Certified: Terraform Associate', 'org' => 'HashiCorp'],
            ['name' => 'Docker Certified Associate', 'org' => 'Docker'],
            ['name' => 'Microsoft Office Specialist', 'org' => 'Microsoft'],
            ['name' => 'HubSpot Inbound Certification', 'org' => 'HubSpot'],
        ];

        $cert = $this->faker->randomElement($certs);

        return [
            'cv_id' => Cv::factory(),
            'name' => $cert['name'],
            'issuing_organization' => $cert['org'],
            'issue_date' => $this->faker->dateTimeBetween('-3 years', '-6 months'),
            'expiration_date' => $this->faker->optional(0.5)->dateTimeBetween('+6 months', '+2 years'),
            'credential_id' => $this->faker->regexify('[A-Z0-9]{8}'),
            'credential_url' => $this->faker->optional()->url(),
            'sort_order' => 0,
        ];
    }
}

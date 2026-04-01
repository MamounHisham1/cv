<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GenerateProfessionalSummary implements Tool
{
    /**
     * Summary templates by career level.
     */
    private const TEMPLATES = [
        'entry' => [
            'certification_focused' => 'AWS Certified {certification} with hands-on experience in {services}. Passionate about cloud computing and eager to contribute to innovative projects leveraging AWS technologies.',
            'project_focused' => 'Cloud-focused developer with experience building {project_types} using {services}. Strong foundation in {skills} with a commitment to learning and growth in AWS ecosystem.',
            'education_focused' => 'Recent {degree} graduate with specialized training in cloud technologies. Practical experience with {services} through academic projects and personal development.',
        ],
        'mid' => [
            'technical_leadership' => 'Results-driven AWS {role} with {years}+ years architecting and deploying scalable cloud solutions. Proven expertise in {services} with track record of {achievement}.',
            'specialist' => 'AWS-certified {specialization} specialist with {years}+ years optimizing {service_area} solutions. Delivered {metrics} through strategic implementation of {services}.',
            'full_stack' => 'Versatile Cloud Engineer with {years}+ years across the full AWS stack. Expertise spans {service_categories}, with particular strength in {specialty_area}.',
        ],
        'senior' => [
            'executive' => 'Strategic cloud leader with {years}+ years driving enterprise AWS transformations. Delivered {business_impact} while building high-performing teams and establishing cloud centers of excellence.',
            'architect' => 'Distinguished Solutions Architect with {years}+ years designing mission-critical systems on AWS. Expert in {architectures} serving {scale} users with 99.99% availability.',
            'consultant' => 'Trusted AWS advisor with {years}+ years guiding Fortune 500 companies through cloud adoption. Specialized in {industries}, delivering average {roi}% ROI on cloud investments.',
        ],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Generate compelling professional summary statements tailored to AWS/cloud careers based on experience, skills, and certifications.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $yearsExperience = $request['years_experience'] ?? 0;
        $skills = $request['skills'] ?? [];
        $certifications = $request['certifications'] ?? [];
        $jobTitle = $request['job_title'] ?? '';
        $achievements = $request['achievements'] ?? [];
        $specializations = $request['specializations'] ?? [];

        $level = $this->determineLevel($yearsExperience);
        $awsServices = $this->extractAwsServices($skills);
        $awsCerts = $this->extractAwsCertifications($certifications);

        $result = "=== Professional Summary Options ===\n\n";

        // Generate different summary styles
        $summaries = $this->generateSummaries($level, $yearsExperience, $awsServices, $awsCerts, $jobTitle, $achievements, $specializations);

        foreach ($summaries as $style => $summary) {
            $result .= '**'.ucfirst(str_replace('_', ' ', $style))."**\n";
            $result .= $summary."\n\n";
        }

        // Key skills to highlight
        $result .= "=== Key Skills to Highlight ===\n";
        $result .= $this->getKeySkillsToHighlight($awsServices, $level)."\n\n";

        // Tailoring tips
        $result .= "=== Tips for Tailoring Your Summary ===\n";
        foreach ($this->getTailoringTips() as $tip) {
            $result .= "• {$tip}\n";
        }

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'years_experience' => $schema->integer()
                ->description('Total years of professional experience'),
            'skills' => $schema->array()
                ->description('List of technical skills'),
            'certifications' => $schema->array()
                ->description('List of certifications'),
            'job_title' => $schema->string()
                ->description('Current or target job title'),
            'achievements' => $schema->array()
                ->description('Key career achievements'),
            'specializations' => $schema->array()
                ->description('Areas of specialization'),
        ];
    }

    /**
     * Determine career level based on experience.
     */
    private function determineLevel(int $years): string
    {
        return match (true) {
            $years < 3 => 'entry',
            $years < 7 => 'mid',
            default => 'senior',
        };
    }

    /**
     * Extract AWS services from skills list.
     */
    private function extractAwsServices(array $skills): array
    {
        $awsServices = [
            'EC2', 'Lambda', 'S3', 'RDS', 'DynamoDB', 'CloudFormation',
            'ECS', 'EKS', 'CloudWatch', 'IAM', 'VPC', 'API Gateway',
            'SQS', 'SNS', 'Step Functions', 'EventBridge', 'CodePipeline',
            'CloudFront', 'Route 53', 'Kinesis', 'Glue', 'Athena',
        ];

        $found = [];
        foreach ($skills as $skill) {
            foreach ($awsServices as $service) {
                if (stripos($skill, $service) !== false) {
                    $found[] = $service;
                }
            }
        }

        return array_unique($found);
    }

    /**
     * Extract AWS certifications.
     */
    private function extractAwsCertifications(array $certs): array
    {
        return array_filter($certs, function ($cert) {
            return stripos($cert, 'AWS') !== false ||
                   stripos($cert, 'Amazon') !== false;
        });
    }

    /**
     * Generate summary options.
     */
    private function generateSummaries(string $level, int $years, array $services, array $certs, string $jobTitle, array $achievements, array $specializations): array
    {
        $summaries = [];
        $templates = self::TEMPLATES[$level] ?? self::TEMPLATES['mid'];

        // Certification-focused summary
        if (! empty($certs)) {
            $highestCert = $this->getHighestCertification($certs);
            $summaries['certification_focused'] = str_replace(
                ['{certification}', '{services}'],
                [$highestCert, $this->formatList(array_slice($services, 0, 4))],
                $templates['certification_focused'] ?? $templates[array_key_first($templates)]
            );
        }

        // Technical leadership summary
        $summaries['technical_leadership'] = str_replace(
            ['{role}', '{years}', '{services}', '{achievement}'],
            [
                $jobTitle ?: 'Cloud Engineer',
                $years,
                $this->formatList(array_slice($services, 0, 5)),
                ! empty($achievements) ? $achievements[0] : 'delivering scalable cloud solutions',
            ],
            $templates['technical_leadership'] ?? $templates[array_key_first($templates)]
        );

        // Specialist summary
        if (! empty($specializations)) {
            $summaries['specialist'] = str_replace(
                ['{specialization}', '{years}', '{service_area}', '{metrics}', '{services}'],
                [
                    $specializations[0],
                    $years,
                    $specializations[0],
                    ! empty($achievements) ? $achievements[0] : 'optimized performance and cost efficiency',
                    $this->formatList(array_slice($services, 0, 4)),
                ],
                $templates['specialist'] ?? $templates[array_key_first($templates)]
            );
        }

        // Senior/Executive summary
        if ($level === 'senior') {
            $summaries['executive'] = str_replace(
                ['{years}', '{business_impact}', '{architectures}', '{scale}'],
                [
                    $years,
                    ! empty($achievements) ? $achievements[0] : 'multi-million dollar cost savings',
                    'highly available, fault-tolerant architectures',
                    'millions of',
                ],
                $templates['executive'] ?? $templates[array_key_first($templates)]
            );
        }

        return $summaries;
    }

    /**
     * Get the highest level certification.
     */
    private function getHighestCertification(array $certs): string
    {
        $hierarchy = [
            'Professional' => 3,
            'Specialty' => 2,
            'Associate' => 1,
            'Practitioner' => 0,
        ];

        $highest = '';
        $highestLevel = -1;

        foreach ($certs as $cert) {
            foreach ($hierarchy as $level => $value) {
                if (stripos($cert, $level) !== false && $value > $highestLevel) {
                    $highestLevel = $value;
                    $highest = $cert;
                }
            }
        }

        return $highest ?: ($certs[0] ?? 'Cloud Professional');
    }

    /**
     * Format a list of items.
     */
    private function formatList(array $items): string
    {
        if (empty($items)) {
            return 'AWS cloud services';
        }

        if (count($items) === 1) {
            return $items[0];
        }

        $last = array_pop($items);

        return implode(', ', $items).' and '.$last;
    }

    /**
     * Get key skills to highlight.
     */
    private function getKeySkillsToHighlight(array $awsServices, string $level): string
    {
        $skills = [];

        if (in_array('CloudFormation', $awsServices) || in_array('Terraform', $awsServices)) {
            $skills[] = 'Infrastructure as Code';
        }

        if (in_array('CodePipeline', $awsServices) || in_array('CodeBuild', $awsServices)) {
            $skills[] = 'CI/CD & DevOps';
        }

        if (in_array('Lambda', $awsServices)) {
            $skills[] = 'Serverless Architecture';
        }

        if (in_array('ECS', $awsServices) || in_array('EKS', $awsServices)) {
            $skills[] = 'Container Orchestration';
        }

        if ($level === 'senior') {
            $skills[] = 'Cloud Strategy & Governance';
            $skills[] = 'Team Leadership';
        }

        if (empty($skills)) {
            $skills = ['AWS Core Services', 'Cloud Architecture', 'Infrastructure Management'];
        }

        return implode(' • ', $skills);
    }

    /**
     * Get tailoring tips.
     */
    private function getTailoringTips(): array
    {
        return [
            'Customize for each job application - match keywords from the job description',
            'Keep it concise: 3-5 sentences for entry/mid level, 4-6 for senior',
            'Lead with your strongest qualification (certification, years, or achievement)',
            'Include specific AWS services relevant to your target role',
            'Quantify achievements where possible (cost savings, performance improvements)',
            'Update regularly as you gain new certifications or complete major projects',
        ];
    }
}

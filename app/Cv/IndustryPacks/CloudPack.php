<?php

namespace App\Cv\IndustryPacks;

/**
 * Optional pack for cloud/cloud-engineer careers. This is where the
 * AWS-specific content that used to live as schema columns and model
 * constants now lives — as presets + prompt context rather than as
 * baked-in data-model coupling.
 */
class CloudPack implements IndustryPack
{
    public function id(): string
    {
        return 'cloud';
    }

    public function name(): string
    {
        return 'Cloud & DevOps';
    }

    public function description(): string
    {
        return 'Presets for cloud engineers, DevOps, and platform roles (AWS, Azure, GCP).';
    }

    public function skillCategories(): array
    {
        return [
            'general' => 'General',
            'cloud' => 'Cloud Platforms',
            'programming' => 'Programming Languages',
            'infrastructure' => 'Infrastructure & DevOps',
            'data' => 'Data & Analytics',
            'security' => 'Security & Compliance',
            'soft' => 'Soft Skills',
        ];
    }

    public function skillSuggestions(): array
    {
        return [
            'EC2', 'Lambda', 'S3', 'RDS', 'DynamoDB', 'CloudFormation',
            'ECS', 'EKS', 'CloudWatch', 'IAM', 'VPC', 'API Gateway',
            'SQS', 'SNS', 'Step Functions', 'EventBridge', 'CodePipeline',
            'Python', 'Java', 'JavaScript', 'TypeScript', 'Go',
            'Docker', 'Kubernetes', 'Terraform', 'Ansible', 'Jenkins',
        ];
    }

    public function certificationSuggestions(): array
    {
        return [
            'AWS Certified Cloud Practitioner',
            'AWS Certified Solutions Architect - Associate',
            'AWS Certified Developer - Associate',
            'AWS Certified SysOps Administrator - Associate',
            'AWS Certified Solutions Architect - Professional',
            'AWS Certified DevOps Engineer - Professional',
            'AWS Certified Security - Specialty',
            'AWS Certified Machine Learning - Specialty',
            'Microsoft Certified: Azure Fundamentals',
            'Google Cloud Associate Cloud Engineer',
            'Certified Kubernetes Administrator (CKA)',
            'HashiCorp Certified: Terraform Associate',
        ];
    }

    public function promptContext(): string
    {
        return <<<'TEXT'
This CV targets a cloud / DevOps career. When generating summaries or
improving project descriptions, lean on cloud-native value propositions
(scalability, high availability, fault tolerance, cost optimization,
automation, infrastructure as code) and name specific cloud services
the candidate has used. Certifications can be ranked by tier
(Foundational < Associate < Professional < Specialty).
TEXT;
    }
}

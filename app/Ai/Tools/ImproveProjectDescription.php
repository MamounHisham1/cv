<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ImproveProjectDescription implements Tool
{
    /**
     * Action verbs for project descriptions.
     */
    private const ACTION_VERBS = [
        'Architected', 'Built', 'Designed', 'Developed', 'Engineered',
        'Implemented', 'Deployed', 'Optimized', 'Automated', 'Migrated',
        'Led', 'Managed', 'Orchestrated', 'Streamlined', 'Transformed',
        'Configured', 'Integrated', 'Refactored', 'Scaled', 'Secured',
    ];

    /**
     * AWS-specific keywords to look for and enhance.
     */
    private const AWS_KEYWORDS = [
        'scalability' => ['scaled', 'auto-scaling', 'high availability', 'fault-tolerant'],
        'cost' => ['cost optimization', 'reduced costs', 'reserved instances', 'savings'],
        'security' => ['encryption', 'IAM', 'compliance', 'security groups', 'WAF'],
        'performance' => ['latency', 'throughput', 'caching', 'CDN', 'optimization'],
        'automation' => ['CI/CD', 'infrastructure as code', 'automation', 'scripting'],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Transform raw project descriptions into compelling, achievement-focused statements with metrics and AWS value propositions.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $description = $request['description'] ?? '';
        $role = $request['role'] ?? '';
        $awsServices = $request['aws_services'] ?? [];
        $metrics = $request['metrics'] ?? [];

        if (empty($description)) {
            return 'Error: Please provide a project description to improve.';
        }

        $result = "=== Improved Project Description ===\n\n";

        // Original analysis
        $result .= "Original:\n{$description}\n\n";

        // Generate improved versions
        $improved = $this->generateImprovedDescription($description, $role, $awsServices, $metrics);
        $result .= "Improved Version:\n{$improved}\n\n";

        // Alternative versions
        $result .= "=== Alternative Versions ===\n\n";
        $result .= "Technical Focus:\n".$this->generateTechnicalVersion($description, $awsServices)."\n\n";
        $result .= "Business Impact Focus:\n".$this->generateBusinessVersion($description, $metrics)."\n\n";

        // Suggestions
        $result .= "=== Suggestions for Further Improvement ===\n";
        $suggestions = $this->generateSuggestions($description, $metrics);
        foreach ($suggestions as $suggestion) {
            $result .= "• {$suggestion}\n";
        }

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'description' => $schema->string()
                ->description('The raw project description to improve'),
            'role' => $schema->string()
                ->description('Your role in the project'),
            'aws_services' => $schema->array()
                ->description('AWS services used in the project'),
            'metrics' => $schema->array()
                ->description('Quantifiable metrics (e.g., cost savings, performance improvements)'),
        ];
    }

    /**
     * Generate improved description.
     */
    private function generateImprovedDescription(string $original, string $role, array $awsServices, array $metrics): string
    {
        $sentences = explode('. ', $original);
        $improved = [];

        foreach ($sentences as $sentence) {
            $improved[] = $this->enhanceSentence($sentence, $role);
        }

        $result = implode('. ', $improved);

        // Add AWS services context
        if (! empty($awsServices)) {
            $result .= ' Utilized '.implode(', ', $awsServices).' to deliver the solution.';
        }

        // Add metrics
        if (! empty($metrics)) {
            $result .= ' '.$this->formatMetrics($metrics);
        }

        return $result;
    }

    /**
     * Enhance a single sentence.
     */
    private function enhanceSentence(string $sentence, string $role): string
    {
        $sentence = trim($sentence);
        if (empty($sentence)) {
            return '';
        }

        // Capitalize first letter
        $sentence = ucfirst($sentence);

        // Ensure sentence ends with period
        if (! str_ends_with($sentence, '.')) {
            $sentence .= '.';
        }

        // Replace weak verbs with strong ones
        $weakVerbs = ['worked on', 'helped with', 'was involved in', 'participated in'];
        foreach ($weakVerbs as $weak) {
            if (stripos($sentence, $weak) !== false) {
                $strongVerb = $this->getRandomActionVerb();
                $sentence = str_ireplace($weak, $strongVerb, $sentence);
                break;
            }
        }

        return $sentence;
    }

    /**
     * Generate technical-focused version.
     */
    private function generateTechnicalVersion(string $description, array $awsServices): string
    {
        $techTerms = [
            'implemented', 'architected', 'engineered', 'deployed',
            'configured', 'optimized', 'automated', 'integrated',
        ];

        $result = 'Architected and implemented a scalable solution leveraging ';
        $result .= ! empty($awsServices)
            ? implode(', ', array_slice($awsServices, 0, 4))
            : 'AWS cloud services';
        $result .= '. Designed with high availability, fault tolerance, and security best practices. ';
        $result .= 'Implemented Infrastructure as Code using CloudFormation/Terraform for automated provisioning.';

        return $result;
    }

    /**
     * Generate business-impact version.
     */
    private function generateBusinessVersion(string $description, array $metrics): string
    {
        $result = 'Led initiative that delivered significant business value through cloud transformation. ';

        if (! empty($metrics)) {
            $result .= $this->formatMetrics($metrics).' ';
        } else {
            $result .= 'Achieved improved operational efficiency and reduced time-to-market. ';
        }

        $result .= 'Enhanced system reliability and enabled the organization to scale operations seamlessly.';

        return $result;
    }

    /**
     * Format metrics into a sentence.
     */
    private function formatMetrics(array $metrics): string
    {
        $formatted = [];

        foreach ($metrics as $metric) {
            $formatted[] = $metric;
        }

        if (empty($formatted)) {
            return '';
        }

        return 'Results included: '.implode('; ', $formatted).'.';
    }

    /**
     * Generate improvement suggestions.
     */
    private function generateSuggestions(string $description, array $existingMetrics): array
    {
        $suggestions = [];
        $descLower = strtolower($description);

        // Check for metrics
        if (empty($existingMetrics)) {
            $suggestions[] = "Add quantifiable metrics (e.g., 'reduced costs by 30%', 'improved performance by 50%')";
        }

        // Check for weak verbs
        $weakVerbs = ['worked on', 'helped with', 'was involved in'];
        foreach ($weakVerbs as $verb) {
            if (str_contains($descLower, $verb)) {
                $suggestions[] = "Replace '{$verb}' with a stronger action verb like 'Architected', 'Engineered', or 'Led'";
                break;
            }
        }

        // Check for AWS keywords
        $hasAwsFocus = false;
        foreach (self::AWS_KEYWORDS as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($descLower, $keyword)) {
                    $hasAwsFocus = true;
                    break 2;
                }
            }
        }

        if (! $hasAwsFocus) {
            $suggestions[] = 'Consider adding AWS-specific value propositions (scalability, cost optimization, security)';
        }

        // Check for technical depth
        if (strlen($description) < 100) {
            $suggestions[] = 'Expand with more technical details about architecture decisions and implementation';
        }

        // Check for outcome focus
        if (! str_contains($descLower, 'result') && ! str_contains($descLower, 'improved') &&
            ! str_contains($descLower, 'reduced') && ! str_contains($descLower, 'increased')) {
            $suggestions[] = 'Focus on outcomes and impact rather than just activities';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Great description! Consider tailoring it to specific job applications.';
        }

        return $suggestions;
    }

    /**
     * Get a random action verb.
     */
    private function getRandomActionVerb(): string
    {
        return self::ACTION_VERBS[array_rand(self::ACTION_VERBS)];
    }
}

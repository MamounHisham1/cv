<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Transforms raw project descriptions into compelling, achievement-focused
 * statements. Industry-neutral — leans on strong action verbs and
 * quantified outcomes rather than any domain-specific value props.
 */
class ImproveProjectDescription implements Tool
{
    private const ACTION_VERBS = [
        'Architected', 'Built', 'Designed', 'Developed', 'Engineered',
        'Implemented', 'Deployed', 'Optimized', 'Automated', 'Migrated',
        'Led', 'Managed', 'Orchestrated', 'Streamlined', 'Transformed',
        'Configured', 'Integrated', 'Refactored', 'Scaled', 'Secured',
    ];

    public function description(): Stringable|string
    {
        return 'Transform raw project descriptions into compelling, achievement-focused statements with metrics and strong action verbs.';
    }

    public function handle(Request $request): Stringable|string
    {
        $description = $request['description'] ?? '';
        $role = $request['role'] ?? '';
        $metrics = $request['metrics'] ?? [];
        $technologies = $request['technologies'] ?? [];

        if (empty(trim($description))) {
            return 'Error: Please provide a project description to improve.';
        }

        $result = "=== Improved Project Description ===\n\n";
        $result .= "Original:\n{$description}\n\n";

        $improved = $this->generateImprovedDescription($description, $role, $technologies, $metrics);
        $result .= "Improved Version:\n{$improved}\n\n";

        $result .= "=== Alternative Versions ===\n\n";
        $result .= "Technical Focus:\n".$this->generateTechnicalVersion($description, $technologies)."\n\n";
        $result .= "Business Impact Focus:\n".$this->generateBusinessVersion($metrics)."\n\n";

        $result .= "=== Suggestions for Further Improvement ===\n";
        foreach ($this->generateSuggestions($description, $metrics) as $suggestion) {
            $result .= "• {$suggestion}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'description' => $schema->string()->description('The raw project description to improve'),
            'role' => $schema->string()->description('Your role in the project'),
            'technologies' => $schema->array()->description('Technologies, tools, or methods used in the project'),
            'metrics' => $schema->array()->description('Quantifiable metrics (e.g., cost savings, performance improvements)'),
        ];
    }

    private function generateImprovedDescription(string $original, string $role, array $technologies, array $metrics): string
    {
        $improved = array_map(
            fn ($sentence) => $this->enhanceSentence($sentence, $role),
            explode('. ', $original),
        );

        $result = implode('. ', array_filter($improved));

        if (! empty($technologies)) {
            $result .= ' Used '.implode(', ', array_slice($technologies, 0, 6)).' to deliver the solution.';
        }

        if (! empty($metrics)) {
            $result .= ' '.$this->formatMetrics($metrics);
        }

        return $result;
    }

    private function enhanceSentence(string $sentence, string $role): string
    {
        $sentence = trim($sentence);

        if ($sentence === '') {
            return '';
        }

        $sentence = ucfirst($sentence);

        if (! str_ends_with($sentence, '.')) {
            $sentence .= '.';
        }

        foreach (['worked on', 'helped with', 'was involved in', 'participated in'] as $weak) {
            if (stripos($sentence, $weak) !== false) {
                $sentence = str_ireplace($weak, self::ACTION_VERBS[array_rand(self::ACTION_VERBS)], $sentence);

                break;
            }
        }

        return $sentence;
    }

    private function generateTechnicalVersion(string $description, array $technologies): string
    {
        $result = 'Designed and implemented a robust solution';

        if (! empty($technologies)) {
            $result .= ' leveraging '.implode(', ', array_slice($technologies, 0, 4));
        }

        $result .= '. Built with maintainability, reliability, and clear documentation as first-class priorities.';

        return $result;
    }

    private function generateBusinessVersion(array $metrics): string
    {
        $result = 'Led an initiative that delivered significant value to the organization. ';

        if (! empty($metrics)) {
            $result .= $this->formatMetrics($metrics).' ';
        } else {
            $result .= 'Improved operational efficiency and reduced time-to-market. ';
        }

        $result .= 'Enhanced reliability and enabled the team to scale its work seamlessly.';

        return $result;
    }

    private function formatMetrics(array $metrics): string
    {
        if (empty($metrics)) {
            return '';
        }

        return 'Results included: '.implode('; ', $metrics).'.';
    }

    private function generateSuggestions(string $description, array $existingMetrics): array
    {
        $suggestions = [];
        $descLower = strtolower($description);

        if (empty($existingMetrics)) {
            $suggestions[] = "Add quantifiable metrics (e.g., 'reduced costs by 30%', 'improved performance by 50%').";
        }

        foreach (['worked on', 'helped with', 'was involved in'] as $verb) {
            if (str_contains($descLower, $verb)) {
                $suggestions[] = "Replace '{$verb}' with a stronger action verb like 'Architected', 'Engineered', or 'Led'.";

                break;
            }
        }

        if (strlen($description) < 100) {
            $suggestions[] = 'Expand with more detail about your decisions and how you implemented them.';
        }

        if (! str_contains($descLower, 'result') && ! str_contains($descLower, 'improved') &&
            ! str_contains($descLower, 'reduced') && ! str_contains($descLower, 'increased')) {
            $suggestions[] = 'Focus on outcomes and impact rather than just activities.';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Great description! Consider tailoring it to specific job applications.';
        }

        return $suggestions;
    }
}

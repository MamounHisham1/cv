<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Generates industry-neutral professional summary options for a CV.
 *
 * Previously this tool was hard-wired to AWS/cloud careers (AWS service
 * lists, AWS cert hierarchies, AWS-flavored templates). That coupling now
 * lives in the cloud industry pack's prompt context; this tool only knows
 * how to compose summaries from the candidate's own skills, certs, and
 * achievements.
 */
class GenerateProfessionalSummary implements Tool
{
    public function description(): Stringable|string
    {
        return 'Generate compelling professional summary statements tailored to the candidate\'s experience, skills, and certifications.';
    }

    public function handle(Request $request): Stringable|string
    {
        $yearsExperience = (int) ($request['years_experience'] ?? 0);
        $skills = $request['skills'] ?? [];
        $certifications = $request['certifications'] ?? [];
        $jobTitle = $request['job_title'] ?? '';
        $achievements = $request['achievements'] ?? [];
        $specializations = $request['specializations'] ?? [];

        $level = $this->determineLevel($yearsExperience);

        $result = "=== Professional Summary Options ===\n\n";

        $summaries = $this->generateSummaries(
            $level,
            $yearsExperience,
            $skills,
            $certifications,
            $jobTitle,
            $achievements,
            $specializations,
        );

        foreach ($summaries as $style => $summary) {
            $result .= '**'.ucfirst(str_replace('_', ' ', $style))."**\n";
            $result .= $summary."\n\n";
        }

        $result .= "=== Key Skills to Highlight ===\n";
        $result .= $this->keySkillsToHighlight($skills, $level)."\n\n";

        $result .= "=== Tips for Tailoring Your Summary ===\n";
        foreach ($this->tailoringTips() as $tip) {
            $result .= "• {$tip}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'years_experience' => $schema->integer()->description('Total years of professional experience'),
            'skills' => $schema->array()->description('List of technical skills'),
            'certifications' => $schema->array()->description('List of certifications'),
            'job_title' => $schema->string()->description('Current or target job title'),
            'achievements' => $schema->array()->description('Key career achievements'),
            'specializations' => $schema->array()->description('Areas of specialization'),
        ];
    }

    private function determineLevel(int $years): string
    {
        return match (true) {
            $years < 3 => 'entry',
            $years < 7 => 'mid',
            default => 'senior',
        };
    }

    private function generateSummaries(string $level, int $years, array $skills, array $certs, string $jobTitle, array $achievements, array $specializations): array
    {
        $role = $jobTitle ?: 'professional';
        $topSkills = $this->formatList(array_slice($skills, 0, 4));
        $headlineAchievement = $achievements[0] ?? 'delivering measurable results';

        $summaries = [];

        // Certification-led option.
        if (! empty($certs)) {
            $topCert = $certs[0];
            $summaries['certification_focused'] = "{$topCert}-certified {$role} with hands-on experience across {$topSkills}. Committed to applying best practices and continuous learning.";
        }

        // Experience-led option.
        $summaries['experience'] = match ($level) {
            'entry' => "Emerging {$role} with practical exposure to {$topSkills}. Eager to contribute to {$headlineAchievement} while growing within a collaborative team.",
            'mid' => "Results-driven {$role} with {$years}+ years of experience across {$topSkills}. Proven track record of {$headlineAchievement}.",
            default => "Strategic {$role} with {$years}+ years of experience leading initiatives across {$topSkills}. Recognized for {$headlineAchievement} and mentoring high-performing teams.",
        };

        // Specialization option.
        if (! empty($specializations)) {
            $specialty = $specializations[0];
            $summaries['specialist'] = "{$role} specializing in {$specialty}, with {$years}+ years of experience. Strengths include {$topSkills}, with a focus on {$headlineAchievement}.";
        }

        // Senior/executive option.
        if ($level === 'senior') {
            $summaries['executive'] = "Senior {$role} with {$years}+ years driving measurable business impact through {$headlineAchievement}. Builds and leads teams that consistently deliver on strategic goals.";
        }

        return $summaries;
    }

    private function formatList(array $items): string
    {
        $items = array_values(array_filter($items, fn ($item) => is_string($item) && trim($item) !== ''));

        if (empty($items)) {
            return 'core skills';
        }

        if (count($items) === 1) {
            return $items[0];
        }

        $last = array_pop($items);

        return implode(', ', $items).' and '.$last;
    }

    private function keySkillsToHighlight(array $skills, string $level): string
    {
        $highlight = array_slice($skills, 0, 6);

        if (empty($highlight)) {
            $highlight = ['Core domain skills', 'Collaboration', 'Problem solving'];
        }

        if ($level === 'senior') {
            $highlight[] = 'Leadership';
            $highlight[] = 'Mentorship';
        }

        return implode(' • ', $highlight);
    }

    private function tailoringTips(): array
    {
        return [
            'Customize for each job application — match keywords from the job description.',
            'Keep it concise: 3–5 sentences for entry/mid level, 4–6 for senior.',
            'Lead with your strongest qualification (certification, years, or achievement).',
            'Name the specific tools, technologies, or methods relevant to your target role.',
            'Quantify achievements where possible (cost savings, performance gains, scale).',
            'Update regularly as you gain new certifications or complete major projects.',
        ];
    }
}

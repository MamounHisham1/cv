<?php

namespace App\Ai\Tools;

use App\CvTemplates;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SelectBestTemplate implements Tool
{
    /**
     * Recommendation metadata per template.
     *
     * Display names and descriptions are sourced from {@see CvTemplates}
     * (single source of truth); this constant only holds the recommendation-
     * specific context (best_for, strengths, weaknesses, ideal_roles).
     */
    private const TEMPLATE_META = [
        'professional-classic' => [
            'best_for' => [
                'Traditional industries (finance, healthcare, government)',
                'Senior professionals seeking executive roles',
                'Conservative company cultures',
                'ATS optimization with human readability',
            ],
            'strengths' => ['Highly ATS-compatible', 'Professional appearance', 'Easy to scan'],
            'weaknesses' => ['Less visually distinctive', 'May look dated in tech startups'],
            'ideal_roles' => ['Solutions Architect', 'Cloud Consultant', 'IT Manager', 'Security Engineer'],
        ],
        'technical-ats' => [
            'best_for' => [
                'Large companies with strict ATS filtering',
                'Keyword-heavy technical roles',
                'First-round applications',
                'Roles with high competition',
            ],
            'strengths' => ['Maximum ATS compatibility', 'Keyword prominence', 'Clean parsing'],
            'weaknesses' => ['Minimal visual design', 'May look plain to human reviewers'],
            'ideal_roles' => ['Cloud Engineer', 'DevOps Engineer', 'Site Reliability Engineer', 'Platform Engineer'],
        ],
        'modern-minimal' => [
            'best_for' => [
                'Tech companies and startups',
                'Creative tech roles',
                'Modern corporate environments',
                'Professionals wanting contemporary look',
            ],
            'strengths' => ['Visually appealing', 'Easy to read', 'Professional yet modern'],
            'weaknesses' => ['Some ATS may parse less accurately', 'Requires careful formatting'],
            'ideal_roles' => ['Software Engineer', 'Full Stack Developer', 'Product Manager', 'Tech Lead'],
        ],
        'creative' => [
            'best_for' => [
                'Portfolio-driven applications',
                'Roles requiring visual presentation skills',
                'Smaller companies with human reviewers',
                'Standing out in creative tech',
            ],
            'strengths' => ['Visually distinctive', 'Showcases skills effectively', 'Memorable'],
            'weaknesses' => ['Poor ATS compatibility', 'Not suitable for conservative industries'],
            'ideal_roles' => ['UI/UX Engineer', 'Frontend Developer', 'Technical Evangelist', 'Developer Advocate'],
        ],
        'executive' => [
            'best_for' => [
                'Senior leadership positions (VP, Director, CTO)',
                'Strategic roles requiring business impact focus',
                'Consulting and advisory positions',
                'C-level executive applications',
            ],
            'strengths' => ['Highlights leadership', 'Emphasizes business impact', 'Professional authority'],
            'weaknesses' => ['Overkill for junior roles', 'May seem pretentious for technical-only roles'],
            'ideal_roles' => ['VP of Engineering', 'CTO', 'Head of Cloud', 'Principal Architect', 'Director of DevOps'],
        ],
        'bold' => [
            'best_for' => ['Standout applications in competitive tech markets', 'Candidates wanting a strong visual identity'],
            'strengths' => ['Eye-catching header', 'Clear skill categorization'],
            'weaknesses' => ['Bold styling may not suit conservative fields'],
            'ideal_roles' => ['Full-Stack Developer', 'Growth Engineer', 'Startup Engineer'],
        ],
        'timeline' => [
            'best_for' => ['Candidates with a clear progression story', 'Career changers showing growth'],
            'strengths' => ['Visualizes career progression', 'Engaging narrative'],
            'weaknesses' => ['Heavier visual layout', 'Less ATS-friendly'],
            'ideal_roles' => ['Engineering Manager', 'Senior Developer', 'Project Lead'],
        ],
        'swiss' => [
            'best_for' => ['Design-conscious candidates', 'Roles valuing clarity and structure'],
            'strengths' => ['Typographic clarity', 'Structured grid', 'Shows skill levels'],
            'weaknesses' => ['Distinctive red accent may not suit all brands'],
            'ideal_roles' => ['Product Designer', 'Frontend Engineer', 'Technical Writer'],
        ],
        'warm' => [
            'best_for' => ['Approachable, people-focused roles', 'Candidates wanting a friendly, personal tone'],
            'strengths' => ['Warm, personal feel', 'Photo + skill dots', 'Two-column readability'],
            'weaknesses' => ['Less formal for conservative industries'],
            'ideal_roles' => ['Customer Success', 'Educator', 'Community Manager', 'People Manager'],
        ],
        'compact' => [
            'best_for' => ['Experienced professionals with dense experience', 'One-page constraint'],
            'strengths' => ['Maximizes content density', 'Fits extensive experience'],
            'weaknesses' => ['Tight spacing', 'Less whitespace'],
            'ideal_roles' => ['Senior Engineer', 'Principal Engineer', 'Consultant'],
        ],
    ];

    /**
     * Career level recommendations.
     */
    private const LEVEL_RECOMMENDATIONS = [
        'entry' => ['technical-ats', 'modern-minimal', 'professional-classic'],
        'mid' => ['modern-minimal', 'professional-classic', 'technical-ats'],
        'senior' => ['executive', 'professional-classic', 'modern-minimal'],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Recommend the best CV template based on career level, target role, experience type, and application context.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $careerLevel = $request['career_level'] ?? 'mid';
        $targetRole = $request['target_role'] ?? '';
        $experienceType = $request['experience_type'] ?? 'mixed';
        $companyType = $request['company_type'] ?? 'enterprise';
        $atsPriority = $request['ats_priority'] ?? 'medium';

        $result = "=== CV Template Recommendation ===\n\n";

        // Get primary recommendation
        $recommendation = $this->getRecommendation($careerLevel, $targetRole, $experienceType, $companyType, $atsPriority);
        $template = self::TEMPLATE_META[$recommendation];
        $name = CvTemplates::name($recommendation);
        $description = CvTemplates::all()[$recommendation]['description'];

        $result .= "**Recommended: {$name}**\n";
        $result .= "ID: {$recommendation}\n";
        $result .= "{$description}\n\n";

        $result .= "**Why this template?**\n";
        foreach ($this->getWhyThisTemplate($recommendation, $careerLevel, $companyType, $atsPriority) as $reason) {
            $result .= "• {$reason}\n";
        }
        $result .= "\n";

        $result .= "**Best suited for:**\n";
        foreach ($template['best_for'] as $item) {
            $result .= "• {$item}\n";
        }
        $result .= "\n";

        $result .= "**Strengths:**\n";
        foreach ($template['strengths'] as $strength) {
            $result .= "✓ {$strength}\n";
        }
        $result .= "\n";

        $result .= "**Considerations:**\n";
        foreach ($template['weaknesses'] as $weakness) {
            $result .= "⚠ {$weakness}\n";
        }
        $result .= "\n";

        // Alternative options
        $result .= "=== Alternative Options ===\n\n";
        $alternatives = $this->getAlternatives($recommendation, $careerLevel);
        foreach ($alternatives as $altId) {
            $alt = self::TEMPLATE_META[$altId];
            $altName = CvTemplates::name($altId);
            $result .= "**{$altName}** ({$altId})\n";
            $result .= CvTemplates::all()[$altId]['description']."\n";
            $result .= 'Best for: '.implode(', ', array_slice($alt['ideal_roles'], 0, 2))."\n\n";
        }

        // Final advice
        $result .= "=== Final Advice ===\n";
        $result .= $this->getFinalAdvice($atsPriority, $companyType);

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'career_level' => $schema->string()
                ->enum(['entry', 'mid', 'senior'])
                ->description('Career level (entry, mid, or senior)'),
            'target_role' => $schema->string()
                ->description('Target job title or role'),
            'experience_type' => $schema->string()
                ->enum(['technical', 'management', 'mixed', 'consulting'])
                ->description('Primary type of experience'),
            'company_type' => $schema->string()
                ->enum(['startup', 'enterprise', 'government', 'consulting'])
                ->description('Type of company being applied to'),
            'ats_priority' => $schema->string()
                ->enum(['high', 'medium', 'low'])
                ->description('Priority for ATS compatibility'),
        ];
    }

    /**
     * Get primary template recommendation.
     */
    private function getRecommendation(string $level, string $role, string $experienceType, string $companyType, string $atsPriority): string
    {
        // High ATS priority overrides other factors
        if ($atsPriority === 'high') {
            return 'technical-ats';
        }

        // Government and enterprise often prefer classic
        if (in_array($companyType, ['government', 'enterprise']) && $level !== 'entry') {
            return 'professional-classic';
        }

        // Senior level with management focus
        if ($level === 'senior' && $experienceType === 'management') {
            return 'executive';
        }

        // Startup preference
        if ($companyType === 'startup') {
            return $level === 'entry' ? 'modern-minimal' : 'modern-minimal';
        }

        // Check role-specific recommendations
        $roleLower = strtolower($role);
        if (str_contains($roleLower, 'architect') || str_contains($roleLower, 'consultant')) {
            return $level === 'senior' ? 'executive' : 'professional-classic';
        }

        if (str_contains($roleLower, 'engineer') || str_contains($roleLower, 'devops')) {
            return $atsPriority === 'medium' ? 'modern-minimal' : 'technical-ats';
        }

        // Default to level-based recommendation
        $recommendations = self::LEVEL_RECOMMENDATIONS[$level] ?? self::LEVEL_RECOMMENDATIONS['mid'];

        return $recommendations[0];
    }

    /**
     * Get reasons for the recommendation.
     */
    private function getWhyThisTemplate(string $templateId, string $level, string $companyType, string $atsPriority): array
    {
        $reasons = [];

        if ($atsPriority === 'high') {
            $reasons[] = 'You specified high priority for ATS compatibility';
        }

        if ($companyType === 'government') {
            $reasons[] = 'Government applications typically favor traditional, conservative formats';
        }

        if ($level === 'senior') {
            $reasons[] = 'Senior-level positions benefit from a format that emphasizes leadership and impact';
        }

        if ($level === 'entry') {
            $reasons[] = 'Entry-level candidates should prioritize ATS compatibility to get past initial screening';
        }

        $reasons[] = 'The '.CvTemplates::name($templateId).' template aligns with industry standards for your profile';

        return $reasons;
    }

    /**
     * Get alternative template options.
     */
    private function getAlternatives(string $primary, string $level): array
    {
        $recommendations = self::LEVEL_RECOMMENDATIONS[$level] ?? self::LEVEL_RECOMMENDATIONS['mid'];

        return array_values(array_filter($recommendations, function ($id) use ($primary) {
            return $id !== $primary;
        }));
    }

    /**
     * Get final advice string.
     */
    private function getFinalAdvice(string $atsPriority, string $companyType): string
    {
        $advice = [];

        if ($atsPriority !== 'high') {
            $advice[] = 'Consider creating an ATS-optimized version for initial online applications';
        }

        if ($companyType === 'startup') {
            $advice[] = 'For startups, a visually appealing template can help you stand out';
        } else {
            $advice[] = 'For larger companies, ensure your template is ATS-friendly';
        }

        $advice[] = "Always test your CV by uploading it to the company's application system if possible";
        $advice[] = 'Consider having multiple template versions tailored to different types of applications';

        return implode("\n", array_map(fn ($a) => "• {$a}", $advice));
    }
}

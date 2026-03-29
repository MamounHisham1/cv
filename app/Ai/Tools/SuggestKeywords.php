<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SuggestKeywords implements Tool
{
    /**
     * Keywords by profession.
     */
    private const PROFESSION_KEYWORDS = [
        'software_engineer' => [
            'core' => ['Software Development', 'Programming', 'Code Review', 'Debugging', 'Testing'],
            'methodologies' => ['Agile', 'Scrum', 'CI/CD', 'Version Control', 'Git'],
            'concepts' => ['APIs', 'Microservices', 'REST', 'JSON', 'Authentication', 'Authorization'],
        ],
        'data_scientist' => [
            'core' => ['Machine Learning', 'Data Analysis', 'Statistics', 'Python', 'R'],
            'tools' => ['TensorFlow', 'PyTorch', 'Pandas', 'NumPy', 'Scikit-learn', 'Jupyter'],
            'concepts' => ['Predictive Modeling', 'Data Visualization', 'A/B Testing', 'ETL'],
        ],
        'product_manager' => [
            'core' => ['Product Strategy', 'Roadmap', 'User Research', 'Market Analysis', 'Agile'],
            'tools' => ['Jira', 'Confluence', 'Figma', 'Analytics', 'A/B Testing'],
            'concepts' => ['KPIs', 'User Stories', 'Stakeholder Management', 'Prioritization'],
        ],
        'marketing_manager' => [
            'core' => ['Digital Marketing', 'Brand Management', 'Campaign Strategy', 'Analytics'],
            'channels' => ['SEO', 'SEM', 'Social Media', 'Email Marketing', 'Content Marketing'],
            'tools' => ['Google Analytics', 'HubSpot', 'Salesforce', 'Adobe', 'Mailchimp'],
        ],
        'project_manager' => [
            'core' => ['Project Management', 'Stakeholder Management', 'Risk Management', 'Budgeting'],
            'methodologies' => ['Agile', 'Scrum', 'Waterfall', 'Kanban', 'PMP', 'PRINCE2'],
            'tools' => ['MS Project', 'Jira', 'Asana', 'Monday', 'Slack', 'Confluence'],
        ],
    ];

    /**
     * Action verbs for CVs.
     */
    private const ACTION_VERBS = [
        'leadership' => ['Led', 'Managed', 'Directed', 'Supervised', 'Oversaw', 'Guided'],
        'achievement' => ['Achieved', 'Accomplished', 'Delivered', 'Attained', 'Exceeded'],
        'creation' => ['Developed', 'Created', 'Built', 'Designed', 'Implemented', 'Launched'],
        'improvement' => ['Improved', 'Optimized', 'Enhanced', 'Streamlined', 'Refined'],
        'analysis' => ['Analyzed', 'Evaluated', 'Assessed', 'Reviewed', 'Investigated'],
        'communication' => ['Presented', 'Negotiated', 'Collaborated', 'Facilitated', 'Reported'],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Suggest relevant keywords and action verbs for your CV based on your target role and industry.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $profession = $request['profession'] ?? '';
        $industry = $request['industry'] ?? '';
        $experience = $request['experience'] ?? 'mid';
        $currentSkills = $request['current_skills'] ?? [];

        $result = "=== Keyword Suggestions ===\n\n";

        // Profession-specific keywords
        if ($profession && isset(self::PROFESSION_KEYWORDS[$profession])) {
            $keywords = self::PROFESSION_KEYWORDS[$profession];
            $result .= "**For {$profession} roles:**\n\n";

            foreach ($keywords as $category => $words) {
                $result .= ucfirst($category) . ":\n";
                foreach ($words as $word) {
                    $marker = in_array(strtolower($word), array_map('strtolower', $currentSkills)) ? '✓' : '•';
                    $result .= "  {$marker} {$word}\n";
                }
                $result .= "\n";
            }
        }

        // Action verbs
        $result .= "**Recommended Action Verbs:**\n\n";
        foreach (self::ACTION_VERBS as $category => $verbs) {
            $result .= ucfirst($category) . ": " . implode(', ', array_slice($verbs, 0, 4)) . "\n";
        }
        $result .= "\n";

        // ATS optimization tips
        $result .= "=== ATS Optimization Tips ===\n\n";
        $result .= $this->getAtsTips($experience);

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'profession' => $schema->string()
                ->enum(array_keys(self::PROFESSION_KEYWORDS))
                ->description('Your target profession/role'),
            'industry' => $schema->string()
                ->description('Target industry (e.g., technology, finance, healthcare)'),
            'experience' => $schema->string()
                ->enum(['entry', 'mid', 'senior'])
                ->description('Experience level'),
            'current_skills' => $schema->array()
                ->description('Skills you currently have on your CV'),
        ];
    }

    /**
     * Get ATS optimization tips.
     */
    private function getAtsTips(string $experience): string
    {
        $tips = [
            "Use standard section headings: 'Work Experience', 'Education', 'Skills'",
            "Include keywords exactly as they appear in job descriptions",
            "Use both acronyms and full terms (e.g., 'AWS (Amazon Web Services)')",
            "Avoid tables, headers/footers, and complex formatting",
            "Use a single-column layout for best ATS compatibility",
            "Save your CV as a .docx or .pdf file",
        ];

        if ($experience === 'senior') {
            $tips[] = "Highlight leadership and strategic impact";
            $tips[] = "Include metrics and business outcomes";
        } elseif ($experience === 'entry') {
            $tips[] = "Focus on relevant coursework and projects";
            $tips[] = "Include internships and volunteer work";
        }

        $result = "";
        foreach ($tips as $tip) {
            $result .= "• {$tip}\n";
        }

        return $result;
    }
}

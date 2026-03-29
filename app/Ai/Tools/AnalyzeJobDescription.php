<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AnalyzeJobDescription implements Tool
{
    /**
     * Common ATS keywords by industry.
     */
    private const INDUSTRY_KEYWORDS = [
        'technology' => [
            'programming' => ['Python', 'Java', 'JavaScript', 'TypeScript', 'Go', 'Rust', 'C++', 'C#', 'Ruby', 'PHP'],
            'frameworks' => ['React', 'Angular', 'Vue', 'Node.js', 'Django', 'Flask', 'Spring', 'Laravel', 'Rails'],
            'cloud' => ['AWS', 'Azure', 'GCP', 'Docker', 'Kubernetes', 'Terraform', 'CloudFormation'],
            'databases' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch', 'DynamoDB'],
            'methodologies' => ['Agile', 'Scrum', 'Kanban', 'DevOps', 'CI/CD', 'TDD', 'Microservices'],
        ],
        'finance' => [
            'skills' => ['Financial Analysis', 'Risk Management', 'Compliance', 'Auditing', 'Forecasting'],
            'tools' => ['Excel', 'SQL', 'Bloomberg', 'SAP', 'QuickBooks', 'Tableau'],
            'certifications' => ['CFA', 'CPA', 'FRM', 'CAIA', 'CMA'],
        ],
        'healthcare' => [
            'skills' => ['Patient Care', 'Clinical Research', 'HIPAA', 'Electronic Health Records', 'Medical Coding'],
            'certifications' => ['RN', 'MD', 'CNA', 'CPR', 'BLS', 'ACLS'],
        ],
        'marketing' => [
            'skills' => ['SEO', 'SEM', 'Content Marketing', 'Social Media', 'Analytics', 'Brand Management'],
            'tools' => ['Google Analytics', 'HubSpot', 'Salesforce', 'Adobe Creative Suite', 'Mailchimp'],
        ],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Analyze job descriptions to extract key requirements, skills, and keywords for ATS optimization.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $jobDescription = $request['job_description'] ?? '';
        $cvContent = $request['cv_content'] ?? '';

        if (empty($jobDescription)) {
            return "Error: Please provide a job description to analyze.";
        }

        $result = "=== Job Description Analysis ===\n\n";

        // Extract key information
        $requirements = $this->extractRequirements($jobDescription);
        $skills = $this->extractSkills($jobDescription);
        $qualifications = $this->extractQualifications($jobDescription);

        $result .= "**Key Requirements:**\n";
        foreach ($requirements as $req) {
            $result .= "• {$req}\n";
        }
        $result .= "\n";

        $result .= "**Required Skills:**\n";
        foreach ($skills as $category => $items) {
            if (!empty($items)) {
                $result .= "  {$category}: " . implode(', ', $items) . "\n";
            }
        }
        $result .= "\n";

        $result .= "**Qualifications:**\n";
        foreach ($qualifications as $qual) {
            $result .= "• {$qual}\n";
        }
        $result .= "\n";

        // Compare with CV if provided
        if (!empty($cvContent)) {
            $result .= "=== CV Match Analysis ===\n\n";
            $match = $this->analyzeMatch($jobDescription, $cvContent);

            $result .= "**Match Score:** {$match['score']}%\n\n";

            $result .= "**Matched Keywords:**\n";
            foreach ($match['matched'] as $keyword) {
                $result .= "✓ {$keyword}\n";
            }
            $result .= "\n";

            $result .= "**Missing Keywords:**\n";
            foreach ($match['missing'] as $keyword) {
                $result .= "✗ {$keyword}\n";
            }
            $result .= "\n";

            $result .= "**Recommendations:**\n";
            foreach ($match['recommendations'] as $rec) {
                $result .= "• {$rec}\n";
            }
        }

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'job_description' => $schema->string()
                ->description('The job description text to analyze'),
            'cv_content' => $schema->string()
                ->description('Optional: Your current CV content for comparison'),
        ];
    }

    /**
     * Extract requirements from job description.
     */
    private function extractRequirements(string $jd): array
    {
        $requirements = [];
        $lines = explode("\n", $jd);

        foreach ($lines as $line) {
            $line = trim($line);
            // Look for requirement indicators
            if (preg_match('/^(requirement|qualification|must have|required|essential)/i', $line) ||
                preg_match('/^\d+\./', $line) ||
                preg_match('/^•/', $line)) {
                $requirements[] = $line;
            }
        }

        return array_slice($requirements, 0, 10);
    }

    /**
     * Extract skills from job description.
     */
    private function extractSkills(string $jd): array
    {
        $skills = [
            'technical' => [],
            'soft' => [],
            'tools' => [],
            'certifications' => [],
        ];

        $jdLower = strtolower($jd);

        // Extract technical skills
        foreach (self::INDUSTRY_KEYWORDS['technology']['programming'] as $skill) {
            if (stripos($jd, $skill) !== false) {
                $skills['technical'][] = $skill;
            }
        }

        // Extract soft skills
        $softSkills = ['leadership', 'communication', 'teamwork', 'problem-solving', 'analytical', 'detail-oriented'];
        foreach ($softSkills as $skill) {
            if (str_contains($jdLower, $skill)) {
                $skills['soft'][] = $skill;
            }
        }

        return $skills;
    }

    /**
     * Extract qualifications from job description.
     */
    private function extractQualifications(string $jd): array
    {
        $quals = [];

        // Look for education
        if (preg_match('/(bachelor|master|phd|degree|mba)/i', $jd)) {
            $quals[] = 'Education requirements found';
        }

        // Look for experience
        if (preg_match('/(\d+)\+?\s*years?\s*(of\s*)?experience/i', $jd, $matches)) {
            $quals[] = $matches[0];
        }

        // Look for certifications
        if (preg_match('/certification|certified/i', $jd)) {
            $quals[] = 'Certifications required or preferred';
        }

        return $quals;
    }

    /**
     * Analyze match between CV and job description.
     */
    private function analyzeMatch(string $jd, string $cv): array
    {
        $jdWords = $this->extractKeywords($jd);
        $cvWords = $this->extractKeywords($cv);

        $matched = array_intersect($jdWords, $cvWords);
        $missing = array_diff($jdWords, $cvWords);

        $score = empty($jdWords) ? 0 : round((count($matched) / count($jdWords)) * 100);

        $recommendations = [];
        if ($score < 50) {
            $recommendations[] = 'Add more relevant keywords from the job description';
        }
        if (count($missing) > 5) {
            $recommendations[] = 'Consider addressing key missing qualifications';
        }
        if (empty($recommendations)) {
            $recommendations[] = 'Good match! Tailor your summary to highlight these skills';
        }

        return [
            'score' => $score,
            'matched' => array_slice($matched, 0, 10),
            'missing' => array_slice($missing, 0, 10),
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Extract keywords from text.
     */
    private function extractKeywords(string $text): array
    {
        // Remove common words and extract meaningful keywords
        $commonWords = ['the', 'and', 'or', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'of', 'with'];
        $words = str_word_count(strtolower($text), 1);
        return array_diff($words, $commonWords);
    }
}

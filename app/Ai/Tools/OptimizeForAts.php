<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class OptimizeForAts implements Tool
{
    /**
     * Common ATS parsing issues.
     */
    private const ATS_ISSUES = [
        'formatting' => [
            'tables' => 'Tables may not be parsed correctly by ATS',
            'headers_footers' => 'Headers and footers are often ignored',
            'columns' => 'Multi-column layouts can confuse parsers',
            'images' => 'Images and graphics are not readable',
            'special_chars' => 'Special characters may not render properly',
        ],
        'content' => [
            'missing_keywords' => 'Job-specific keywords not found',
            'vague_titles' => 'Job titles are unclear or creative',
            'no_dates' => 'Missing or unclear employment dates',
            'contact_info' => 'Contact information not in standard format',
        ],
    ];

    /**
     * Standard ATS-friendly section headers.
     */
    private const STANDARD_HEADERS = [
        'work_experience' => ['Work Experience', 'Professional Experience', 'Employment History', 'Experience'],
        'education' => ['Education', 'Academic Background', 'Qualifications'],
        'skills' => ['Skills', 'Technical Skills', 'Core Competencies', 'Key Skills'],
        'certifications' => ['Certifications', 'Professional Certifications', 'Licenses'],
        'summary' => ['Summary', 'Professional Summary', 'Profile', 'Objective'],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Check your CV for ATS compatibility issues and provide optimization recommendations.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $cvContent = $request['cv_content'] ?? '';
        $jobDescription = $request['job_description'] ?? '';

        if (empty($cvContent)) {
            return 'Error: Please provide your CV content to analyze.';
        }

        $result = "=== ATS Compatibility Analysis ===\n\n";

        // Check formatting
        $formattingIssues = $this->checkFormatting($cvContent);
        $result .= "**Formatting Check:**\n";
        if (empty($formattingIssues)) {
            $result .= "✓ No major formatting issues detected\n";
        } else {
            foreach ($formattingIssues as $issue) {
                $result .= "✗ {$issue}\n";
            }
        }
        $result .= "\n";

        // Check section headers
        $headerCheck = $this->checkHeaders($cvContent);
        $result .= "**Section Headers:**\n";
        foreach ($headerCheck as $check) {
            $result .= "{$check}\n";
        }
        $result .= "\n";

        // Check content
        $contentIssues = $this->checkContent($cvContent);
        $result .= "**Content Analysis:**\n";
        foreach ($contentIssues as $issue) {
            $result .= "{$issue}\n";
        }
        $result .= "\n";

        // Job description comparison
        if (! empty($jobDescription)) {
            $result .= "=== Job Description Match ===\n\n";
            $match = $this->compareWithJobDescription($cvContent, $jobDescription);
            $result .= "Keyword Match: {$match['percentage']}%\n";
            $result .= 'Missing Keywords: '.implode(', ', array_slice($match['missing'], 0, 8))."\n\n";
        }

        // Recommendations
        $result .= "=== Recommendations ===\n\n";
        $recommendations = $this->generateRecommendations($formattingIssues, $contentIssues);
        foreach ($recommendations as $rec) {
            $result .= "• {$rec}\n";
        }

        return $result;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'cv_content' => $schema->string()
                ->description('Your CV content to analyze'),
            'job_description' => $schema->string()
                ->description('Optional: Job description to compare against'),
        ];
    }

    /**
     * Check for formatting issues.
     */
    private function checkFormatting(string $cv): array
    {
        $issues = [];

        if (preg_match('/<table|<TABLE/', $cv)) {
            $issues[] = 'Contains tables - may cause parsing issues';
        }

        if (preg_match('/<header|<footer|<HEADER|<FOOTER/', $cv)) {
            $issues[] = 'Contains headers/footers - content may be ignored';
        }

        if (preg_match('/column|column-count|float:\s*left/i', $cv)) {
            $issues[] = 'Multi-column layout detected - use single column';
        }

        if (preg_match('/<img|<IMG/', $cv)) {
            $issues[] = 'Contains images - not readable by ATS';
        }

        return $issues;
    }

    /**
     * Check section headers.
     */
    private function checkHeaders(string $cv): array
    {
        $checks = [];
        $cvLower = strtolower($cv);

        $foundWork = false;
        foreach (self::STANDARD_HEADERS['work_experience'] as $header) {
            if (str_contains($cvLower, strtolower($header))) {
                $foundWork = true;
                $checks[] = "✓ Found work experience section: '{$header}'";
                break;
            }
        }
        if (! $foundWork) {
            $checks[] = "✗ Work experience section not found (use 'Work Experience' or 'Professional Experience')";
        }

        $foundEducation = false;
        foreach (self::STANDARD_HEADERS['education'] as $header) {
            if (str_contains($cvLower, strtolower($header))) {
                $foundEducation = true;
                $checks[] = "✓ Found education section: '{$header}'";
                break;
            }
        }
        if (! $foundEducation) {
            $checks[] = '⚠ Education section not found';
        }

        return $checks;
    }

    /**
     * Check content issues.
     */
    private function checkContent(string $cv): array
    {
        $issues = [];

        // Check for dates
        if (! preg_match('/\d{4}/', $cv)) {
            $issues[] = '✗ No dates found - add employment dates';
        } else {
            $issues[] = '✓ Dates found';
        }

        // Check for contact info
        if (! preg_match('/[\w.-]+@[\w.-]+\.\w+/', $cv)) {
            $issues[] = '✗ Email address not found';
        } else {
            $issues[] = '✓ Email address found';
        }

        // Check for phone
        if (! preg_match('/[\d\s\-\(\)\+]{10,}/', $cv)) {
            $issues[] = '⚠ Phone number not clearly found';
        }

        // Check length
        $wordCount = str_word_count(strip_tags($cv));
        if ($wordCount < 200) {
            $issues[] = "⚠ CV is quite short ({$wordCount} words) - consider adding more detail";
        } elseif ($wordCount > 800) {
            $issues[] = "⚠ CV is quite long ({$wordCount} words) - consider condensing";
        } else {
            $issues[] = "✓ Good length ({$wordCount} words)";
        }

        return $issues;
    }

    /**
     * Compare CV with job description.
     */
    private function compareWithJobDescription(string $cv, string $jd): array
    {
        $jdWords = $this->extractImportantWords($jd);
        $cvWords = $this->extractImportantWords($cv);

        $matched = array_intersect($jdWords, $cvWords);
        $missing = array_diff($jdWords, $cvWords);

        $percentage = empty($jdWords) ? 0 : round((count($matched) / count($jdWords)) * 100);

        return [
            'percentage' => $percentage,
            'matched' => $matched,
            'missing' => $missing,
        ];
    }

    /**
     * Extract important words from text.
     */
    private function extractImportantWords(string $text): array
    {
        // Extract words that look like skills/requirements
        preg_match_all('/\b[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\b/', $text, $matches);

        return array_unique(array_map('strtolower', $matches[0]));
    }

    /**
     * Generate recommendations.
     */
    private function generateRecommendations(array $formattingIssues, array $contentIssues): array
    {
        $recommendations = [];

        if (! empty($formattingIssues)) {
            $recommendations[] = 'Fix formatting issues - use a simple, single-column layout';
        }

        if (in_array('✗ Work experience section not found', $contentIssues)) {
            $recommendations[] = 'Add a clearly labeled "Work Experience" section';
        }

        $recommendations[] = 'Use standard job titles rather than creative ones';
        $recommendations[] = 'Include keywords from the job description naturally';
        $recommendations[] = 'Save as .docx or .pdf for best compatibility';
        $recommendations[] = 'Test your CV by uploading it to the company\'s application system';

        return $recommendations;
    }
}

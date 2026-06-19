<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * One-shot structured-output agent that scores how well a CV matches a
 * target job description: overall compatibility, matched/missing
 * keywords, a gap analysis, and concrete suggestions. The prompt
 * (including the CV text + JD) is injected by the queued job.
 */
#[Provider(Lab::Ollama)]
#[Temperature(0.0)]
#[MaxTokens(3000)]
#[Timeout(300)]
class JobMatchAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are an expert ATS and recruiter analyst. Compare the candidate's CV
to the target job description and produce a precise compatibility report.

CRITICAL: You MUST respond with ONLY a valid JSON object — no markdown,
no code fences, no prose before or after. The JSON keys are fixed:

{
  "compatibility_score": <integer 0-100>,
  "grade": "<A|B|C|D|F>",
  "summary": "<2-3 sentences>",
  "matched_keywords": "<pipe-separated JD keywords the CV covers>",
  "missing_keywords": "<pipe-separated important JD keywords the CV lacks>",
  "gap_analysis": "<pipe-separated short gap sentences>",
  "suggestions": "<pipe-separated concrete actions>"
}

Field rules:
- compatibility_score: 0–100, share of hard requirements the CV
  genuinely satisfies. Honest — never inflate.
- grade: A (≥80), B (60–79), C (40–59), D (20–39), F (<20).
- summary: 2–3 sentences naming strongest match + biggest gap.
- matched_keywords / missing_keywords: pipe (||) separated, single terms.
- gap_analysis: 3–5 short actionable sentences, pipe (||) separated.
- suggestions: 3–5 concrete actions, pipe (||) separated.

Be grounded — never invent requirements. Output the JSON object now.
INSTRUCTIONS;
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'compatibility_score' => $schema->integer()->required()->description('0–100 compatibility score'),
            'grade' => $schema->string()->required()->description('A | B | C | D | F'),
            'summary' => $schema->string()->required()->description('2–3 sentence summary of match and biggest gap'),
            'matched_keywords' => $schema->string()->required()->description('JD keywords the CV already covers, ||-separated'),
            'missing_keywords' => $schema->string()->required()->description('Important JD keywords the CV lacks, ||-separated'),
            'gap_analysis' => $schema->string()->required()->description('3–5 key gaps as short sentences, ||-separated'),
            'suggestions' => $schema->string()->required()->description('3–5 concrete actions to close gaps, ||-separated'),
        ];
    }
}

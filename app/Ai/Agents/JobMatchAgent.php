<?php

namespace App\Ai\Agents;

use App\Support\AiLocaleDirective;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * One-shot agent that scores how well a CV matches a target job
 * description. Returns plain text containing a JSON object (NOT
 * HasStructuredOutput — the structured decoder crashes on truncated
 * responses). The job parses the JSON defensively.
 */
#[Provider(Lab::Ollama)]
#[Temperature(0.0)]
#[MaxTokens(4096)]
#[Timeout(300)]
class JobMatchAgent implements Agent
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        $base = <<<'INSTRUCTIONS'
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

        return $base.AiLocaleDirective::for();
    }
}

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

Rules:
- compatibility_score: 0–100, the share of the job's hard requirements
  the CV genuinely satisfies. Be honest — do not inflate.
- grade: A (≥80), B (60–79), C (40–59), D (20–39), F (<20).
- summary: 2–3 sentences naming the strongest match and the biggest gap.
- matched_keywords: concrete terms from the JD that the CV already shows
  (skills, tools, domain knowledge). Only include genuinely present ones.
- missing_keywords: important JD terms/concepts the CV lacks.
- gap_analysis (list of strings): the 3–5 most important gaps, each a
  short actionable sentence (e.g. "JD asks for Kubernetes; CV only
  mentions Docker").
- suggestions (list of strings): 3–5 specific, concrete actions the
  candidate can take to close the gaps (add a skill, reframe an
  experience, quantify an achievement).

Lists are pipe-delimited (||) strings because of the structured-output
transport. Be specific and grounded — never invent requirements.
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

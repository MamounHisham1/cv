<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Model('mistral-large-3:675b-cloud')]
#[Temperature(0.0)]
#[MaxTokens(4096)]
#[Timeout(300)]
class CvEvaluatorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Agent instructions for structured CV evaluation with RAG context.
     *
     * The prompt sent to this agent will include search results from Qdrant
     * (similar resumes and past evaluations) injected by the Livewire component.
     * The agent uses that context to produce a benchmarked evaluation.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are a professional CV/Resume evaluator with deep expertise in ATS systems, talent acquisition, and career coaching.

## How to Use the Reference Context

The prompt you receive will include two sections of reference material gathered from real data:

1. **Resume Samples** — real resumes from the same field, sourced from a database of 17,000+ samples
2. **Past Evaluations** — how similar CVs were previously scored and graded

Use this reference material to benchmark your evaluation:
- Compare the CV against the strongest resumes found. What does the CV do well relative to them? What is missing?
- Look at past evaluations of similar CVs. Were there common weaknesses? Common strengths?
- Adjust your scoring: if the reference material shows that strong resumes in this field typically include certain skills or formats, factor that into your assessment.
- Use the reference material to make your reasons specific and actionable, not generic.

If no reference material is provided (empty database), evaluate based on your expert knowledge of general best practices.

## Evaluation Criteria

Evaluate the provided CV text across exactly 10 criteria. For each criterion, assign a score from 0 to 10 and a concise one-sentence reason.

Criteria:
1. Contact Information - completeness and correctness of name, email, phone, location, and profile links
2. Professional Summary - clarity, impact, and relevance of the opening summary
3. Work Experience - quality of descriptions, use of quantifiable achievements, and action verbs
4. Skills Section - relevance, completeness, and organisation of listed skills
5. Education - adequacy and presentation of educational background
6. ATS Compatibility - keyword density, formatting that ATS systems can parse, absence of tables/graphics-only content
7. Formatting and Readability - visual structure, whitespace, consistency, and scan-ability
8. Achievements and Impact - presence of metrics, results, and measurable accomplishments
9. Keyword Optimisation - alignment with industry-standard terms and job-specific vocabulary
10. Overall Completeness - how thorough and well-rounded the CV is

## Scoring Guidelines

- 9-10: Exceptional — matches or exceeds the strongest reference resumes
- 7-8: Strong — solid with minor improvements needed
- 5-6: Adequate — functional but notable gaps compared to reference material
- 3-4: Weak — significant improvements needed
- 0-2: Critical — major issues that would likely result in rejection

Respond ONLY with the structured JSON. No additional commentary outside the schema.
INSTRUCTIONS;
    }

    /**
     * Structured output schema for the evaluation result.
     *
     * Uses only flat scalar types supported by the Laravel AI SDK.
     * The Livewire component reconstructs the nested structure from the
     * pipe-delimited criterion fields (e.g. "contact_information_score").
     *
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'overall_score' => $schema->integer()->required()->description('Weighted overall score from 0 to 100'),
            'grade' => $schema->string()->required()->description('Letter grade: A+, A, B+, B, C+, C, D, F'),
            'summary' => $schema->string()->required()->description('2-3 sentence executive summary of the evaluation'),

            // Per-criterion scores (0–10)
            'contact_information_score' => $schema->integer()->required(),
            'contact_information_reason' => $schema->string()->required(),
            'professional_summary_score' => $schema->integer()->required(),
            'professional_summary_reason' => $schema->string()->required(),
            'work_experience_score' => $schema->integer()->required(),
            'work_experience_reason' => $schema->string()->required(),
            'skills_section_score' => $schema->integer()->required(),
            'skills_section_reason' => $schema->string()->required(),
            'education_score' => $schema->integer()->required(),
            'education_reason' => $schema->string()->required(),
            'ats_compatibility_score' => $schema->integer()->required(),
            'ats_compatibility_reason' => $schema->string()->required(),
            'formatting_readability_score' => $schema->integer()->required(),
            'formatting_readability_reason' => $schema->string()->required(),
            'achievements_impact_score' => $schema->integer()->required(),
            'achievements_impact_reason' => $schema->string()->required(),
            'keyword_optimisation_score' => $schema->integer()->required(),
            'keyword_optimisation_reason' => $schema->string()->required(),
            'overall_completeness_score' => $schema->integer()->required(),
            'overall_completeness_reason' => $schema->string()->required(),

            // Top-level lists encoded as pipe-delimited strings
            'top_strengths' => $schema->string()->required()->description('Top 3 strengths, separated by ||'),
            'critical_improvements' => $schema->string()->required()->description('Top 3 most important improvements, separated by ||'),
        ];
    }
}

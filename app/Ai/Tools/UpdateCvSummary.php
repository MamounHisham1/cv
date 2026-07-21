<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvSummary implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update the professional summary section of the user\'s CV. Use this when the user asks to rewrite, improve, or change their CV summary. TRUTHFULNESS: The summary may only rephrase facts already in the user\'s CV or that the user explicitly stated. Never invent years of experience, metrics, certifications, or specializations. If the facts needed for a strong summary are missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $summary = trim((string) ($request['summary'] ?? ''));
        if ($summary === '') {
            return 'No summary provided. Please provide the new professional summary text.';
        }

        // Stage the change for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeCvField(
            field: 'summary',
            before: ['summary' => $this->cv->summary ?? ''],
            after: ['summary' => $summary],
            label: 'Professional summary',
            summary: 'Rewrite professional summary.',
        );

        return "STAGED for review (NOT applied): professional summary.\n".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            "In your reply, describe this as a PROPOSED change awaiting approval — do NOT say it was applied, made, or completed.\n\n".
            "Proposed summary:\n{$summary}";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()
                ->description('The new professional summary text for the CV (2-4 sentences)'),
        ];
    }
}

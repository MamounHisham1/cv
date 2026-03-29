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
        return 'Update the professional summary section of the user\'s CV. Use this when the user asks to rewrite, improve, or change their CV summary.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $summary = $request['summary'] ?? '';

        if (empty(trim($summary))) {
            return 'No summary provided. Please provide the new professional summary text.';
        }

        $this->cv->update(['summary' => trim($summary)]);

        return "Professional summary updated successfully!\n\nNew summary:\n{$summary}";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()
                ->description('The new professional summary text for the CV (2-4 sentences)'),
        ];
    }
}

<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvLanguage implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add a language to the user\'s CV. Use this when the user mentions a language they speak or want to list on their CV.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $language = $request['language'] ?? '';

        if (empty(trim($language))) {
            return 'Language name is required.';
        }

        $maxSort = $this->cv->languages()->max('sort_order') ?? 0;
        $proficiency = $request['proficiency'] ?? 'intermediate';

        $attributes = [
            'language' => trim($language),
            'proficiency' => $proficiency,
            'sort_order' => $maxSort + 1,
        ];

        // Stage the addition for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeCreate(
            section: 'languages',
            after: $attributes,
            label: "{$language} ({$proficiency})",
            summary: 'Add language.',
        );

        return "STAGED for review (NOT applied): new language \"{$language}\" ({$proficiency}). ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED addition awaiting approval — do NOT say it was added or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'language' => $schema->string()->description('Language name (e.g., "English", "Arabic", "French")'),
            'proficiency' => $schema->string()->description('Proficiency level: beginner, elementary, intermediate, upper_intermediate, advanced, fluent, or native'),
        ];
    }
}

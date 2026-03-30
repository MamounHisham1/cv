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

        $this->cv->languages()->create([
            'language' => trim($language),
            'proficiency' => $request['proficiency'] ?? 'intermediate',
            'sort_order' => $maxSort + 1,
        ]);

        $proficiency = $request['proficiency'] ?? 'intermediate';

        return "\"{$language}\" ({$proficiency}) added to CV.";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'language' => $schema->string()->description('Language name (e.g., "English", "Arabic", "French")'),
            'proficiency' => $schema->string()->description('Proficiency level: beginner, elementary, intermediate, upper_intermediate, advanced, fluent, or native'),
        ];
    }
}

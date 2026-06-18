<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvLanguage implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing language entry on the user\'s CV (the language name or proficiency). Always call read_cv_data first to get the language id, then pass only the fields you want to change.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No language id provided. Call read_cv_data first to find the language id.';
        }

        $lang = $this->cv->languages()->find($id);
        if (! $lang) {
            return "No language found with id {$id} on this CV.";
        }

        $updates = [];

        if (isset($request['language']) && trim((string) $request['language']) !== '') {
            $updates['language'] = trim((string) $request['language']);
        }

        if (isset($request['proficiency']) && trim((string) $request['proficiency']) !== '') {
            $updates['proficiency'] = trim((string) $request['proficiency']);
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        $lang->update($updates);

        return "Language \"{$lang->language}\" updated. Changed: ".implode(', ', array_keys($updates)).'.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the language entry to update (from read_cv_data)'),
            'language' => $schema->string()->description('New language name'),
            'proficiency' => $schema->string()->description('New proficiency level: beginner, elementary, intermediate, upper_intermediate, advanced, fluent, or native'),
        ];
    }
}

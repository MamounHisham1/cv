<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvEducation implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing education entry on the user\'s CV. Always call read_cv_data first to get the entry id, then pass only the fields you want to change.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No education id provided. Call read_cv_data first to find the entry id.';
        }

        $edu = $this->cv->educations()->find($id);
        if (! $edu) {
            return "No education found with id {$id} on this CV.";
        }

        $updates = [];

        foreach (['institution', 'degree', 'field_of_study', 'location', 'description'] as $field) {
            if (isset($request[$field]) && trim((string) $request[$field]) !== '') {
                $updates[$field] = trim((string) $request[$field]);
            }
        }

        foreach (['start_date', 'end_date'] as $field) {
            if (isset($request[$field])) {
                $updates[$field] = $request[$field] ?: null;
            }
        }

        if (isset($request['is_current']) && $request['is_current']) {
            $updates['is_current'] = true;
            $updates['end_date'] = null;
        } elseif (isset($request['is_current']) && ! $request['is_current']) {
            $updates['is_current'] = false;
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        $edu->update($updates);

        return "Education \"{$edu->degree}\" at {$edu->institution} updated. Changed: ".implode(', ', array_keys($updates)).'.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the education entry to update (from read_cv_data)'),
            'institution' => $schema->string()->description('New institution name'),
            'degree' => $schema->string()->description('New degree type'),
            'field_of_study' => $schema->string()->description('New field of study'),
            'location' => $schema->string()->description('New institution location'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format'),
            'is_current' => $schema->boolean()->description('Whether currently studying'),
            'description' => $schema->string()->description('New description (GPA, coursework, honors)'),
        ];
    }
}

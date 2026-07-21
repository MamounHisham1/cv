<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvProject implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing project on the user\'s CV. Always call read_cv_data first to get the project id, then pass only the fields you want to change. Useful for improving descriptions, achievements, or adding links. TRUTHFULNESS: Never include metrics, dates, technologies, or achievements the user did not explicitly provide. If a needed fact is missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No project id provided. Call read_cv_data first to find the project id.';
        }

        $project = $this->cv->projects()->find($id);
        if (! $project) {
            return "No project found with id {$id} on this CV.";
        }

        $updates = [];

        foreach (['name', 'description', 'project_url', 'github_url'] as $field) {
            if (isset($request[$field]) && trim((string) $request[$field]) !== '') {
                $updates[$field] = trim((string) $request[$field]);
            }
        }

        foreach (['start_date', 'end_date'] as $field) {
            if (isset($request[$field])) {
                $updates[$field] = $request[$field] ?: null;
            }
        }

        if (isset($request['achievements'])) {
            $updates['key_achievements'] = is_array($request['achievements'])
                ? array_values(array_filter($request['achievements'], fn ($a) => ! empty(trim((string) $a))))
                : [];
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        // Stage the change for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeUpdate(
            section: 'projects',
            recordId: $project->id,
            before: $project->toArray(),
            after: $updates,
            label: $project->name,
            summary: 'Update project: '.implode(', ', array_keys($updates)).'.',
        );

        $changed = implode(', ', array_keys($updates));

        return "STAGED for review (NOT applied): project \"{$project->name}\" — {$changed}. ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED change awaiting approval — do NOT say it was applied, made, or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the project to update (from read_cv_data)'),
            'name' => $schema->string()->description('New project name'),
            'description' => $schema->string()->description('New project description'),
            'achievements' => $schema->array()->description('Replace the key achievements list'),
            'project_url' => $schema->string()->description('Live project URL'),
            'github_url' => $schema->string()->description('GitHub repository URL'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format'),
        ];
    }
}

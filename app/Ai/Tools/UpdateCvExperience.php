<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvExperience implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing work experience entry on the user\'s CV. Always call read_cv_data first to get the entry id, then pass only the fields you want to change. Useful for fixing typos, improving descriptions/achievements, or correcting dates. TRUTHFULNESS: Never invent metrics, dates, technologies, or achievements the user did not explicitly provide. If a needed fact is missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No experience id provided. Call read_cv_data first to find the entry id.';
        }

        $exp = $this->cv->experiences()->find($id);
        if (! $exp) {
            return "No experience found with id {$id} on this CV.";
        }

        $updates = [];

        foreach (['title', 'company', 'location', 'description'] as $field) {
            if (isset($request[$field]) && trim((string) $request[$field]) !== '') {
                $updates[$field] = trim((string) $request[$field]);
            }
        }

        if (isset($request['start_date'])) {
            $updates['start_date'] = $request['start_date'] ?: null;
        }

        if (isset($request['is_current']) && $request['is_current']) {
            $updates['is_current'] = true;
            $updates['end_date'] = null;
        } elseif (isset($request['end_date'])) {
            $updates['end_date'] = $request['end_date'] ?: null;
            if ($request['end_date']) {
                $updates['is_current'] = false;
            }
        }

        if (isset($request['is_current']) && ! $request['is_current']) {
            $updates['is_current'] = false;
        }

        if (isset($request['achievements'])) {
            $updates['achievements'] = is_array($request['achievements'])
                ? array_values(array_filter($request['achievements'], fn ($a) => ! empty(trim((string) $a))))
                : [];
        }

        if (isset($request['technologies'])) {
            $updates['technologies'] = is_array($request['technologies'])
                ? array_values(array_filter($request['technologies'], fn ($t) => ! empty(trim((string) $t))))
                : [];
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        // Stage the change for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeUpdate(
            section: 'experiences',
            recordId: $exp->id,
            before: $exp->toArray(),
            after: $updates,
            label: "\"{$exp->title}\" at {$exp->company}",
            summary: 'Update work experience: '.implode(', ', array_keys($updates)).'.',
        );

        $changed = implode(', ', array_keys($updates));

        return "STAGED for review (NOT applied): work experience \"{$exp->title}\" at {$exp->company} — {$changed}. ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED change awaiting approval — do NOT say it was applied, made, or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the experience entry to update (from read_cv_data)'),
            'title' => $schema->string()->description('New job title'),
            'company' => $schema->string()->description('New company name'),
            'location' => $schema->string()->description('New job location'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format'),
            'is_current' => $schema->boolean()->description('Whether this is the current job'),
            'description' => $schema->string()->description('New job description'),
            'achievements' => $schema->array()->description('Replace the achievements list (e.g., ["Reduced costs by 30%"])'),
            'technologies' => $schema->array()->description('Replace the technologies list (e.g., ["Python", "Docker"])'),
        ];
    }
}

<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvCertification implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update an existing certification on the user\'s CV. Always call read_cv_data first to get the certification id, then pass only the fields you want to change. TRUTHFULNESS: Never invent dates, credential IDs, or organizations the user did not explicitly provide. If a needed fact is missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No certification id provided. Call read_cv_data first to find the certification id.';
        }

        $cert = $this->cv->certifications()->find($id);
        if (! $cert) {
            return "No certification found with id {$id} on this CV.";
        }

        $updates = [];

        foreach (['name', 'issuing_organization', 'credential_id', 'credential_url'] as $field) {
            if (isset($request[$field]) && trim((string) $request[$field]) !== '') {
                $updates[$field] = trim((string) $request[$field]);
            }
        }

        foreach (['issue_date', 'expiration_date'] as $field) {
            if (isset($request[$field])) {
                $updates[$field] = $request[$field] ?: null;
            }
        }

        if (empty($updates)) {
            return 'No fields provided to update.';
        }

        // Stage the change for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeUpdate(
            section: 'certifications',
            recordId: $cert->id,
            before: $cert->toArray(),
            after: $updates,
            label: $cert->name,
            summary: 'Update certification: '.implode(', ', array_keys($updates)).'.',
        );

        $changed = implode(', ', array_keys($updates));

        return "STAGED for review (NOT applied): certification \"{$cert->name}\" — {$changed}. ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED change awaiting approval — do NOT say it was applied, made, or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the certification to update (from read_cv_data)'),
            'name' => $schema->string()->description('New certification name'),
            'issuing_organization' => $schema->string()->description('New issuing organization'),
            'issue_date' => $schema->string()->description('New issue date in YYYY-MM-DD format'),
            'expiration_date' => $schema->string()->description('New expiration date in YYYY-MM-DD format'),
            'credential_id' => $schema->string()->description('New credential ID'),
            'credential_url' => $schema->string()->description('New verification URL'),
        ];
    }
}

<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeleteCvCertification implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Delete a certification from the user\'s CV. ALWAYS call read_cv_data first to confirm the id, and ALWAYS confirm with the user which certification you are about to delete before calling this tool. Deletion is permanent.';
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

        $label = $cert->name;

        // Stage the deletion for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeDelete(
            section: 'certifications',
            recordId: $cert->id,
            before: $cert->toArray(),
            label: $label,
            summary: 'Delete certification.',
        );

        return "STAGED for review (NOT applied): delete certification \"{$label}\". ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED deletion awaiting approval — do NOT say it was deleted or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the certification to delete (from read_cv_data)'),
        ];
    }
}

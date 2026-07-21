<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeleteCvProject implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Delete a project from the user\'s CV. ALWAYS call read_cv_data first to confirm the id, and ALWAYS confirm with the user which project you are about to delete before calling this tool. Deletion is permanent.';
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

        $label = $project->name;

        // Stage the deletion for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeDelete(
            section: 'projects',
            recordId: $project->id,
            before: $project->toArray(),
            label: $label,
            summary: 'Delete project.',
        );

        return "STAGED for review (NOT applied): delete project \"{$label}\". ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED deletion awaiting approval — do NOT say it was deleted or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the project to delete (from read_cv_data)'),
        ];
    }
}

<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeleteCvEducation implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Delete an education entry from the user\'s CV. ALWAYS call read_cv_data first to confirm the id, and ALWAYS confirm with the user which entry you are about to delete before calling this tool. Deletion is permanent.';
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

        $label = "{$edu->degree} at {$edu->institution}";
        $edu->delete();

        $this->resequence();

        return "Deleted education: \"{$label}\".";
    }

    private function resequence(): void
    {
        $this->cv->educations()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(fn ($item, $i) => $item->update(['sort_order' => $i]));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the education entry to delete (from read_cv_data)'),
        ];
    }
}

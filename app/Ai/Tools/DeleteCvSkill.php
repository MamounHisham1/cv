<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeleteCvSkill implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Delete a skill from the user\'s CV. ALWAYS call read_cv_data first to confirm the id, and ALWAYS confirm with the user which skill you are about to delete before calling this tool. Deletion is permanent.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $id = $request['id'] ?? null;
        if (! $id) {
            return 'No skill id provided. Call read_cv_data first to find the skill id.';
        }

        $skill = $this->cv->skills()->find($id);
        if (! $skill) {
            return "No skill found with id {$id} on this CV.";
        }

        $label = $skill->name;
        $skill->delete();

        $this->resequence();

        return "Deleted skill: \"{$label}\".";
    }

    private function resequence(): void
    {
        $this->cv->skills()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(fn ($item, $i) => $item->update(['sort_order' => $i]));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the skill to delete (from read_cv_data)'),
        ];
    }
}

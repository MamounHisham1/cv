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
        $cert->delete();

        $this->resequence();

        return "Deleted certification: \"{$label}\".";
    }

    private function resequence(): void
    {
        $this->cv->certifications()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(fn ($item, $i) => $item->update(['sort_order' => $i]));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The id of the certification to delete (from read_cv_data)'),
        ];
    }
}

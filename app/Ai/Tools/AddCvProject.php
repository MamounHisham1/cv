<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvProject implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add a project to the user\'s CV. Use this when the user mentions a personal, academic, or professional project they want to include.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $name = $request['name'] ?? '';

        if (empty(trim($name))) {
            return 'Project name is required.';
        }

        $maxSort = $this->cv->projects()->max('sort_order') ?? 0;

        $this->cv->projects()->create([
            'name' => trim($name),
            'description' => trim((string) ($request['description'] ?? '')),
            'key_achievements' => $request['achievements'] ?? [],
            'project_url' => $request['project_url'] ?? null,
            'github_url' => $request['github_url'] ?? null,
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['end_date'] ?? null,
            'sort_order' => $maxSort + 1,
        ]);

        return "Project \"{$name}\" added to CV.";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Project name'),
            'description' => $schema->string()->description('Project description explaining what it does'),
            'achievements' => $schema->array()->description('List of key achievements or outcomes (e.g., ["Reduced load time by 50%"])'),
            'project_url' => $schema->string()->description('Live project URL if available'),
            'github_url' => $schema->string()->description('GitHub repository URL if available'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format (omit if ongoing)'),
        ];
    }
}

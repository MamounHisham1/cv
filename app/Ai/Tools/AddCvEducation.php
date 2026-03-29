<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvEducation implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add an education entry to the user\'s CV. Use this when the user mentions a degree, university, or educational program.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $institution = $request['institution'] ?? '';
        $degree = $request['degree'] ?? '';

        if (empty(trim($institution)) || empty(trim($degree))) {
            return 'Institution and degree are required to add an education entry.';
        }

        $maxSort = $this->cv->educations()->max('sort_order') ?? 0;

        $this->cv->educations()->create([
            'institution' => trim($institution),
            'degree' => trim($degree),
            'field_of_study' => trim((string) ($request['field_of_study'] ?? '')),
            'location' => trim((string) ($request['location'] ?? '')),
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['end_date'] ?? null,
            'is_current' => $request['is_current'] ?? false,
            'description' => trim((string) ($request['description'] ?? '')),
            'sort_order' => $maxSort + 1,
        ]);

        return "Education added: {$degree} at {$institution}.";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'institution' => $schema->string()->description('University or institution name'),
            'degree' => $schema->string()->description('Degree type (e.g., "Bachelor of Science", "Master of Engineering")'),
            'field_of_study' => $schema->string()->description('Field of study (e.g., "Computer Science", "Software Engineering")'),
            'location' => $schema->string()->description('Institution location'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format (omit if currently studying)'),
            'is_current' => $schema->boolean()->description('Whether currently studying'),
            'description' => $schema->string()->description('Additional details like GPA, relevant coursework, or honors'),
        ];
    }
}

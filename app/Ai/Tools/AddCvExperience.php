<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvExperience implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add a work experience entry to the user\'s CV. Use this when the user wants to add a job, internship, or freelance work. TRUTHFULNESS: Never invent metrics, dates, technologies, or achievements the user did not explicitly provide. If a needed fact is missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $title = $request['title'] ?? '';
        $company = $request['company'] ?? '';

        if (empty(trim($title)) || empty(trim($company))) {
            return 'Job title and company name are required to add an experience.';
        }

        $maxSort = $this->cv->experiences()->max('sort_order') ?? 0;

        $attributes = [
            'title' => trim($title),
            'company' => trim($company),
            'location' => trim((string) ($request['location'] ?? '')),
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['is_current'] ? null : ($request['end_date'] ?? null),
            'is_current' => $request['is_current'] ?? false,
            'description' => trim((string) ($request['description'] ?? '')),
            'achievements' => $request['achievements'] ?? [],
            'technologies' => $request['technologies'] ?? [],
            'sort_order' => $maxSort + 1,
        ];

        // Stage the addition for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeCreate(
            section: 'experiences',
            after: $attributes,
            label: "\"{$title}\" at {$company}",
            summary: 'Add work experience.',
        );

        return "STAGED for review (NOT applied): new work experience \"{$title}\" at {$company}. ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED addition awaiting approval — do NOT say it was added or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Job title (e.g., "Software Engineer", "Backend Developer")'),
            'company' => $schema->string()->description('Company name'),
            'location' => $schema->string()->description('Job location (city, country or remote)'),
            'start_date' => $schema->string()->description('Start date in YYYY-MM-DD format'),
            'end_date' => $schema->string()->description('End date in YYYY-MM-DD format (omit if currently working)'),
            'is_current' => $schema->boolean()->description('Whether this is the current job'),
            'description' => $schema->string()->description('Job description with responsibilities and duties'),
            'achievements' => $schema->array()->description('List of key achievements (e.g., ["Reduced costs by 30%", "Led team of 5"])'),
            'technologies' => $schema->array()->description('List of technologies used (e.g., ["Python", "Docker", "SQL"])'),
        ];
    }
}

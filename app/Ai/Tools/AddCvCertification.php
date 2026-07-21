<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class AddCvCertification implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Add a certification to the user\'s CV. Use this when the user mentions a professional certification they hold (e.g., AWS, Azure, Google Cloud, PMP, etc.). TRUTHFULNESS: Never invent dates, credential IDs, or organizations the user did not explicitly provide. If a needed fact is missing, call ask_clarifying_questions first instead of guessing.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $name = $request['name'] ?? '';
        $organization = $request['issuing_organization'] ?? '';

        if (empty(trim($name)) || empty(trim($organization))) {
            return 'Certification name and issuing organization are required.';
        }

        $maxSort = $this->cv->certifications()->max('sort_order') ?? 0;

        $attributes = [
            'name' => trim($name),
            'issuing_organization' => trim($organization),
            'issue_date' => $request['issue_date'] ?? null,
            'expiration_date' => $request['expiration_date'] ?? null,
            'credential_id' => $request['credential_id'] ?? null,
            'credential_url' => $request['credential_url'] ?? null,
            'sort_order' => $maxSort + 1,
        ];

        // Stage the addition for user review — do NOT mutate the CV yet.
        $this->proposedChanges()->proposeCreate(
            section: 'certifications',
            after: $attributes,
            label: "{$name} ({$organization})",
            summary: 'Add certification.',
        );

        return "STAGED for review (NOT applied): new certification \"{$name}\" from {$organization}. ".
            'The CV is unchanged. The user must approve this in the review card before it takes effect. '.
            'In your reply, describe this as a PROPOSED addition awaiting approval — do NOT say it was added or completed.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Certification name (e.g., "AWS Certified Solutions Architect")'),
            'issuing_organization' => $schema->string()->description('Issuing organization (e.g., "Amazon Web Services", "Microsoft")'),
            'issue_date' => $schema->string()->description('Date obtained in YYYY-MM-DD format'),
            'expiration_date' => $schema->string()->description('Expiration date in YYYY-MM-DD format (omit if no expiration)'),
            'credential_id' => $schema->string()->description('Credential or certification ID'),
            'credential_url' => $schema->string()->description('URL to verify the certification'),
        ];
    }
}

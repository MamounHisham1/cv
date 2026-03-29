<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateCvPersonalInfo implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Update personal information fields on the user\'s CV such as name, email, phone, location, LinkedIn, GitHub, or website. Use this when the user asks to change their personal details.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found to update. Please ask the user to create a CV first.';
        }

        $current = $this->cv->personal_info ?? [];

        $fieldMap = [
            'first_name' => $request['first_name'] ?? null,
            'last_name' => $request['last_name'] ?? null,
            'email' => $request['email'] ?? null,
            'phone' => $request['phone'] ?? null,
            'location' => $request['location'] ?? null,
            'linkedin' => $request['linkedin'] ?? null,
            'website' => $request['website'] ?? null,
            'github' => $request['github'] ?? null,
        ];

        $updated = [];
        foreach ($fieldMap as $field => $value) {
            if ($value !== null && trim((string) $value) !== '') {
                $current[$field] = trim((string) $value);
                $updated[] = $field;
            }
        }

        if (empty($updated)) {
            return 'No fields provided to update. Please specify at least one field to change.';
        }

        $this->cv->update(['personal_info' => $current]);

        return 'Personal information updated successfully! Updated fields: '.implode(', ', $updated).'.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'first_name' => $schema->string()->description('First name'),
            'last_name' => $schema->string()->description('Last name'),
            'email' => $schema->string()->description('Email address'),
            'phone' => $schema->string()->description('Phone number'),
            'location' => $schema->string()->description('City, Country or location'),
            'linkedin' => $schema->string()->description('LinkedIn profile URL'),
            'github' => $schema->string()->description('GitHub profile URL'),
            'website' => $schema->string()->description('Personal website URL'),
        ];
    }
}

<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Temperature(0.0)]
#[MaxTokens(8192)]
class CvParser implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are a CV/Resume parser. Extract structured data from the provided CV text into the exact schema requested.

Rules:
- Only extract information that is clearly present in the CV text
- If a field is not found, use an empty string or omit it
- Keep descriptions and achievements concise but preserve important details
- For dates, use YYYY-MM-DD format if possible, or YYYY-MM if only month/year is available
- For arrays like skills and achievements, separate items clearly
- For URLs (linkedin, github, website), extract the full URL
- Parse the professional summary/objective as-is

Respond ONLY with the structured JSON matching the schema. No additional commentary.
INSTRUCTIONS;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'first_name' => $schema->string()->required()->description('First name'),
            'last_name' => $schema->string()->required()->description('Last name'),
            'email' => $schema->string()->required()->description('Email address'),
            'phone' => $schema->string()->required()->description('Phone number'),
            'location' => $schema->string()->required()->description('City, Country or location'),
            'linkedin' => $schema->string()->required()->description('LinkedIn profile URL'),
            'github' => $schema->string()->required()->description('GitHub profile URL'),
            'website' => $schema->string()->required()->description('Personal website URL'),
            'title' => $schema->string()->required()->description('Professional title or target role'),
            'summary' => $schema->string()->required()->description('Professional summary or objective'),
            'experiences' => $schema->string()->required()->description('JSON array of experiences. Each: {company, title, location, start_date, end_date, is_current, description, achievements}'),
            'skills' => $schema->string()->required()->description('JSON array of skills. Each: {name, category, level}'),
            'educations' => $schema->string()->required()->description('JSON array of educations. Each: {institution, degree, field_of_study, location, start_date, end_date, is_current}'),
            'certifications' => $schema->string()->required()->description('JSON array of certifications. Each: {name, issuing_organization, issue_date, expiration_date, credential_id}'),
        ];
    }
}

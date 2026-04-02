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
#[Timeout(300)]
class CvParser implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are a CV/Resume parser. Extract structured data from the provided CV text into the EXACT schema requested.

CRITICAL: You MUST use the exact field names from the schema. Do NOT invent your own structure.

Rules:
- Only extract information clearly present in the CV text
- If a field is not found, use an empty string ""
- For dates, use YYYY-MM-DD format if possible, or YYYY-MM if only month/year
- For URLs, extract the full URL as given
- Parse the professional summary/objective as-is

For the "experiences" field: return a JSON array of objects, each with keys: company, title, location, start_date, end_date, is_current, description, achievements
For the "skills" field: return a JSON array of objects, each with keys: name, category, level
For the "educations" field: return a JSON array of objects, each with keys: institution, degree, field_of_study, location, start_date, end_date, is_current
For the "certifications" field: return a JSON array of objects, each with keys: name, issuing_organization, issue_date, expiration_date, credential_id
For the "languages" field: return a JSON array of objects, each with keys: language, proficiency (beginner|elementary|intermediate|upper_intermediate|advanced|fluent|native)
For the "projects" field: return a JSON array of objects, each with keys: name, description, key_achievements (array of strings), project_url, github_url, start_date, end_date

Respond ONLY with valid JSON matching the schema. No markdown, no commentary, no extra keys.
INSTRUCTIONS;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'first_name' => $schema->string()->description('First name from the CV'),
            'last_name' => $schema->string()->description('Last name from the CV'),
            'email' => $schema->string()->description('Email address'),
            'phone' => $schema->string()->description('Phone number'),
            'location' => $schema->string()->description('City, Country or location'),
            'linkedin' => $schema->string()->description('LinkedIn profile URL'),
            'github' => $schema->string()->description('GitHub profile URL'),
            'website' => $schema->string()->description('Personal website URL'),
            'title' => $schema->string()->description('Professional title or role, e.g. "Full-Stack Developer"'),
            'summary' => $schema->string()->description('Professional summary or profile text'),
            'experiences' => $schema->string()->description('JSON array of work experiences. Each object: {"company":"...","title":"...","location":"...","start_date":"YYYY-MM-DD","end_date":"YYYY-MM-DD","is_current":false,"description":"...","achievements":["..."]}'),
            'skills' => $schema->string()->description('JSON array of skills. Each object: {"name":"...","category":"frontend|backend|tools|general","level":"beginner|intermediate|advanced"}'),
            'educations' => $schema->string()->description('JSON array of educations. Each object: {"institution":"...","degree":"...","field_of_study":"...","location":"...","start_date":"YYYY-MM-DD","end_date":"YYYY-MM-DD","is_current":false}'),
            'certifications' => $schema->string()->description('JSON array of certifications. Each object: {"name":"...","issuing_organization":"...","issue_date":"YYYY-MM-DD","expiration_date":"YYYY-MM-DD","credential_id":"..."}'),
            'languages' => $schema->string()->description('JSON array of languages. Each object: {"language":"...","proficiency":"beginner|elementary|intermediate|upper_intermediate|advanced|fluent|native"}'),
            'projects' => $schema->string()->description('JSON array of projects. Each object: {"name":"...","description":"...","key_achievements":["..."],"project_url":"...","github_url":"...","start_date":"YYYY-MM-DD","end_date":"YYYY-MM-DD"}'),
        ];
    }
}

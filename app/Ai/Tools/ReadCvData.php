<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ReadCvData implements Tool
{
    use InteractsWithCv;

    public function description(): Stringable|string
    {
        return 'Read and retrieve all data from the user\'s current CV including personal info, summary, experiences, skills, education, projects, certifications, and languages. ALWAYS use this tool before answering questions about the user\'s CV, resume, or career background.';
    }

    public function handle(Request $request): Stringable|string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return 'No CV found. The user has not created a CV yet.';
        }

        $this->cv->load([
            'experiences',
            'skills',
            'educations',
            'projects',
            'certifications',
            'languages',
        ]);

        $pi = $this->cv->personal_info;
        $output = "=== CV Data for: {$pi['first_name']} {$pi['last_name']} ===\n\n";

        $output .= "--- Personal Info ---\n";
        $output .= "Title: {$this->cv->title}\n";
        $output .= "Email: {$pi['email']}\n";
        $output .= "Phone: {$pi['phone']}\n";
        $output .= "Location: {$pi['location']}\n";
        $output .= "LinkedIn: {$pi['linkedin']}\n";
        $output .= "GitHub: {$pi['github']}\n";
        $output .= "Website: {$pi['website']}\n\n";

        $output .= "--- Professional Summary ---\n";
        $output .= ($this->cv->summary ?: 'No summary yet.')."\n\n";

        $output .= "--- Work Experience ({$this->cv->experiences->count()}) ---\n";
        foreach ($this->cv->experiences as $exp) {
            $output .= "• {$exp->title} at {$exp->company}";
            if ($exp->location) {
                $output .= " ({$exp->location})";
            }
            $output .= "\n";
            if ($exp->start_date) {
                $output .= "  Period: {$exp->start_date->format('M Y')}";
                if ($exp->end_date) {
                    $output .= " - {$exp->end_date->format('M Y')}";
                } elseif ($exp->is_current) {
                    $output .= ' - Present';
                }
                $output .= "\n";
            }
            if ($exp->description) {
                $output .= "  {$exp->description}\n";
            }
            if (! empty($exp->achievements)) {
                foreach ($exp->achievements as $achievement) {
                    if (! empty($achievement)) {
                        $output .= "  - {$achievement}\n";
                    }
                }
            }
            $output .= "\n";
        }

        $output .= "--- Skills ({$this->cv->skills->count()}) ---\n";
        $byCategory = $this->cv->skills->groupBy('category');
        foreach ($byCategory as $category => $skills) {
            $names = $skills->pluck('name')->join(', ');
            $output .= "• {$category}: {$names}\n";
        }
        $output .= "\n";

        $output .= "--- Education ({$this->cv->educations->count()}) ---\n";
        foreach ($this->cv->educations as $edu) {
            $output .= "• {$edu->degree} in {$edu->field_of_study} at {$edu->institution}";
            if ($edu->start_date) {
                $output .= " ({$edu->start_date->format('M Y')}";
                if ($edu->end_date) {
                    $output .= " - {$edu->end_date->format('M Y')}";
                } elseif ($edu->is_current) {
                    $output .= ' - Present';
                }
                $output .= ')';
            }
            $output .= "\n";
        }
        $output .= "\n";

        $output .= "--- Projects ({$this->cv->projects->count()}) ---\n";
        foreach ($this->cv->projects as $proj) {
            $output .= "• {$proj->name}\n";
            if ($proj->description) {
                $output .= "  {$proj->description}\n";
            }
            if (! empty($proj->key_achievements)) {
                foreach ($proj->key_achievements as $achievement) {
                    if (! empty($achievement)) {
                        $output .= "  - {$achievement}\n";
                    }
                }
            }
            if ($proj->project_url) {
                $output .= "  URL: {$proj->project_url}\n";
            }
            if ($proj->github_url) {
                $output .= "  GitHub: {$proj->github_url}\n";
            }
            $output .= "\n";
        }

        $output .= "--- Certifications ({$this->cv->certifications->count()}) ---\n";
        foreach ($this->cv->certifications as $cert) {
            $output .= "• {$cert->name}";
            if ($cert->issuing_organization) {
                $output .= " - {$cert->issuing_organization}";
            }
            $output .= "\n";
        }
        $output .= "\n";

        $output .= "--- Languages ({$this->cv->languages->count()}) ---\n";
        foreach ($this->cv->languages as $lang) {
            $output .= "• {$lang->language} ({$lang->proficiency})\n";
        }

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'section' => $schema->string()
                ->description('Optional: specific section to focus on. Use "all" or leave empty to get the full CV. Options: all, personal, summary, experience, skills, education, projects, certifications, languages'),
        ];
    }
}

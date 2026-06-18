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
        return 'Read and retrieve all data from the user\'s current CV including personal info, summary, experiences, skills, education, projects, certifications, and languages. ALWAYS use this tool before answering questions about the user\'s CV, resume, or career background, and BEFORE calling any update_*, delete_*, or remove_* tool — each section item exposes its `id`, which you need to target updates and deletions. Before deleting, read this first, confirm with the user which entry (by id + details) you will remove, and only then call the delete tool.';
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

        $pi = $this->cv->personal_info ?? [];
        $output = '=== CV Data for: '.trim(($pi['first_name'] ?? '').' '.($pi['last_name'] ?? ''))." ===\n\n";

        $output .= "--- Personal Info ---\n";
        $output .= 'Title: '.($this->cv->title ?? 'Untitled')."\n";
        $output .= 'Email: '.($pi['email'] ?? '')."\n";
        $output .= 'Phone: '.($pi['phone'] ?? '')."\n";
        $output .= 'Location: '.($pi['location'] ?? '')."\n";
        $output .= 'LinkedIn: '.($pi['linkedin'] ?? '')."\n";
        $output .= 'GitHub: '.($pi['github'] ?? '')."\n";
        $output .= 'Website: '.($pi['website'] ?? '')."\n\n";

        $output .= "--- Professional Summary ---\n";
        $output .= ($this->cv->summary ?: 'No summary yet.')."\n\n";

        $output .= "--- Work Experience ({$this->cv->experiences->count()}) ---\n";
        foreach ($this->cv->experiences as $exp) {
            $output .= "• [id:{$exp->id}] {$exp->title} at {$exp->company}";
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
            if (! empty($exp->technologies)) {
                $techs = is_array($exp->technologies) ? implode(', ', $exp->technologies) : (string) $exp->technologies;
                if (trim($techs)) {
                    $output .= "  Technologies: {$techs}\n";
                }
            }
            $output .= "\n";
        }

        $output .= "--- Skills ({$this->cv->skills->count()}) ---\n";
        $byCategory = $this->cv->skills->groupBy('category');
        foreach ($byCategory as $category => $skills) {
            foreach ($skills as $skill) {
                $output .= "• [id:{$skill->id}] {$skill->name}";
                if ($skill->level) {
                    $output .= " ({$skill->level})";
                }
                $output .= " — category: {$category}\n";
            }
        }
        $output .= "\n";

        $output .= "--- Education ({$this->cv->educations->count()}) ---\n";
        foreach ($this->cv->educations as $edu) {
            $output .= "• [id:{$edu->id}] {$edu->degree}";
            if ($edu->field_of_study) {
                $output .= " in {$edu->field_of_study}";
            }
            $output .= " at {$edu->institution}";
            if ($edu->location) {
                $output .= ", {$edu->location}";
            }
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
            if ($edu->description) {
                $output .= "  {$edu->description}\n";
            }
        }
        $output .= "\n";

        $output .= "--- Projects ({$this->cv->projects->count()}) ---\n";
        foreach ($this->cv->projects as $proj) {
            $output .= "• [id:{$proj->id}] {$proj->name}\n";
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
            $output .= "• [id:{$cert->id}] {$cert->name}";
            if ($cert->issuing_organization) {
                $output .= " - {$cert->issuing_organization}";
            }
            if ($cert->issue_date) {
                $output .= " ({$cert->issue_date->format('M Y')})";
            }
            $output .= "\n";
        }
        $output .= "\n";

        $output .= "--- Languages ({$this->cv->languages->count()}) ---\n";
        foreach ($this->cv->languages as $lang) {
            $output .= "• [id:{$lang->id}] {$lang->language} ({$lang->proficiency})\n";
        }

        $output .= "\nNOTE: To edit or remove any item, use its [id:N] above. Always confirm with the user before deleting.";

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

<?php

namespace App\Ai\Agents;

use App\Ai\Tools\AddCvEducation;
use App\Ai\Tools\AddCvExperience;
use App\Ai\Tools\AddCvLanguage;
use App\Ai\Tools\AddCvProject;
use App\Ai\Tools\AddCvSkill;
use App\Ai\Tools\AnalyzeJobDescription;
use App\Ai\Tools\GenerateProfessionalSummary;
use App\Ai\Tools\ImproveProjectDescription;
use App\Ai\Tools\OptimizeForAts;
use App\Ai\Tools\ReadCvData;
use App\Ai\Tools\SearchResumes;
use App\Ai\Tools\SelectBestTemplate;
use App\Ai\Tools\SuggestKeywords;
use App\Ai\Tools\UpdateCvPersonalInfo;
use App\Ai\Tools\UpdateCvSummary;
use App\Models\Cv;
use App\Services\ResumeVectorStore;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Temperature(0.7)]
#[Timeout(300)]
class CvBuilderAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(public ?Cv $cv = null) {}

    private function cvTools(): array
    {
        return [
            (new ReadCvData)->setCv($this->cv),
            (new UpdateCvPersonalInfo)->setCv($this->cv),
            (new UpdateCvSummary)->setCv($this->cv),
            (new AddCvExperience)->setCv($this->cv),
            (new AddCvSkill)->setCv($this->cv),
            (new AddCvEducation)->setCv($this->cv),
            (new AddCvProject)->setCv($this->cv),
            (new AddCvLanguage)->setCv($this->cv),
        ];
    }

    public function instructions(): Stringable|string
    {
        $cvContext = $this->buildCvContextString();

        $instructions = <<<'INSTRUCTIONS'
You are an expert CV writer and career coach specializing in ATS (Applicant Tracking System) optimization and modern recruitment best practices.

IMPORTANT: When the user asks about their CV, resume, experience, skills, projects, education, or anything related to their career background, you MUST first use the "read_cv_data" tool to retrieve their current CV data. Never assume or guess what's on their CV — always read it first.

Your role is to help users create outstanding CVs that pass automated screening systems and impress human recruiters. You should:

1. **Guide users through CV creation** by asking relevant questions about their experience, skills, and career goals
2. **Provide ATS optimization advice** including:
   - Keyword optimization for specific job descriptions
   - Proper formatting that ATS systems can parse
   - Section ordering and content hierarchy
   - How to beat the bots and get noticed by recruiters

3. **Improve content quality** by:
   - Transforming vague descriptions into achievement-focused statements
   - Adding metrics and quantifiable results
   - Using strong action verbs
   - Highlighting relevant accomplishments

4. **Optimize for both ATS and humans** by:
   - Including relevant keywords naturally
   - Maintaining professional formatting
   - Ensuring clarity and readability
   - Balancing keyword density with natural flow

5. **Be conversational and encouraging** while maintaining professionalism

When users share their experience, help them reframe it in terms of business value - efficiency, revenue growth, cost savings, team leadership, and problem-solving.

Always ask clarifying questions if information is unclear, and provide specific, actionable feedback.
INSTRUCTIONS;

        if ($cvContext) {
            $instructions .= "\n\n=== THE USER'S CURRENT CV DATA ===\n{$cvContext}\n=== END OF CV DATA ===\n\nYou have full access to the user's current CV data above. Use this information when answering questions about their CV, making suggestions, or offering improvements. Always reference their actual data.";
        }

        return $instructions;
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            ...$this->cvTools(),
            new AnalyzeJobDescription,
            new SuggestKeywords,
            new OptimizeForAts,
            new ImproveProjectDescription,
            new GenerateProfessionalSummary,
            new SelectBestTemplate,
            new SearchResumes(app(ResumeVectorStore::class)),
        ];
    }

    private function buildCvContextString(): string
    {
        if (! $this->cv || ! $this->cv->exists) {
            return '';
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
        $output = "Name: {$pi['first_name']} {$pi['last_name']}\n";
        $output .= "Title: {$this->cv->title}\n";
        $output .= "Email: {$pi['email']}\n";
        $output .= "Phone: {$pi['phone']}\n";
        $output .= "Location: {$pi['location']}\n";
        $output .= "LinkedIn: {$pi['linkedin']}\n";
        $output .= "GitHub: {$pi['github']}\n";
        $output .= "Website: {$pi['website']}\n\n";

        $output .= 'Summary: '.($this->cv->summary ?: 'No summary yet.')."\n\n";

        if ($this->cv->experiences->isNotEmpty()) {
            $output .= "Work Experience:\n";
            foreach ($this->cv->experiences as $exp) {
                $output .= "- {$exp->title} at {$exp->company}";
                if ($exp->start_date) {
                    $output .= " ({$exp->start_date->format('M Y')}";
                    if ($exp->end_date) {
                        $output .= " - {$exp->end_date->format('M Y')}";
                    } elseif ($exp->is_current) {
                        $output .= ' - Present';
                    }
                    $output .= ')';
                }
                $output .= "\n";
                if ($exp->description) {
                    $output .= "  {$exp->description}\n";
                }
                if (! empty($exp->achievements)) {
                    foreach ($exp->achievements as $a) {
                        if (! empty($a)) {
                            $output .= "  * {$a}\n";
                        }
                    }
                }
            }
            $output .= "\n";
        }

        if ($this->cv->skills->isNotEmpty()) {
            $output .= "Skills:\n";
            foreach ($this->cv->skills->groupBy('category') as $category => $skills) {
                $output .= "- {$category}: ".$skills->pluck('name')->join(', ')."\n";
            }
            $output .= "\n";
        }

        if ($this->cv->educations->isNotEmpty()) {
            $output .= "Education:\n";
            foreach ($this->cv->educations as $edu) {
                $output .= "- {$edu->degree} in {$edu->field_of_study} at {$edu->institution}";
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
        }

        if ($this->cv->projects->isNotEmpty()) {
            $output .= "Projects:\n";
            foreach ($this->cv->projects as $proj) {
                $output .= "- {$proj->name}\n";
                if ($proj->description) {
                    $output .= "  {$proj->description}\n";
                }
                if ($proj->project_url) {
                    $output .= "  URL: {$proj->project_url}\n";
                }
                if ($proj->github_url) {
                    $output .= "  GitHub: {$proj->github_url}\n";
                }
            }
            $output .= "\n";
        }

        if ($this->cv->certifications->isNotEmpty()) {
            $output .= "Certifications:\n";
            foreach ($this->cv->certifications as $cert) {
                $output .= "- {$cert->name}";
                if ($cert->issuing_organization) {
                    $output .= " ({$cert->issuing_organization})";
                }
                $output .= "\n";
            }
            $output .= "\n";
        }

        if ($this->cv->languages->isNotEmpty()) {
            $output .= "Languages:\n";
            foreach ($this->cv->languages as $lang) {
                $output .= "- {$lang->language} ({$lang->proficiency})\n";
            }
        }

        return $output;
    }
}

<?php

namespace App\Ai\Agents;

use App\Ai\Tools\AddCvCertification;
use App\Ai\Tools\AddCvEducation;
use App\Ai\Tools\AddCvExperience;
use App\Ai\Tools\AddCvProject;
use App\Ai\Tools\AddCvSkill;
use App\Ai\Tools\AnalyzeJobDescription;
use App\Ai\Tools\GenerateProfessionalSummary;
use App\Ai\Tools\ImproveProjectDescription;
use App\Ai\Tools\OptimizeForAts;
use App\Ai\Tools\SearchResumes;
use App\Ai\Tools\SelectBestTemplate;
use App\Ai\Tools\SuggestKeywords;
use App\Ai\Tools\UpdateCvPersonalInfo;
use App\Ai\Tools\UpdateCvSummary;
use App\Models\Cv;
use App\Services\ResumeVectorStore;
use Laravel\Ai\Attributes\Model;
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
#[Model('mistral-large-3:675b-cloud')]
#[Temperature(0.7)]
class CvBuilderAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(public ?Cv $cv = null) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $cvContext = '';

        if ($this->cv) {
            $cv = $this->cv;

            $cvContext = "\n\n## USER'S CURRENT CV CONTEXT\n";
            $cvContext .= "You have access to the user's current CV. Use this information to provide personalized, context-aware responses.\n\n";

            if ($cv->personal_info) {
                $info = $cv->personal_info;
                $cvContext .= "### Personal Info\n";
                $cvContext .= '- Name: '.($info['first_name'] ?? 'N/A').' '.($info['last_name'] ?? 'N/A')."\n";
                $cvContext .= '- Email: '.($info['email'] ?? 'N/A')."\n";
                $cvContext .= '- Phone: '.($info['phone'] ?? 'N/A')."\n";
                $cvContext .= '- Location: '.($info['location'] ?? 'N/A')."\n";
                if (! empty($info['linkedin_url'])) {
                    $cvContext .= "- LinkedIn: {$info['linkedin_url']}\n";
                }
                $cvContext .= "\n";
            }

            if ($cv->summary) {
                $cvContext .= "### Professional Summary\n{$cv->summary}\n\n";
            }

            if ($cv->experiences->isNotEmpty()) {
                $cvContext .= "### Work Experience ({$cv->experiences->count()})\n";
                foreach ($cv->experiences as $exp) {
                    $dates = $exp->start_date;
                    if ($exp->is_current) {
                        $dates .= ' - Present';
                    } elseif ($exp->end_date) {
                        $dates .= ' - '.$exp->end_date;
                    }
                    $cvContext .= "- **{$exp->title}** at {$exp->company} ({$dates})\n";
                    if ($exp->location) {
                        $cvContext .= "  Location: {$exp->location}\n";
                    }
                    if ($exp->description) {
                        $cvContext .= "  Description: {$exp->description}\n";
                    }
                    if ($exp->achievements) {
                        $cvContext .= "  Achievements: {$exp->achievements}\n";
                    }
                    $cvContext .= "\n";
                }
            }

            if ($cv->skills->isNotEmpty()) {
                $cvContext .= "### Skills ({$cv->skills->count()})\n";
                $grouped = $cv->skills->groupBy('category');
                foreach ($grouped as $category => $skills) {
                    $names = $skills->pluck('name')->join(', ');
                    $cvContext .= "- **{$category}**: {$names}\n";
                }
                $cvContext .= "\n";
            }

            if ($cv->projects->isNotEmpty()) {
                $cvContext .= "### Projects ({$cv->projects->count()})\n";
                foreach ($cv->projects as $project) {
                    $cvContext .= "- **{$project->name}**\n";
                    if ($project->description) {
                        $cvContext .= "  Description: {$project->description}\n";
                    }
                    if ($project->key_achievements) {
                        $cvContext .= "  Achievements: {$project->key_achievements}\n";
                    }
                    $cvContext .= "\n";
                }
            }

            if ($cv->educations->isNotEmpty()) {
                $cvContext .= "### Education ({$cv->educations->count()})\n";
                foreach ($cv->educations as $edu) {
                    $cvContext .= "- **{$edu->degree}** in {$edu->field_of_study} at {$edu->institution}\n";
                    $cvContext .= "\n";
                }
            }

            if ($cv->certifications->isNotEmpty()) {
                $cvContext .= "### Certifications ({$cv->certifications->count()})\n";
                foreach ($cv->certifications as $cert) {
                    $cvContext .= "- **{$cert->name}** - {$cert->issuing_organization}\n";
                    $cvContext .= "\n";
                }
            }

            $cvContext .= "IMPORTANT: When the user asks about their CV, refers to themselves, or asks for improvements, use the above context to give personalized answers. Do NOT ask them to share their CV - you already have it.\n";
        }

        return <<<INSTRUCTIONS
You are an expert CV writer and career coach specializing in ATS (Applicant Tracking System) optimization and modern recruitment best practices.

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

IMPORTANT: You have tools to directly edit the user's CV in the database. When the user asks you to update, add, or change something on their CV (name, summary, skills, experience, projects, education, certifications), USE THE TOOLS to make the changes. Do NOT tell the user to do it manually. Confirm what was changed after using the tool.
{$cvContext}
INSTRUCTIONS;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new AnalyzeJobDescription,
            new SuggestKeywords,
            new OptimizeForAts,
            new ImproveProjectDescription,
            new GenerateProfessionalSummary,
            new SelectBestTemplate,
            new SearchResumes(app(ResumeVectorStore::class)),
            (new UpdateCvSummary)->setCv($this->cv),
            (new UpdateCvPersonalInfo)->setCv($this->cv),
            (new AddCvSkill)->setCv($this->cv),
            (new AddCvExperience)->setCv($this->cv),
            (new AddCvProject)->setCv($this->cv),
            (new AddCvEducation)->setCv($this->cv),
            (new AddCvCertification)->setCv($this->cv),
        ];
    }

    /**
     * Get the current CV context for the conversation.
     */
    public function getCvContext(): array
    {
        if (! $this->cv) {
            return [];
        }

        return [
            'cv_id' => $this->cv->id,
            'title' => $this->cv->title,
            'template' => $this->cv->template_id,
            'personal_info' => $this->cv->personal_info,
            'summary' => $this->cv->summary,
            'experiences_count' => $this->cv->experiences()->count(),
            'skills_count' => $this->cv->skills()->count(),
            'certifications_count' => $this->cv->certifications()->count(),
            'projects_count' => $this->cv->projects()->count(),
        ];
    }
}

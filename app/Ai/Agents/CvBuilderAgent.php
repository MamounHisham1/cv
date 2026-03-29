<?php

namespace App\Ai\Agents;

use App\Ai\Tools\AnalyzeJobDescription;
use App\Ai\Tools\GenerateProfessionalSummary;
use App\Ai\Tools\ImproveProjectDescription;
use App\Ai\Tools\OptimizeForAts;
use App\Ai\Tools\SelectBestTemplate;
use App\Ai\Tools\SuggestKeywords;
use App\Models\Cv;
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
        return <<<'INSTRUCTIONS'
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

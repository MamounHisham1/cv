<?php

namespace App\Ai\Agents;

use App\Ai\Tools\AddCvCertification;
use App\Ai\Tools\AddCvEducation;
use App\Ai\Tools\AddCvExperience;
use App\Ai\Tools\AddCvLanguage;
use App\Ai\Tools\AddCvProject;
use App\Ai\Tools\AddCvSkill;
use App\Ai\Tools\AnalyzeJobDescription;
use App\Ai\Tools\AskClarifyingQuestions;
use App\Ai\Tools\DeleteCvCertification;
use App\Ai\Tools\DeleteCvEducation;
use App\Ai\Tools\DeleteCvExperience;
use App\Ai\Tools\DeleteCvLanguage;
use App\Ai\Tools\DeleteCvProject;
use App\Ai\Tools\DeleteCvSkill;
use App\Ai\Tools\OptimizeForAts;
use App\Ai\Tools\ReadCvData;
use App\Ai\Tools\SearchResumes;
use App\Ai\Tools\SelectBestTemplate;
use App\Ai\Tools\SuggestKeywords;
use App\Ai\Tools\UpdateCvCertification;
use App\Ai\Tools\UpdateCvEducation;
use App\Ai\Tools\UpdateCvExperience;
use App\Ai\Tools\UpdateCvLanguage;
use App\Ai\Tools\UpdateCvPersonalInfo;
use App\Ai\Tools\UpdateCvProject;
use App\Ai\Tools\UpdateCvSkill;
use App\Ai\Tools\UpdateCvSummary;
use App\Models\Cv;
use App\Services\ResumeVectorStore;
use App\Support\AiLocaleDirective;
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
#[Temperature(0.0)]
#[Timeout(300)]
class CvBuilderAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(public ?Cv $cv = null) {}

    private function cvTools(): array
    {
        return [
            // Read
            (new ReadCvData)->setCv($this->cv),

            // Personal info + summary
            (new UpdateCvPersonalInfo)->setCv($this->cv),
            (new UpdateCvSummary)->setCv($this->cv),

            // Experience
            (new AddCvExperience)->setCv($this->cv),
            (new UpdateCvExperience)->setCv($this->cv),
            (new DeleteCvExperience)->setCv($this->cv),

            // Skills
            (new AddCvSkill)->setCv($this->cv),
            (new UpdateCvSkill)->setCv($this->cv),
            (new DeleteCvSkill)->setCv($this->cv),

            // Education
            (new AddCvEducation)->setCv($this->cv),
            (new UpdateCvEducation)->setCv($this->cv),
            (new DeleteCvEducation)->setCv($this->cv),

            // Projects
            (new AddCvProject)->setCv($this->cv),
            (new UpdateCvProject)->setCv($this->cv),
            (new DeleteCvProject)->setCv($this->cv),

            // Certifications
            (new AddCvCertification)->setCv($this->cv),
            (new UpdateCvCertification)->setCv($this->cv),
            (new DeleteCvCertification)->setCv($this->cv),

            // Languages
            (new AddCvLanguage)->setCv($this->cv),
            (new UpdateCvLanguage)->setCv($this->cv),
            (new DeleteCvLanguage)->setCv($this->cv),
        ];
    }

    public function instructions(): Stringable|string
    {
        $cvContext = $this->buildCvContextString();

        $instructions = <<<'INSTRUCTIONS'
You are an expert CV writer and career coach specializing in ATS (Applicant Tracking System) optimization and modern recruitment best practices.

IMPORTANT: When the user asks about their CV, resume, experience, skills, projects, education, or anything related to their career background, you MUST first use the "read_cv_data" tool to retrieve their current CV data. Never assume or guess what's on their CV — always read it first.

## ABSOLUTE TRUTHFULNESS CONTRACT (read carefully)

The user's CV is a factual, legal document that recruiters and ATS systems will treat as the literal truth. Inventing content is the single worst failure mode for this assistant — worse than giving no help at all.

1. **NEVER invent facts.** You must not fabricate, guess, estimate, or "round up" ANY of the following:
   - Metrics, numbers, or statistics (percentages, ratios, user counts, revenue, costs, time saved, performance gains, team sizes, page counts)
   - Dates (start/end dates, durations, "X+ years of experience", issue dates)
   - Company names, job titles, institutions, or locations
   - Technologies, tools, frameworks, certifications, or methodologies
   - Achievements, awards, outcomes, or business impact the user did not state
   - Education details, degrees, or fields of study

2. **Every claim you write must have a source.** A claim is allowed only if it comes from:
   - The user's existing CV data (provided to you), OR
   - Something the user explicitly wrote in the chat.
   If you cannot trace a fact to one of these, you do NOT have it.

3. **"Improve" means rephrase, not invent.** When asked to improve a summary, project, or experience, you may only:
   - Tighten wording, fix grammar, improve flow and clarity
   - Convert passive voice to active voice
   - Use stronger action verbs for actions the user DID describe
   - Reorganize or restructure existing content
   You may NOT add new metrics, achievements, technologies, or details that were not already there.

4. **When facts are missing, ASK — never guess.** If the user wants stronger content but the needed facts are absent (e.g. "improve this project" but no metrics exist), you MUST call the "ask_clarifying_questions" tool to request the missing facts, then STOP and wait for the user's answer. Do not write anything to the CV until the user supplies the facts.

## MANDATORY TOOL USAGE — questions must go through ask_clarifying_questions

This is non-negotiable. Whenever you need ANY information from the user — missing facts, a preference, a confirmation, a missing date, a metric, a URL, anything you would otherwise ask in words — you MUST call the "ask_clarifying_questions" tool.

- ❌ NEVER type clarifying questions as plain text in your reply. Do not write "Can you tell me…", "What was…", "Do you have…", or any question that asks the user for information, as part of your message text.
- ✅ ALWAYS call the "ask_clarifying_questions" tool instead, with one entry per question, each containing {question, why, example}. Example call:
  ask_clarifying_questions({ questions: [ { question: "How many concurrent users did the app handle?", why: "To quantify the scale of the project", example: "e.g., around 1,000 peak users" } ] })
- After calling it, STOP. Do not also write the questions as text, and **do not call any update_* / add_* / delete_* tool in the same turn.** A turn is either "ask questions" OR "propose edits" — never both. If you call ask_clarifying_questions, the user must answer before you propose ANY change; any edits you stage in the same turn as a question will be silently discarded by the system.

If you find yourself about to write a question mark directed at the user, that is your signal to call "ask_clarifying_questions" instead. The only text you may output alongside the tool call is a brief framing line (e.g. "I can improve your projects, but I need a few details first.").

5. **Describe staged edits HONESTLY — they are NOT applied yet.** Every add_*/update_*/delete_* tool does NOT modify the CV. It stages a *proposal* that the user must review and approve in a separate step. Nothing is saved until the user clicks "Apply." Therefore, in your reply:

   - ❌ NEVER say the CV was "updated", "changed", "enhanced", "finalized", or "made stronger". NEVER write "✅ Changes Made", "Changes Applied", "Here's what I updated", "Your CV is now…", or any past-tense claim that the edit took effect. The edit did NOT take effect.
   - ✅ ALWAYS use future/conditional tense: "I've *prepared* the following changes for your review", "Here's what I'd change — review and approve to apply them", "I've drafted updates to…". Frame every edit as a *proposal awaiting approval*, never a completed action.
   - End with a clear pointer to the review step: "The changes are queued below for your review — uncheck anything you don't want, then hit Apply."

   For each proposed change, list the field and the source of any new content ("you stated: 4 developers" / "from your CV"). If you cannot name a source, do not propose the change.

6. **One edit per record per turn.** To update several fields of the same project/experience/education, make a SINGLE update call with all the changed fields — never call the same update tool repeatedly for one record. Duplicate calls waste the user's review effort and are collapsed anyway.

## Your role

Help users create outstanding CVs that pass automated screening systems and impress human recruiters:

1. **Guide users through CV creation** by asking relevant questions about their experience, skills, and career goals
2. **Provide ATS optimization advice** — keyword strategy, parsing-friendly formatting, section ordering, content hierarchy
3. **Improve content quality** within the truthfulness rules above — tighten phrasing, use strong action verbs, surface metrics the user confirms
4. **Be conversational and encouraging** while maintaining professionalism

When users share their experience, help them reframe it in terms of business value — but only using facts they provided, never invented ones.

Always ask clarifying questions if information is unclear, and provide specific, actionable feedback.
INSTRUCTIONS;

        if ($cvContext) {
            $instructions .= "\n\n=== THE USER'S CURRENT CV DATA ===\n{$cvContext}\n=== END OF CV DATA ===\n\nYou have full access to the user's current CV data above. Use this information when answering questions about their CV, making suggestions, or offering improvements. Always reference their actual data.";
        }

        // Fold in any industry-pack-specific guidance (e.g. cloud career
        // value propositions) when the CV targets a specialized field.
        $packContext = $this->cv?->industryPack()?->promptContext();
        if (! empty(trim((string) $packContext))) {
            $instructions .= "\n\n=== INDUSTRY CONTEXT ===\n{$packContext}\n=== END OF INDUSTRY CONTEXT ===";
        }

        // Locale-driven response language (Arabic when the user's locale is
        // ar; otherwise mirror whatever language the user writes in).
        return $instructions.AiLocaleDirective::for();
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            ...$this->cvTools(),
            app(AskClarifyingQuestions::class),
            new AnalyzeJobDescription,
            new SuggestKeywords,
            new OptimizeForAts,
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

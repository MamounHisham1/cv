<?php

namespace App\Livewire;

use App\Ai\Agents\InterviewEvaluatorAgent;
use App\Models\Cv;
use App\Models\InterviewSession;
use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('AI Interviewer')]
class AiInterviewer extends Component
{
    // State machine: setup -> active -> evaluating -> results
    public string $state = 'setup';

    // Setup properties
    public ?int $selectedCvId = null;

    public ?string $jobDescription = null;

    public string $interviewType = 'mixed';

    // Active session properties
    public ?InterviewSession $session = null;

    public array $messages = [];

    public bool $isRecording = false;

    public bool $isAiSpeaking = false;

    // Evaluation properties
    public ?array $evaluation = null;

    // System prompt built from CV data for the Deepgram Voice Agent
    public string $systemPrompt = '';

    // Dynamic greeting for the Voice Agent
    public string $greeting = '';

    #[Computed]
    public function cvs()
    {
        return Auth::user()->cvs()->latest()->get();
    }

    public function mount()
    {
        if ($this->cvs()->isNotEmpty()) {
            $this->selectedCvId = $this->cvs()->first()->id;
        }
    }

    public function startInterview(CreditManager $creditManager)
    {
        $this->validate([
            'selectedCvId' => 'required|exists:cvs,id',
            'interviewType' => 'required|in:behavioral,technical,mixed',
        ]);

        $cv = Cv::findOrFail($this->selectedCvId);

        if ($cv->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $creditManager->canPerformOperation(Auth::user(), 'ai_interview')) {
            $this->addError('credits', 'Not enough credits to start an interview.');

            return;
        }

        $this->session = InterviewSession::create([
            'user_id' => Auth::id(),
            'cv_id' => $cv->id,
            'job_description' => $this->jobDescription,
            'interview_type' => $this->interviewType,
            'status' => 'active',
            'started_at' => now(),
            'conversation_id' => Str::uuid()->toString(),
        ]);

        $this->state = 'active';
        $this->messages = [];
        $this->systemPrompt = $this->buildSystemPrompt($cv);
        $this->greeting = $this->buildGreeting($cv);
    }

    public function saveMessage(string $role, string $content)
    {
        if ($this->state !== 'active' || ! $this->session) {
            return;
        }

        if (! in_array($role, ['interviewer', 'candidate'])) {
            $role = 'candidate';
        }

        $this->addMessage($role, $content);
    }

    public function endInterview(CreditManager $creditManager)
    {
        if (! $this->session || $this->state !== 'active') {
            return;
        }

        $this->session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_questions' => $this->session->messages()->where('role', 'interviewer')->count(),
            'duration_seconds' => now()->diffInSeconds($this->session->started_at),
        ]);

        $creditManager->deduct(Auth::user(), config('credits.minimum_charge.ai_interview', 3), 'ai_interview', clone $this->session, [
            'interview_type' => $this->session->interview_type,
        ]);

        $this->state = 'evaluating';
    }

    public function generateEvaluation()
    {
        if ($this->state !== 'evaluating' || ! $this->session) {
            return;
        }

        $this->session->load('messages');

        $agent = new InterviewEvaluatorAgent(
            $this->session->cv,
            $this->session->messages()->orderBy('sort_order')->get()->map(fn ($msg) => [
                'role' => $msg->role,
                'content' => $msg->content,
            ])->all(),
            $this->session->job_description
        );

        try {
            $response = $agent->prompt('Evaluate the interview transcript.');

            $evaluation = $this->session->evaluation()->create([
                'overall_score' => $response['overall_score'],
                'grade' => $response['grade'],
                'summary' => $response['summary'],
                'criteria' => $response['criteria'],
                'strengths' => $response['strengths'],
                'improvements' => $response['improvements'],
            ]);

            $this->evaluation = $evaluation->toArray();
            $this->state = 'results';
        } catch (\Exception $e) {
            $this->addError('eval', 'Failed to generate evaluation. '.$e->getMessage());
        }
    }

    public function getDeepgramKey()
    {
        $apiKey = config('services.deepgram.key');

        if (! $apiKey) {
            return ['error' => 'Deepgram not configured'];
        }

        return ['key' => $apiKey];
    }

    public function resetSession()
    {
        $this->state = 'setup';
        $this->session = null;
        $this->messages = [];
        $this->evaluation = null;
        $this->jobDescription = null;
        $this->systemPrompt = '';
    }

    public function addMessage(string $role, string $content)
    {
        $message = $this->session->messages()->create([
            'role' => $role,
            'content' => $content,
            'sort_order' => count($this->messages),
        ]);

        $this->messages[] = [
            'id' => $message->id,
            'role' => $role,
            'content' => $content,
        ];
    }

    protected function buildGreeting(Cv $cv): string
    {
        $pi = $cv->personal_info;
        $firstName = $pi['first_name'] ?? 'there';
        $title = $cv->title ? " as a {$cv->title}" : '';

        $expCount = $cv->experiences()->count();
        $experienceContext = $expCount > 0
            ? " I see you have {$expCount} ".Str::plural('role', $expCount).' on your CV.'
            : '';

        return "Hello {$firstName}! Welcome to your mock interview{$title}.{$experienceContext} Let's jump right in — could you tell me a bit about yourself and what you're looking for in your next role?";
    }

    protected function buildSystemPrompt(Cv $cv): string
    {
        $cv->load([
            'experiences',
            'skills',
            'educations',
            'projects',
            'certifications',
            'languages',
        ]);

        $cvData = $this->formatCvData($cv);

        $jobContext = $this->jobDescription
            ? "Job Description:\n".$this->jobDescription
            : 'No specific job description provided, ask general software engineering questions.';

        return <<<PROMPT
            You are an expert technical and behavioral interviewer conducting a voice-based mock interview.

            ## Candidate's CV
            {$cvData}

            ## Job Description
            {$jobContext}

            ## Interview Configuration
            - Interview type: {$this->interviewType} (behavioral, technical, or mixed)
            - Tailor your questions to this type.

            ## Rules
            1. Ask ONE question at a time. Do not overwhelm the candidate.
            2. Ask specific, probing questions based on the candidate's CV. If they mention a specific technology or project, follow up on it.
            3. Challenge vague answers. If the candidate says "I improved performance", ask "How did you measure it and what were the exact results?"
            4. Keep your responses concise and conversational. This is a voice interview — speak naturally.
            5. Do not provide feedback or corrections during the interview unless specifically asked.
            6. After about 5-8 questions, when the candidate asks to end the interview, or when the interview has reached a natural conclusion, thank the candidate and say exactly: "Thank you for your time. This concludes our interview."
            7. If the candidate explicitly asks to stop, end, or wrap up the interview, immediately agree and say the closing phrase.
            PROMPT;
    }

    protected function formatCvData(Cv $cv): string
    {
        $pi = $cv->personal_info;
        $output = "Name: {$pi['first_name']} {$pi['last_name']}\n";
        $output .= "Title: {$cv->title}\n";
        $output .= "Email: {$pi['email']}\n";
        $output .= "Location: {$pi['location']}\n\n";

        $output .= 'Professional Summary: '.($cv->summary ?: 'No summary.')."\n\n";

        $output .= "Work Experience ({$cv->experiences->count()}):\n";
        foreach ($cv->experiences as $exp) {
            $output .= "- {$exp->title} at {$exp->company}";
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
                foreach ($exp->achievements as $a) {
                    if (! empty($a)) {
                        $output .= "  - {$a}\n";
                    }
                }
            }
        }
        $output .= "\n";

        $output .= "Skills ({$cv->skills->count()}):\n";
        $byCategory = $cv->skills->groupBy('category');
        foreach ($byCategory as $category => $skills) {
            $output .= "- {$category}: ".$skills->pluck('name')->join(', ')."\n";
        }
        $output .= "\n";

        $output .= "Education ({$cv->educations->count()}):\n";
        foreach ($cv->educations as $edu) {
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

        $output .= "Projects ({$cv->projects->count()}):\n";
        foreach ($cv->projects as $proj) {
            $output .= "- {$proj->name}";
            if ($proj->description) {
                $output .= ": {$proj->description}";
            }
            $output .= "\n";
        }
        $output .= "\n";

        $output .= "Certifications ({$cv->certifications->count()}):\n";
        foreach ($cv->certifications as $cert) {
            $output .= "- {$cert->name}";
            if ($cert->issuing_organization) {
                $output .= " - {$cert->issuing_organization}";
            }
            $output .= "\n";
        }
        $output .= "\n";

        $output .= "Languages ({$cv->languages->count()}):\n";
        foreach ($cv->languages as $lang) {
            $output .= "- {$lang->language} ({$lang->proficiency})\n";
        }

        return $output;
    }

    public function render()
    {
        return view('livewire.ai-interviewer');
    }
}

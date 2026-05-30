<?php

namespace App\Livewire;

use App\Jobs\EvaluateInterview;
use App\Models\Cv;
use App\Models\InterviewEvaluation;
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

    public string $selectedVoice = 'aura-2-orion-en';

    public array $voices = [
        'aura-2-orion-en' => ['name' => 'Orion', 'accent' => 'American', 'gender' => 'Male', 'tone' => 'Calm & Approachable'],
        'aura-2-aurora-en' => ['name' => 'Aurora', 'accent' => 'American', 'gender' => 'Female', 'tone' => 'Cheerful & Energetic'],
        'aura-2-draco-en' => ['name' => 'Draco', 'accent' => 'British', 'gender' => 'Male', 'tone' => 'Warm & Trustworthy'],
        'aura-2-pandora-en' => ['name' => 'Pandora', 'accent' => 'British', 'gender' => 'Female', 'tone' => 'Smooth & Calm'],
    ];

    // Active session properties
    public ?InterviewSession $session = null;

    public array $messages = [];

    public bool $isRecording = false;

    public bool $isAiSpeaking = false;

    // Evaluation properties
    public ?array $evaluation = null;

    public bool $shouldPoll = false;

    public ?string $evalErrorMessage = null;

    public ?int $pendingEvaluationId = null;

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

        // If a specific session was requested (e.g. from history page), load that one
        $requestedSessionId = request('session');

        $sessionQuery = InterviewSession::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->has('evaluation');

        if ($requestedSessionId) {
            $sessionQuery->where('id', (int) $requestedSessionId);
        }

        $session = $sessionQuery->latest()->first();

        if ($session) {
            $evaluation = $session->evaluation;

            if ($evaluation && $evaluation->isCompleted()) {
                $this->session = $session;
                $this->evaluation = $evaluation->toArray();
                $this->state = 'results';
            } elseif ($evaluation && $evaluation->isFailed()) {
                $this->session = $session;
                $this->pendingEvaluationId = $evaluation->id;
                $this->evalErrorMessage = $evaluation->error_message ?? 'Evaluation failed.';
                $this->state = 'evaluating';
            } elseif ($evaluation && ($evaluation->isPending() || $evaluation->isProcessing())) {
                $this->session = $session;
                $this->pendingEvaluationId = $evaluation->id;
                $this->shouldPoll = true;
                $this->state = 'evaluating';
            }
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

    public function endInterview(CreditManager $creditManager, ?array $transcript = null)
    {
        if (! $this->session || $this->state !== 'active') {
            return;
        }

        // Save any accumulated transcript messages that weren't saved during the call
        if ($transcript && count($transcript) > 0) {
            $existingCount = $this->session->messages()->count();
            foreach ($transcript as $i => $msg) {
                $role = ($msg['role'] ?? 'candidate');
                $content = $msg['content'] ?? '';
                if ($content && in_array($role, ['interviewer', 'candidate'])) {
                    $this->session->messages()->create([
                        'role' => $role,
                        'content' => $content,
                        'sort_order' => $existingCount + $i,
                    ]);
                }
            }
        }

        $this->session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_questions' => $this->session->messages()->where('role', 'interviewer')->count(),
            'duration_seconds' => now()->diffInSeconds($this->session->started_at),
        ]);

        try {
            $creditManager->deduct(Auth::user(), config('credits.minimum_charge.ai_interview', 3), 'ai_interview', clone $this->session, [
                'interview_type' => $this->session->interview_type,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Credit deduction failed on interview end: '.$e->getMessage());
        }

        // Create pending evaluation and dispatch job
        $evaluation = $this->session->evaluation()->create([
            'overall_score' => 0,
            'grade' => '',
            'summary' => '',
            'criteria' => [],
            'strengths' => [],
            'improvements' => [],
            'status' => InterviewEvaluation::STATUS_PENDING,
        ]);

        $this->pendingEvaluationId = $evaluation->id;
        $this->shouldPoll = true;
        $this->evalErrorMessage = null;
        $this->state = 'evaluating';

        EvaluateInterview::dispatch(Auth::id(), $this->session->id);
    }

    public function checkEvaluationStatus(): void
    {
        if (! $this->pendingEvaluationId || $this->state !== 'evaluating') {
            return;
        }

        $evaluation = InterviewEvaluation::find($this->pendingEvaluationId);

        if (! $evaluation) {
            $this->shouldPoll = false;
            $this->evalErrorMessage = 'Evaluation not found.';

            return;
        }

        if ($evaluation->isCompleted()) {
            $this->shouldPoll = false;
            $this->evaluation = $evaluation->toArray();
            $this->state = 'results';
        } elseif ($evaluation->isFailed()) {
            $this->shouldPoll = false;
            $this->evalErrorMessage = $evaluation->error_message ?? 'Evaluation failed. Please try again.';
        }
    }

    public function retryEvaluation(): void
    {
        if (! $this->session || $this->state !== 'evaluating') {
            return;
        }

        $this->evalErrorMessage = null;
        $this->shouldPoll = true;

        $this->session->evaluation?->delete();

        $evaluation = $this->session->evaluation()->create([
            'overall_score' => 0,
            'grade' => '',
            'summary' => '',
            'criteria' => [],
            'strengths' => [],
            'improvements' => [],
            'status' => InterviewEvaluation::STATUS_PENDING,
        ]);

        $this->pendingEvaluationId = $evaluation->id;

        EvaluateInterview::dispatch(Auth::id(), $this->session->id);
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
        $this->shouldPoll = false;
        $this->evalErrorMessage = null;
        $this->pendingEvaluationId = null;
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

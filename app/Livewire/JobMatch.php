<?php

namespace App\Livewire;

use App\Jobs\ProcessJobMatch;
use App\Models\Cv;
use App\Models\CvJobMatch;
use App\Services\CreditManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Job Match')]
class JobMatch extends Component
{
    public ?int $selectedCvId = null;

    public string $jobTitle = '';

    public string $jobDescription = '';

    public string $state = 'idle'; // idle | processing | complete | error

    public ?int $currentMatchId = null;

    public ?CvJobMatch $result = null;

    public string $errorMessage = '';

    public bool $shouldPoll = false;

    /** @var Collection<int, Cv>|null */
    public $cvs;

    /** @var Collection<int, CvJobMatch>|null */
    public $history;

    public function mount(?int $cv = null): void
    {
        $this->cvs = Auth::user()->cvs()->select(['id', 'title'])->latest()->get();
        $this->selectedCvId = $cv ?? $this->cvs->first()?->id;
        $this->loadHistory();
    }

    public function loadHistory(): void
    {
        $this->history = Auth::user()->jobMatches()
            ->with('cv:id,title')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function runMatch(): void
    {
        $this->validate([
            'selectedCvId' => 'required|exists:cvs,id',
            'jobTitle' => 'nullable|string|max:255',
            'jobDescription' => 'required|string|min:50|max:15000',
        ]);

        $cv = Cv::where('id', $this->selectedCvId)->where('user_id', Auth::id())->first();
        if (! $cv) {
            return;
        }

        if (! app(CreditManager::class)->canPerformOperation(Auth::user(), 'ai_jd_match')) {
            $this->errorMessage = "You don't have enough credits for a job-match analysis.";
            $this->state = 'error';
            $this->dispatch('insufficient-credits');

            return;
        }

        $match = CvJobMatch::create([
            'user_id' => Auth::id(),
            'cv_id' => $cv->id,
            'status' => CvJobMatch::STATUS_PENDING,
            'job_description' => $this->jobDescription,
            'job_title' => $this->jobTitle ?: null,
        ]);

        $this->currentMatchId = $match->id;
        $this->state = 'processing';
        $this->shouldPoll = true;
        $this->errorMessage = '';

        ProcessJobMatch::dispatch($match->id);
    }

    public function checkStatus(): void
    {
        if (! $this->currentMatchId) {
            $this->shouldPoll = false;

            return;
        }

        $match = CvJobMatch::find($this->currentMatchId);
        if (! $match) {
            $this->state = 'error';
            $this->errorMessage = 'Match record not found.';
            $this->shouldPoll = false;

            return;
        }

        if ($match->isCompleted()) {
            $this->result = $match;
            $this->state = 'complete';
            $this->shouldPoll = false;
            $this->loadHistory();
            $this->dispatch('credits-updated');
        } elseif ($match->isFailed()) {
            $this->state = 'error';
            $this->errorMessage = $match->error_message ?: 'Analysis failed. Please try again.';
            $this->shouldPoll = false;
        }
    }

    public function viewResult(int $id): void
    {
        $match = CvJobMatch::where('user_id', Auth::id())->find($id);
        if (! $match || ! $match->isCompleted()) {
            return;
        }
        $this->result = $match;
        $this->currentMatchId = $match->id;
        $this->state = 'complete';
        $this->selectedCvId = $match->cv_id;
        $this->jobTitle = $match->job_title ?? '';
        $this->jobDescription = $match->job_description;
    }

    public function resetForm(): void
    {
        $this->state = 'idle';
        $this->result = null;
        $this->currentMatchId = null;
        $this->errorMessage = '';
        $this->shouldPoll = false;
    }

    public function scoreColor(int $score): string
    {
        return match (true) {
            $score >= 80 => 'text-emerald-400',
            $score >= 60 => 'text-blue-400',
            $score >= 40 => 'text-amber-400',
            default => 'text-red-400',
        };
    }

    public function render()
    {
        return view('livewire.job-match');
    }
}

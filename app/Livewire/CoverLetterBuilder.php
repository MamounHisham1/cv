<?php

namespace App\Livewire;

use App\CoverLetterTemplates;
use App\Jobs\ProcessCoverLetter;
use App\Models\CoverLetter;
use App\Models\Cv;
use App\Services\CreditManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Cover Letters')]
class CoverLetterBuilder extends Component
{
    /** @var Collection<int, CoverLetter>|null */
    public $letters;

    public ?int $editingId = null;

    public string $title = '';

    public string $body = '';

    public string $templateId = 'classic';

    public ?int $sourceCvId = null;

    // AI generation inputs.
    public ?int $generateCvId = null;

    public string $jobDescription = '';

    // Async generation state: idle | generating | complete | error.
    public string $generationState = 'idle';

    public ?int $generatingLetterId = null;

    public bool $shouldPoll = false;

    public string $errorMessage = '';

    public array $templates;

    /** @var Collection<int, Cv>|null */
    public $cvs;

    public function mount(): void
    {
        $this->templates = CoverLetterTemplates::options();
        $this->cvs = Auth::user()->cvs()->select(['id', 'title'])->latest()->get();
        $this->loadLetters();

        // Resume an in-flight generation if the user navigated away and
        // came back while the job was still running.
        $inFlight = Auth::user()->coverLetters()
            ->where('status', CoverLetter::STATUS_GENERATING)
            ->latest()->first();
        if ($inFlight) {
            $this->generatingLetterId = $inFlight->id;
            $this->generationState = 'generating';
            $this->shouldPoll = true;
        }
    }

    public function loadLetters(): void
    {
        $this->letters = Auth::user()->coverLetters()
            ->with('cv:id,title')
            ->latest()
            ->get();
    }

    public function startNew(): void
    {
        $this->resetEditor();
        $this->editingId = null;
    }

    public function edit(int $id): void
    {
        $letter = $this->ownedLetter($id);
        if (! $letter) {
            return;
        }

        $this->resetEditor();
        $this->editingId = $letter->id;
        $this->title = $letter->title;
        $this->body = $letter->body ?? '';
        $this->templateId = $letter->template_id;
        $this->sourceCvId = $letter->cv_id;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:20000',
            'templateId' => 'required|string|in:'.implode(',', array_keys(CoverLetterTemplates::all())),
            'sourceCvId' => 'nullable|exists:cvs,id',
        ]);

        $data = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'template_id' => $validated['templateId'],
            'cv_id' => $validated['sourceCvId'] && Cv::where('id', $validated['sourceCvId'])->where('user_id', Auth::id())->exists() ? $validated['sourceCvId'] : null,
        ];

        if ($this->editingId) {
            $this->ownedLetter($this->editingId)?->update($data);
            $this->dispatch('notify', message: 'Cover letter saved.', type: 'success');
        } else {
            $letter = Auth::user()->coverLetters()->create($data);
            $this->editingId = $letter->id;
            $this->dispatch('notify', message: 'Cover letter created.', type: 'success');
        }

        $this->loadLetters();
    }

    public function delete(int $id): void
    {
        $letter = $this->ownedLetter($id);
        if (! $letter) {
            return;
        }
        $letter->delete();

        if ($this->editingId === $id) {
            $this->resetEditor();
        }
        $this->loadLetters();
        $this->dispatch('notify', message: 'Cover letter deleted.', type: 'success');
    }

    /**
     * Kick off an async AI draft. Creates a CoverLetter row in the
     * "generating" state and dispatches the queued job; the user can
     * navigate away and will be notified on completion.
     */
    public function startGeneration(): void
    {
        $this->validate([
            'generateCvId' => 'required|exists:cvs,id',
            'jobDescription' => 'nullable|string|max:8000',
        ]);

        $cv = Cv::where('id', $this->generateCvId)->where('user_id', Auth::id())->first();
        if (! $cv) {
            return;
        }

        if (! app(CreditManager::class)->canPerformOperation(Auth::user(), 'ai_cover_letter')) {
            $this->dispatch('notify', message: "You don't have enough credits to generate a cover letter.", type: 'error');
            $this->dispatch('insufficient-credits');

            return;
        }

        $letter = Auth::user()->coverLetters()->create([
            'cv_id' => $cv->id,
            'title' => 'Cover Letter — '.($cv->title ?? 'Application'),
            'template_id' => $this->templateId,
            'status' => CoverLetter::STATUS_GENERATING,
            'job_description' => $this->jobDescription ?: null,
        ]);

        $this->generatingLetterId = $letter->id;
        $this->generationState = 'generating';
        $this->shouldPoll = true;
        $this->errorMessage = '';
        $this->loadLetters();

        ProcessCoverLetter::dispatch($letter->id);

        $this->dispatch('notify', message: 'Drafting your cover letter — we\'ll notify you when it\'s ready. You can navigate away.', type: 'success');
    }

    /**
     * Polled while generation is in flight; transitions the UI when the
     * job finishes (success or failure).
     */
    public function checkGenerationStatus(): void
    {
        if (! $this->generatingLetterId) {
            $this->shouldPoll = false;

            return;
        }

        $letter = CoverLetter::find($this->generatingLetterId);
        if (! $letter) {
            $this->generationState = 'error';
            $this->errorMessage = 'Letter record not found.';
            $this->shouldPoll = false;

            return;
        }

        if ($letter->isGenerated()) {
            $this->shouldPoll = false;
            $this->generationState = 'complete';
            // Load the finished draft into the editor.
            $this->editingId = $letter->id;
            $this->title = $letter->title;
            $this->body = $letter->body ?? '';
            $this->templateId = $letter->template_id;
            $this->sourceCvId = $letter->cv_id;
            $this->loadLetters();
            $this->dispatch('notify', message: 'Cover letter draft ready — edit to make it yours.', type: 'success');
        } elseif ($letter->isFailed()) {
            $this->shouldPoll = false;
            $this->generationState = 'error';
            $this->errorMessage = $letter->error_message ?: 'Generation failed. Please try again.';
            $this->loadLetters();
        }
    }

    public function resetGenerationState(): void
    {
        $this->generationState = 'idle';
        $this->generatingLetterId = null;
        $this->errorMessage = '';
        $this->shouldPoll = false;
    }

    public function resetEditor(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->body = '';
        $this->templateId = 'classic';
        $this->sourceCvId = null;
    }

    public function render()
    {
        return view('livewire.cover-letter-builder');
    }

    private function ownedLetter(int $id): ?CoverLetter
    {
        return CoverLetter::where('user_id', Auth::id())->find($id);
    }
}

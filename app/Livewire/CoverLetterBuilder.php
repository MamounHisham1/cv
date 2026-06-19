<?php

namespace App\Livewire;

use App\Ai\Agents\CoverLetterAgent;
use App\CoverLetterTemplates;
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

    public bool $isGenerating = false;

    public array $templates;

    /** @var Collection<int, Cv>|null */
    public $cvs;

    public function mount(): void
    {
        $this->templates = CoverLetterTemplates::options();
        $this->cvs = Auth::user()->cvs()->select(['id', 'title'])->latest()->get();
        $this->loadLetters();
    }

    public function loadLetters(): void
    {
        $this->letters = Auth::user()->coverLetters()->latest()->get();
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
     * Generate a tailored letter body from a source CV (+ optional JD) via
     * the CoverLetterAgent. Credit-gated like the other AI operations.
     */
    public function generate(): void
    {
        $this->validate([
            'generateCvId' => 'required|exists:cvs,id',
            'jobDescription' => 'nullable|string|max:8000',
        ]);

        $cv = Cv::where('id', $this->generateCvId)->where('user_id', Auth::id())->first();
        if (! $cv) {
            return;
        }

        $creditManager = app(CreditManager::class);
        if (! $creditManager->canPerformOperation(Auth::user(), 'ai_cover_letter')) {
            $this->dispatch('notify', message: 'You don\'t have enough credits to generate a cover letter.', type: 'error');
            $this->dispatch('insufficient-credits');

            return;
        }

        $this->isGenerating = true;

        try {
            $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);
            $prompt = $this->buildPrompt($cv);

            $agent = new CoverLetterAgent;
            $response = $agent->prompt($prompt);

            $body = trim(preg_replace('/\R{3,}/', "\n\n", (string) $response));
            $this->body = $body;
            $this->sourceCvId = $cv->id;
            if (! $this->title) {
                $this->title = 'Cover Letter — '.($cv->title ?? 'Application');
            }

            // Charge based on real token usage.
            $credits = $creditManager->calculateFromUsage($response->usage, 'ai_cover_letter');
            $creditManager->deduct(Auth::user(), $credits, 'ai_cover_letter', null, [
                'prompt_tokens' => $response->usage->promptTokens ?? 0,
                'completion_tokens' => $response->usage->completionTokens ?? 0,
                'cv_id' => $cv->id,
            ]);
            $this->dispatch('credits-updated');
            $this->dispatch('notify', message: 'Draft generated — edit to make it yours.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: 'Generation failed: '.$e->getMessage(), type: 'error');
        } finally {
            $this->isGenerating = false;
        }
    }

    public function resetEditor(): void
    {
        $this->title = '';
        $this->body = '';
        $this->templateId = 'classic';
        $this->sourceCvId = null;
        $this->generateCvId = null;
        $this->jobDescription = '';
    }

    public function render()
    {
        return view('livewire.cover-letter-builder');
    }

    private function buildPrompt(Cv $cv): string
    {
        $prompt = "=== CANDIDATE CV ===\n".$cv->toText()."\n\n";

        if (trim($this->jobDescription) !== '') {
            $prompt .= "=== TARGET JOB DESCRIPTION ===\n".trim($this->jobDescription)."\n\n";
        }

        $prompt .= 'Write the cover-letter body now (paragraphs only, no headers/salutation).';

        return $prompt;
    }

    private function ownedLetter(int $id): ?CoverLetter
    {
        return CoverLetter::where('user_id', Auth::id())->find($id);
    }
}

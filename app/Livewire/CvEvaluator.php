<?php

namespace App\Livewire;

use App\Jobs\ProcessCvEvaluation;
use App\Models\Cv;
use App\Models\CvEvaluation;
use App\Services\CreditManager;
use App\Services\CvTextExtractor;
use League\Flysystem\UnableToRetrieveMetadata;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('AI CV Evaluator')]
class CvEvaluator extends Component
{
    use WithFileUploads;

    /** 'idle' | 'uploading' | 'processing' | 'complete' | 'error' */
    public string $evaluationState = 'idle';

    public ?object $uploadedFile = null;

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public ?string $errorMessage = null;

    /** Raw CV text extracted from the uploaded file. */
    public string $cvText = '';

    /** @var array<int, array<string, mixed>> */
    public array $evaluations = [];

    /** @var array<int, int> Max 2 selected evaluation IDs for comparison */
    public array $selectedEvaluationIds = [];

    /** @var array<string, mixed>|null Comparison diff result */
    public ?array $comparisonResult = null;

    /** Manual text-paste fallback. */
    public string $pastedText = '';

    public string $inputMode = 'upload';

    public ?int $pendingEvaluationId = null;

    public bool $shouldPoll = false;

    /**
     * Load the most recent evaluation for the authenticated user.
     */
    public function mount(?Cv $cv = null): void
    {
        if (! auth()->check()) {
            return;
        }

        if ($cv && $cv->exists) {
            $this->authorize('update', $cv);

            // Check if there's already a completed evaluation for this CV
            $completedEvaluation = CvEvaluation::where('user_id', auth()->id())
                ->where('cv_id', $cv->id)
                ->where('status', CvEvaluation::STATUS_COMPLETED)
                ->latest()
                ->first();

            if ($completedEvaluation) {
                // Show the existing completed evaluation
                $this->result = [
                    'overall_score' => $completedEvaluation->overall_score,
                    'grade' => $completedEvaluation->grade,
                    'summary' => $completedEvaluation->summary,
                    'criteria' => $completedEvaluation->criteria,
                    'top_strengths' => $completedEvaluation->top_strengths ?? [],
                    'critical_improvements' => $completedEvaluation->critical_improvements ?? [],
                    'cv_id' => $completedEvaluation->cv_id,
                ];
                $this->evaluationState = 'complete';
                $this->refreshEvaluations();

                return;
            }

            // Check for pending/processing evaluations
            $pendingEvaluation = CvEvaluation::where('user_id', auth()->id())
                ->where('cv_id', $cv->id)
                ->whereIn('status', [CvEvaluation::STATUS_PENDING, CvEvaluation::STATUS_PROCESSING])
                ->latest()
                ->first();

            if ($pendingEvaluation) {
                $this->pendingEvaluationId = $pendingEvaluation->id;
                $this->evaluationState = 'processing';
                $this->shouldPoll = true;
                $this->refreshEvaluations();

                return;
            }

            // No existing evaluation, start a new one
            $this->evaluateFromCv($cv);

            return;
        }

        $latestEvaluation = auth()->user()->cvEvaluations()->latest()->first();

        if ($latestEvaluation && $latestEvaluation->isCompleted()) {
            $this->result = [
                'overall_score' => $latestEvaluation->overall_score,
                'grade' => $latestEvaluation->grade,
                'summary' => $latestEvaluation->summary,
                'criteria' => $latestEvaluation->criteria,
                'top_strengths' => $latestEvaluation->top_strengths ?? [],
                'critical_improvements' => $latestEvaluation->critical_improvements ?? [],
                'cv_id' => $latestEvaluation->cv_id,
            ];
            $this->evaluationState = 'complete';
        } elseif ($latestEvaluation && ($latestEvaluation->isPending() || $latestEvaluation->isProcessing())) {
            $this->pendingEvaluationId = $latestEvaluation->id;
            $this->evaluationState = 'processing';
            $this->shouldPoll = true;
        }

        $this->evaluations = auth()->check()
            ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
            : [];
    }

    public function hydrate(): void
    {
        try {
            if ($this->uploadedFile) {
                $this->uploadedFile->getClientOriginalName();
            }
        } catch (UnableToRetrieveMetadata $e) {
            $this->uploadedFile = null;
            if ($this->evaluationState === 'uploading') {
                $this->evaluationState = 'idle';
            }
        }
    }

    public function updatedUploadedFile(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $this->evaluationState = 'uploading';
    }

    public function evaluate(): void
    {
        $this->errorMessage = null;
        $this->result = null;

        if ($this->inputMode === 'upload') {
            $this->validate([
                'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
            ]);

            $this->cvText = app(CvTextExtractor::class)->extract($this->uploadedFile);
        } else {
            $this->validate([
                'pastedText' => 'required|string|min:100',
            ]);

            $this->cvText = $this->pastedText;
        }

        if (empty(trim($this->cvText))) {
            $this->errorMessage = 'Could not extract text from the uploaded file. Please try pasting the text directly.';
            $this->evaluationState = 'error';

            return;
        }

        if (auth()->check() && ! app(CreditManager::class)->hasCredits(auth()->user())) {
            $this->errorMessage = "You're out of credits. Invite friends to earn more!";
            $this->evaluationState = 'error';
            $this->dispatch('insufficient-credits');

            return;
        }

        $filename = $this->inputMode === 'upload'
            ? $this->uploadedFile?->getClientOriginalName()
            : null;

        $evaluation = CvEvaluation::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'status' => CvEvaluation::STATUS_PENDING,
            'cv_text' => $this->cvText,
        ]);

        $this->pendingEvaluationId = $evaluation->id;
        $this->evaluationState = 'processing';
        $this->shouldPoll = true;

        ProcessCvEvaluation::dispatch(
            auth()->id(),
            $this->cvText,
            $filename,
            $this->inputMode,
            evaluationId: $evaluation->id,
        );

        $this->refreshEvaluations();
    }

    public function evaluateFromCv(Cv $cv): void
    {
        $this->errorMessage = null;
        $this->result = null;
        $this->uploadedFile = null;
        $this->pastedText = '';

        $this->cvText = $cv->toText();

        if (empty(trim($this->cvText))) {
            $this->errorMessage = 'This CV is empty. Add some content first.';
            $this->evaluationState = 'error';

            return;
        }

        if (! app(CreditManager::class)->hasCredits(auth()->user())) {
            $this->errorMessage = "You're out of credits. Invite friends to earn more!";
            $this->evaluationState = 'error';
            $this->dispatch('insufficient-credits');

            return;
        }

        $existing = CvEvaluation::where('user_id', auth()->id())
            ->where('cv_id', $cv->id)
            ->where('status', CvEvaluation::STATUS_PENDING)
            ->latest()
            ->first();

        if ($existing) {
            $this->pendingEvaluationId = $existing->id;
            $this->evaluationState = 'processing';
            $this->shouldPoll = true;
            $this->refreshEvaluations();

            return;
        }

        $processing = CvEvaluation::where('user_id', auth()->id())
            ->where('cv_id', $cv->id)
            ->where('status', 'processing')
            ->latest()
            ->first();

        if ($processing) {
            $this->pendingEvaluationId = $processing->id;
            $this->evaluationState = 'processing';
            $this->shouldPoll = true;
            $this->refreshEvaluations();

            return;
        }

        $filename = $cv->title.'.cv';

        $evaluation = CvEvaluation::create([
            'user_id' => auth()->id(),
            'cv_id' => $cv->id,
            'filename' => $filename,
            'status' => CvEvaluation::STATUS_PENDING,
            'cv_text' => $this->cvText,
        ]);

        $this->pendingEvaluationId = $evaluation->id;
        $this->evaluationState = 'processing';
        $this->shouldPoll = true;

        ProcessCvEvaluation::dispatch(
            auth()->id(),
            $this->cvText,
            $filename,
            'builder',
            $cv->id,
            evaluationId: $evaluation->id,
        );

        $this->refreshEvaluations();
    }

    public function checkEvaluationStatus(): void
    {
        if (! $this->pendingEvaluationId) {
            return;
        }

        $evaluation = CvEvaluation::find($this->pendingEvaluationId);

        if (! $evaluation) {
            $this->shouldPoll = false;
            $this->evaluationState = 'error';
            $this->errorMessage = 'Evaluation not found.';

            return;
        }

        if ($evaluation->isCompleted()) {
            $this->shouldPoll = false;
            $this->evaluationState = 'complete';
            $this->result = [
                'overall_score' => $evaluation->overall_score,
                'grade' => $evaluation->grade,
                'summary' => $evaluation->summary,
                'criteria' => $evaluation->criteria,
                'top_strengths' => $evaluation->top_strengths ?? [],
                'critical_improvements' => $evaluation->critical_improvements ?? [],
                'cv_id' => $evaluation->cv_id,
            ];
            $this->refreshEvaluations();
        } elseif ($evaluation->isFailed()) {
            $this->shouldPoll = false;
            $this->evaluationState = 'error';
            $this->errorMessage = $evaluation->error_message ?? 'Evaluation failed.';
        }
    }

    public function restart(): void
    {
        $this->evaluationState = 'idle';
        $this->uploadedFile = null;
        $this->result = null;
        $this->errorMessage = null;
        $this->cvText = '';
        $this->pastedText = '';
        $this->selectedEvaluationIds = [];
        $this->comparisonResult = null;
        $this->pendingEvaluationId = null;
        $this->shouldPoll = false;

        session()->forget('cv_evaluation_result');
    }

    /**
     * Re-evaluate the current CV (creates a fresh evaluation).
     */
    public function reevaluate(?Cv $cv = null): void
    {
        // If we have a CV from the route, re-evaluate it
        if ($cv && $cv->exists) {
            $this->authorize('update', $cv);
            $this->result = null;
            $this->errorMessage = null;
            $this->evaluateFromCv($cv);

            return;
        }

        // Otherwise, reset to upload state
        $this->restart();
    }

    /**
     * Compute the grade colour for display.
     */
    public function gradeColour(string $grade): string
    {
        return match (true) {
            str_starts_with($grade, 'A') => 'text-emerald-400',
            str_starts_with($grade, 'B') => 'text-blue-400',
            str_starts_with($grade, 'C') => 'text-amber-400',
            default => 'text-red-400',
        };
    }

    public function toggleEvaluationSelection(int $evaluationId): void
    {
        if (in_array($evaluationId, $this->selectedEvaluationIds)) {
            $this->selectedEvaluationIds = array_values(
                array_diff($this->selectedEvaluationIds, [$evaluationId])
            );
            $this->comparisonResult = null;
        } elseif (count($this->selectedEvaluationIds) < 2) {
            $this->selectedEvaluationIds[] = $evaluationId;
        }

        if (count($this->selectedEvaluationIds) === 2) {
            $this->computeComparison();
        }
    }

    public function computeComparison(): void
    {
        if (count($this->selectedEvaluationIds) !== 2) {
            $this->comparisonResult = null;

            return;
        }

        $evaluations = auth()->user()->cvEvaluations()
            ->whereIn('id', $this->selectedEvaluationIds)
            ->get()
            ->keyBy('id');

        $evalA = $evaluations->get($this->selectedEvaluationIds[0]);
        $evalB = $evaluations->get($this->selectedEvaluationIds[1]);

        if (! $evalA || ! $evalB) {
            $this->comparisonResult = null;

            return;
        }

        $allCriteriaKeys = [
            'contact_information', 'professional_summary', 'work_experience',
            'skills_section', 'education', 'ats_compatibility',
            'formatting_readability', 'achievements_impact',
            'keyword_optimisation', 'overall_completeness',
        ];

        $criteriaDiffs = [];
        foreach ($allCriteriaKeys as $key) {
            $scoreA = $evalA->criteria[$key]['score'] ?? 0;
            $scoreB = $evalB->criteria[$key]['score'] ?? 0;
            $criteriaDiffs[$key] = $scoreB - $scoreA;
        }

        $this->comparisonResult = [
            'eval_a' => $evalA->toArray(),
            'eval_b' => $evalB->toArray(),
            'overall_diff' => $evalB->overall_score - $evalA->overall_score,
            'grade_diff' => $evalB->grade !== $evalA->grade,
            'criteria_diffs' => $criteriaDiffs,
        ];
    }

    public function clearSelection(): void
    {
        $this->selectedEvaluationIds = [];
        $this->comparisonResult = null;
    }

    public function refreshEvaluations(): void
    {
        $this->evaluations = auth()->check()
            ? auth()->user()->cvEvaluations()->latest()->get()->toArray()
            : [];
    }

    public function render()
    {
        return view('livewire.cv-evaluator');
    }
}

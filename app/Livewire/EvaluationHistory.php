<?php

namespace App\Livewire;

use App\Models\CvEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Evaluation History')]
class EvaluationHistory extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public ?string $gradeFilter = null;

    public string $sortBy = 'date';

    public string $sortDirection = 'desc';

    public string $search = '';

    public ?int $selectedEvaluation = null;

    public bool $compareMode = false;

    /** @var array<int, int> */
    public array $compareSelections = [];

    /** @var array<string, mixed>|null */
    public ?array $comparisonResult = null;

    public function setFilter(string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function setGradeFilter(?string $grade): void
    {
        $this->gradeFilter = $grade;
        $this->resetPage();
    }

    public function setSortBy(string $sortBy): void
    {
        if ($this->sortBy === $sortBy) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $sortBy;
            $this->sortDirection = $sortBy === 'score' ? 'desc' : 'desc';
        }
        $this->resetPage();
    }

    public function setSearch(string $search): void
    {
        $this->search = $search;
        $this->resetPage();
    }

    public function viewDetails(int $id): void
    {
        $this->selectedEvaluation = $id;
    }

    public function closeModal(): void
    {
        $this->selectedEvaluation = null;
    }

    public function delete(int $id): void
    {
        $evaluation = CvEvaluation::where('user_id', Auth::id())->findOrFail($id);
        $evaluation->delete();

        if ($this->selectedEvaluation === $id) {
            $this->selectedEvaluation = null;
        }

        $this->compareSelections = array_values(array_filter($this->compareSelections, fn ($v) => $v !== $id));

        if (count($this->compareSelections) < 2) {
            $this->comparisonResult = null;
        }
    }

    public function toggleCompareMode(): void
    {
        $this->compareMode = ! $this->compareMode;
        $this->compareSelections = [];
        $this->comparisonResult = null;
    }

    public function toggleSelection(int $id): void
    {
        if (in_array($id, $this->compareSelections)) {
            $this->compareSelections = array_values(array_filter($this->compareSelections, fn ($v) => $v !== $id));
            $this->comparisonResult = null;
        } elseif (count($this->compareSelections) < 2) {
            $this->compareSelections[] = $id;
        }

        if (count($this->compareSelections) === 2) {
            $this->computeComparison();
        }
    }

    public function computeComparison(): void
    {
        if (count($this->compareSelections) !== 2) {
            $this->comparisonResult = null;

            return;
        }

        $evaluations = CvEvaluation::where('user_id', Auth::id())
            ->whereIn('id', $this->compareSelections)
            ->get()
            ->keyBy('id');

        $evalA = $evaluations->get($this->compareSelections[0]);
        $evalB = $evaluations->get($this->compareSelections[1]);

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
        $this->compareSelections = [];
        $this->comparisonResult = null;
    }

    public function gradeColour(string $grade): string
    {
        return match (true) {
            str_starts_with($grade, 'A') => 'text-emerald-400',
            str_starts_with($grade, 'B') => 'text-blue-400',
            str_starts_with($grade, 'C') => 'text-amber-400',
            default => 'text-red-400',
        };
    }

    public function scoreColour(int $score): string
    {
        return match (true) {
            $score >= 80 => 'from-emerald-500 to-emerald-400',
            $score >= 50 => 'from-blue-500 to-blue-400',
            $score >= 30 => 'from-amber-500 to-amber-400',
            default => 'from-red-500 to-red-400',
        };
    }

    public function statusBadge(string $status): string
    {
        return match ($status) {
            CvEvaluation::STATUS_PENDING => 'inline-flex items-center gap-1.5 rounded-full bg-yellow-500/10 px-2.5 py-1 text-xs font-medium text-yellow-400 border border-yellow-400/20',
            CvEvaluation::STATUS_PROCESSING => 'inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-medium text-blue-400 border border-blue-400/20',
            CvEvaluation::STATUS_COMPLETED => 'inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-400 border border-emerald-400/20',
            CvEvaluation::STATUS_FAILED => 'inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-medium text-red-400 border border-red-400/20',
            default => 'inline-flex items-center gap-1.5 rounded-full bg-zinc-500/10 px-2.5 py-1 text-xs font-medium text-zinc-400 border border-zinc-400/20',
        };
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            CvEvaluation::STATUS_PENDING => 'Pending',
            CvEvaluation::STATUS_PROCESSING => 'Processing',
            CvEvaluation::STATUS_COMPLETED => 'Completed',
            CvEvaluation::STATUS_FAILED => 'Failed',
            default => $status,
        };
    }

    public function render()
    {
        $query = CvEvaluation::where('user_id', Auth::id())->with('cv');

        if ($this->statusFilter !== 'all') {
            match ($this->statusFilter) {
                CvEvaluation::STATUS_PENDING => $query->pending(),
                CvEvaluation::STATUS_PROCESSING => $query->processing(),
                CvEvaluation::STATUS_COMPLETED => $query->completed(),
                CvEvaluation::STATUS_FAILED => $query->failed(),
                default => null,
            };
        }

        if ($this->gradeFilter) {
            $query->where('grade', $this->gradeFilter);
        }

        if ($this->search) {
            $query->where('filename', 'like', '%'.$this->search.'%');
        }

        match ($this->sortBy) {
            'score' => $this->sortDirection === 'desc'
                ? $query->orderByDesc('overall_score')->orderByDesc('id')
                : $query->orderBy('overall_score')->orderBy('id'),
            default => $this->sortDirection === 'desc'
                ? $query->latest()
                : $query->oldest(),
        };

        $evaluations = $query->paginate(20);

        $detailEvaluation = null;
        if ($this->selectedEvaluation) {
            $detailEvaluation = CvEvaluation::where('user_id', Auth::id())
                ->find($this->selectedEvaluation);
        }

        return view('livewire.evaluation-history', [
            'evaluations' => $evaluations,
            'detailEvaluation' => $detailEvaluation,
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\InterviewEvaluation;
use App\Models\InterviewSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Interview History')]
class InterviewHistory extends Component
{
    use WithPagination;

    public string $typeFilter = 'all';

    public string $sortBy = 'newest';

    public function setFilter(string $type): void
    {
        $this->typeFilter = $type;
        $this->resetPage();
    }

    public function setSortBy(string $sort): void
    {
        if ($this->sortBy === $sort) {
            $this->sortBy = $sort === 'newest' ? 'oldest' : 'newest';
        } else {
            $this->sortBy = $sort;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = InterviewSession::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('evaluation', fn ($q) => $q->where('status', InterviewEvaluation::STATUS_COMPLETED))
            ->with(['evaluation', 'cv']);

        if ($this->typeFilter !== 'all') {
            $query->where('interview_type', $this->typeFilter);
        }

        match ($this->sortBy) {
            'oldest' => $query->orderBy('completed_at', 'asc'),
            'highest-score' => $query->orderByRelation('evaluation', 'overall_score', 'desc')->orderByDesc('completed_at'),
            default => $query->orderByDesc('completed_at'),
        };

        $sessions = $query->paginate(12);

        return view('livewire.interview-history', [
            'sessions' => $sessions,
            'totalCount' => InterviewSession::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->whereHas('evaluation', fn ($q) => $q->where('status', InterviewEvaluation::STATUS_COMPLETED))
                ->count(),
        ]);
    }
}

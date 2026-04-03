@php
    $glassCard = 'rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-xl';
    $primaryBtn = 'inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30 disabled:pointer-events-none disabled:opacity-50';
    $ghostBtn = 'inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $dangerBtn = 'inline-flex items-center gap-2 rounded-full border border-red-400/20 bg-red-500/10 px-4 py-2 text-sm font-medium text-red-400 backdrop-blur-sm transition-all duration-300 hover:bg-red-500/20 hover:text-red-300';
@endphp

<div class="mx-auto max-w-5xl space-y-6 p-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Evaluation History</h1>
            <p class="mt-1 text-sm text-zinc-400">View and compare your past CV evaluations.</p>
        </div>
        <a href="{{ route('cv.evaluator') }}" wire:navigate class="{{ $primaryBtn }}">
            <x-ui::icon name="plus" class="h-4 w-4" /> New Evaluation
        </a>
    </div>

    {{-- Filter bar --}}
    <div class="{{ $glassCard }}">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            {{-- Status tabs --}}
            <div class="flex flex-wrap gap-2">
                @foreach(['all' => 'All', 'completed' => 'Completed', 'pending' => 'Pending', 'processing' => 'Processing', 'failed' => 'Failed'] as $key => $label)
                    <button
                        wire:click="setFilter('{{ $key }}')"
                        class="rounded-full px-3 py-1.5 text-xs font-medium transition-colors
                            {{ $statusFilter === $key ? 'bg-emerald-600 text-white' : 'border border-white/10 text-zinc-400 hover:bg-white/5 hover:text-white' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Grade, sort, search --}}
            <div class="flex flex-wrap items-center gap-3">
                <select
                    wire:change="setGradeFilter($event.target.value)"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 focus:outline-none focus:ring-1 focus:ring-emerald-500/40"
                >
                    <option value="">All Grades</option>
                    @foreach(['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F'] as $grade)
                        <option value="{{ $grade }}" {{ $gradeFilter === $grade ? 'selected' : '' }}>{{ $grade }}</option>
                    @endforeach
                </select>

                <button
                    wire:click="setSortBy('score')"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 transition-colors hover:bg-white/10"
                >
                    Score @if($sortBy === 'score'){{ $sortDirection === 'desc' ? '↓' : '↑' }}@endif
                </button>

                <button
                    wire:click="setSortBy('date')"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 transition-colors hover:bg-white/10"
                >
                    Date @if($sortBy === 'date'){{ $sortDirection === 'desc' ? '↓' : '↑' }}@endif
                </button>

                <input
                    type="text"
                    wire:input.live.debounce.300ms="setSearch($event.target.value)"
                    placeholder="Search filename..."
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 placeholder:text-zinc-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/40"
                />
            </div>
        </div>
    </div>

    {{-- Compare bar --}}
    @if($compareMode)
        <div class="flex items-center justify-between rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-6 py-4">
            <div class="flex items-center gap-3">
                <x-ui::icon name="git-compare" class="h-5 w-5 text-emerald-400" />
                <span class="text-sm font-medium text-zinc-200">
                    {{ count($compareSelections) }} / 2 selected
                </span>
                @if(count($compareSelections) > 0)
                    <button wire:click="clearSelection" class="text-xs text-zinc-400 hover:text-white">
                        Clear
                    </button>
                @endif
            </div>
            <div class="flex gap-2">
                <button wire:click="toggleCompareMode" class="{{ $ghostBtn }} text-xs">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    {{-- Comparison result --}}
    @if($comparisonResult)
        <div class="{{ $glassCard }}" wire:key="comparison-{{ implode('-', $compareSelections) }}">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Comparison</h3>
                    <p class="text-sm text-zinc-500">Score differences between the two selected evaluations</p>
                </div>
                <button wire:click="clearSelection" class="{{ $ghostBtn }} text-xs">
                    <x-ui::icon name="x" class="h-3 w-3" /> Clear
                </button>
            </div>

            {{-- Overall score comparison --}}
            <div class="mb-6 grid grid-cols-3 items-center gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-4">
                <div class="text-center">
                    <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_a']['created_at'])->format('M j') }}</p>
                    <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                        <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_a']['grade']) }}">{{ $comparisonResult['eval_a']['grade'] }}</span>
                        <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_a']['overall_score'] }}</span>
                    </div>
                    <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_a']['display_name'] ?? 'Pasted' }}</p>
                </div>

                <div class="text-center">
                    @php
                        $overallDiff = $comparisonResult['overall_diff'];
                        $diffColor = $overallDiff > 0 ? 'text-emerald-400' : ($overallDiff < 0 ? 'text-red-400' : 'text-zinc-400');
                        $diffBg = $overallDiff > 0 ? 'bg-emerald-500/10 border-emerald-400/20' : ($overallDiff < 0 ? 'bg-red-500/10 border-red-400/20' : 'bg-white/5 border-white/10');
                    @endphp
                    <div class="inline-flex items-center gap-1 rounded-full border {{ $diffBg }} px-4 py-2">
                        @if($overallDiff > 0)
                            <x-ui::icon name="arrow-up" class="h-4 w-4 {{ $diffColor }}" />
                        @elseif($overallDiff < 0)
                            <x-ui::icon name="arrow-down" class="h-4 w-4 {{ $diffColor }}" />
                        @else
                            <x-ui::icon name="minus" class="h-4 w-4 {{ $diffColor }}" />
                        @endif
                        <span class="text-sm font-bold {{ $diffColor }}">
                            @if($overallDiff > 0)+@endif{{ $overallDiff }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-zinc-500">overall change</p>
                </div>

                <div class="text-center">
                    <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_b']['created_at'])->format('M j') }}</p>
                    <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                        <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_b']['grade']) }}">{{ $comparisonResult['eval_b']['grade'] }}</span>
                        <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_b']['overall_score'] }}</span>
                    </div>
                    <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_b']['display_name'] ?? 'Pasted' }}</p>
                </div>
            </div>

            {{-- Per-criteria comparison --}}
            <div class="space-y-3">
                @foreach([
                    'contact_information' => 'Contact Information',
                    'professional_summary' => 'Professional Summary',
                    'work_experience' => 'Work Experience',
                    'skills_section' => 'Skills Section',
                    'education' => 'Education',
                    'ats_compatibility' => 'ATS Compatibility',
                    'formatting_readability' => 'Formatting & Readability',
                    'achievements_impact' => 'Achievements & Impact',
                    'keyword_optimisation' => 'Keyword Optimisation',
                    'overall_completeness' => 'Overall Completeness',
                ] as $key => $label)
                    @php
                        $scoreA = $comparisonResult['eval_a']['criteria'][$key]['score'] ?? 0;
                        $scoreB = $comparisonResult['eval_b']['criteria'][$key]['score'] ?? 0;
                        $diff = $comparisonResult['criteria_diffs'][$key] ?? 0;
                        $pctA = $scoreA * 10;
                        $pctB = $scoreB * 10;
                        $diffColor = $diff > 0 ? 'text-emerald-400' : ($diff < 0 ? 'text-red-400' : 'text-zinc-500');
                        $diffBg = $diff > 0 ? 'bg-emerald-500/10 border-emerald-400/20' : ($diff < 0 ? 'bg-red-500/10 border-red-400/20' : 'bg-white/5 border-white/10');
                    @endphp
                    <div class="rounded-xl border border-white/5 bg-white/[0.02] p-3">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-300">{{ $label }}</span>
                            <span class="inline-flex items-center gap-1 rounded-full border {{ $diffBg }} px-2 py-0.5 text-xs font-semibold {{ $diffColor }}">
                                @if($diff > 0)+@endif{{ $diff }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 text-right text-[10px] text-zinc-500">{{ $scoreA }}</span>
                                <div class="flex-1 h-1.5 overflow-hidden rounded-full bg-white/5">
                                    <div class="h-full rounded-full bg-zinc-500 transition-all duration-500" style="width: {{ $pctA }}%"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 text-right text-[10px] text-zinc-500">{{ $scoreB }}</span>
                                <div class="flex-1 h-1.5 overflow-hidden rounded-full bg-white/5">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $diff > 0 ? 'bg-emerald-500' : ($diff < 0 ? 'bg-red-400' : 'bg-zinc-400') }}" style="width: {{ $pctB }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Evaluations list --}}
    @if($evaluations->isEmpty())
        <div class="rounded-2xl border border-white/10 bg-white/5 p-12 text-center backdrop-blur-xl">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/10 bg-white/5">
                <x-ui::icon name="file-text" class="h-8 w-8 text-zinc-500" />
            </div>
            <h3 class="mb-2 text-lg font-semibold text-white">No Evaluations Yet</h3>
            <p class="mb-6 text-sm text-zinc-400">Upload your first CV to get started with AI-powered evaluation.</p>
            <a href="{{ route('cv.evaluator') }}" wire:navigate class="{{ $primaryBtn }}">
                <x-ui::icon name="upload" class="h-4 w-4" /> Evaluate My CV
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($evaluations as $evaluation)
                @php
                    $isSelected = in_array($evaluation->id, $compareSelections);
                @endphp
                <div
                    wire:key="eval-{{ $evaluation->id }}"
                    @if($compareMode)
                        wire:click="toggleSelection({{ $evaluation->id }})"
                    @endif
                    class="group flex flex-col gap-3 rounded-2xl border {{ $isSelected ? 'border-emerald-400/30 bg-emerald-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/5' }} p-4 transition-all duration-200 {{ $compareMode ? 'cursor-pointer' : '' }} sm:flex-row sm:items-center sm:gap-4"
                >

                    {{-- Selection checkbox (compare mode) --}}
                    @if($compareMode)
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border {{ $isSelected ? 'border-emerald-400 bg-emerald-500/20' : 'border-white/20' }}">
                            @if($isSelected)
                                <x-ui::icon name="check" class="h-3 w-3 text-emerald-400" />
                            @endif
                        </div>
                    @endif

                    {{-- Grade badge --}}
                    <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5">
                        <span class="text-sm font-bold {{ $this->gradeColour($evaluation->grade ?? 'F') }}">{{ $evaluation->grade ?? '—' }}</span>
                        <span class="text-[10px] text-zinc-500">{{ $evaluation->overall_score ?? 0 }}</span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-zinc-200">
                            {{ $evaluation->display_name }}
                        </p>
                        <p class="text-xs text-zinc-500">
                            {{ $evaluation->created_at->format('M j, Y \a\t g:i A') }}
                        </p>
                    </div>

                    {{-- Score bar --}}
                    <div class="w-full sm:w-32">
                        <div class="flex items-center justify-between text-xs text-zinc-400 mb-1">
                            <span>Score</span>
                            <span>{{ $evaluation->overall_score ?? 0 }}/100</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $this->scoreColour($evaluation->overall_score ?? 0) }}" style="width: {{ $evaluation->overall_score ?? 0 }}%"></div>
                        </div>
                    </div>

                    {{-- Status badge --}}
                    <div class="shrink-0">
                        <span class="{{ $this->statusBadge($evaluation->status) }}">
                            @if($evaluation->status === \App\Models\CvEvaluation::STATUS_PENDING || $evaluation->status === \App\Models\CvEvaluation::STATUS_PROCESSING)
                                <span class="h-1.5 w-1.5 rounded-full bg-current animate-pulse"></span>
                            @endif
                            {{ $this->statusLabel($evaluation->status) }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    @if(!$compareMode)
                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                wire:click="viewDetails({{ $evaluation->id }})"
                                class="rounded-lg p-2 text-zinc-400 transition-colors hover:bg-white/10 hover:text-white"
                                title="View details"
                            >
                                <x-ui::icon name="eye" class="h-4 w-4" />
                            </button>
                            <button
                                wire:click="delete({{ $evaluation->id }})"
                                wire:confirm="Are you sure you want to delete this evaluation?"
                                class="rounded-lg p-2 text-zinc-400 transition-colors hover:bg-red-500/10 hover:text-red-400"
                                title="Delete"
                            >
                                <x-ui::icon name="trash-2" class="h-4 w-4" />
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center pt-4">
            {{ $evaluations->links() }}
        </div>
    @endif

    {{-- Compare mode toggle (when not in compare mode and has evaluations) --}}
    @if(!$compareMode && $evaluations->count() >= 2)
        <div class="flex justify-center">
            <button wire:click="toggleCompareMode" class="{{ $ghostBtn }}">
                <x-ui::icon name="git-compare" class="h-4 w-4" /> Compare Evaluations
            </button>
        </div>
    @endif

    {{-- Detail modal --}}
    @if($selectedEvaluation && $detailEvaluation)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:keydown.escape="closeModal">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal content --}}
            <div class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl border border-white/10 bg-zinc-900 shadow-2xl">
                {{-- Modal header --}}
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-white/10 bg-zinc-900/95 px-6 py-4 backdrop-blur-xl">
                    <div>
                        <h2 class="text-lg font-bold text-white">Evaluation Details</h2>
                        <p class="text-xs text-zinc-400">{{ $detailEvaluation->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    <button wire:click="closeModal" class="rounded-lg p-2 text-zinc-400 transition-colors hover:bg-white/10 hover:text-white">
                        <x-ui::icon name="x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Score hero --}}
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        <div class="flex h-28 w-28 shrink-0 flex-col items-center justify-center rounded-full border-4 border-emerald-400/30 bg-emerald-500/10 shadow-xl shadow-emerald-500/20">
                            <span class="text-4xl font-black {{ $this->gradeColour($detailEvaluation->grade ?? 'F') }}">{{ $detailEvaluation->grade ?? '—' }}</span>
                            <span class="text-xs font-semibold text-zinc-400">{{ $detailEvaluation->overall_score ?? 0 }}/100</span>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="mb-1 text-xl font-bold text-white">{{ $detailEvaluation->display_name }}</h3>
                            <p class="text-sm text-zinc-400">{{ $detailEvaluation->summary ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Score bar --}}
                    <div>
                        <div class="flex items-center justify-between text-sm text-zinc-400 mb-2">
                            <span>Overall Score</span>
                            <span class="font-bold text-white">{{ $detailEvaluation->overall_score ?? 0 }}/100</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $this->scoreColour($detailEvaluation->overall_score ?? 0) }}" style="width: {{ $detailEvaluation->overall_score ?? 0 }}%"></div>
                        </div>
                    </div>

                    {{-- Criteria breakdown --}}
                    @if($detailEvaluation->criteria)
                        <div>
                            <h4 class="mb-4 text-sm font-bold text-white">Criteria Breakdown</h4>
                            <div class="space-y-4">
                                @foreach([
                                    'contact_information' => 'Contact Information',
                                    'professional_summary' => 'Professional Summary',
                                    'work_experience' => 'Work Experience',
                                    'skills_section' => 'Skills Section',
                                    'education' => 'Education',
                                    'ats_compatibility' => 'ATS Compatibility',
                                    'formatting_readability' => 'Formatting & Readability',
                                    'achievements_impact' => 'Achievements & Impact',
                                    'keyword_optimisation' => 'Keyword Optimisation',
                                    'overall_completeness' => 'Overall Completeness',
                                ] as $key => $label)
                                    @php
                                        $score = $detailEvaluation->criteria[$key]['score'] ?? 0;
                                        $reason = $detailEvaluation->criteria[$key]['reason'] ?? '';
                                        $pct = $score * 10;
                                        $colour = $score >= 8
                                            ? 'from-emerald-500 to-emerald-400'
                                            : ($score >= 5
                                                ? 'from-blue-500 to-blue-400'
                                                : ($score >= 3 ? 'from-amber-500 to-amber-400' : 'from-red-500 to-red-400'));
                                    @endphp
                                    <div>
                                        <div class="mb-1.5 flex items-center justify-between">
                                            <span class="text-sm font-medium text-zinc-300">{{ $label }}</span>
                                            <span class="text-sm font-bold text-white">{{ $score }}/10</span>
                                        </div>
                                        <div class="h-2 overflow-hidden rounded-full bg-white/10">
                                            <div class="h-full rounded-full bg-gradient-to-r {{ $colour }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        @if($reason)
                                            <p class="mt-1 text-xs text-zinc-500">{{ $reason }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Strengths & improvements --}}
                    @if($detailEvaluation->top_strengths || $detailEvaluation->critical_improvements)
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @if($detailEvaluation->top_strengths)
                                <div class="rounded-xl border border-white/5 bg-white/[0.02] p-4">
                                    <div class="mb-3 flex items-center gap-2">
                                        <x-ui::icon name="check-circle" class="h-5 w-5 text-emerald-400" />
                                        <h4 class="font-bold text-white">Top Strengths</h4>
                                    </div>
                                    <ul class="space-y-2">
                                        @foreach($detailEvaluation->top_strengths as $strength)
                                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                                <x-ui::icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                                {{ $strength }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($detailEvaluation->critical_improvements)
                                <div class="rounded-xl border border-white/5 bg-white/[0.02] p-4">
                                    <div class="mb-3 flex items-center gap-2">
                                        <x-ui::icon name="alert-triangle" class="h-5 w-5 text-amber-400" />
                                        <h4 class="font-bold text-white">Critical Improvements</h4>
                                    </div>
                                    <ul class="space-y-2">
                                        @foreach($detailEvaluation->critical_improvements as $improvement)
                                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                                <x-ui::icon name="arrow-right" class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" />
                                                {{ $improvement }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Error message --}}
                    @if($detailEvaluation->status === \App\Models\CvEvaluation::STATUS_FAILED && $detailEvaluation->error_message)
                        <div class="rounded-xl border border-red-400/20 bg-red-500/10 p-4">
                            <div class="flex items-start gap-3">
                                <x-ui::icon name="alert-triangle" class="h-5 w-5 text-red-400 shrink-0" />
                                <div>
                                    <h4 class="font-bold text-white">Error</h4>
                                    <p class="text-sm text-zinc-300">{{ $detailEvaluation->error_message }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button wire:click="closeModal" class="{{ $ghostBtn }}">
                            Close
                        </button>
                        <button
                            wire:click="delete({{ $detailEvaluation->id }})"
                            wire:confirm="Are you sure you want to delete this evaluation?"
                            class="{{ $dangerBtn }}"
                        >
                            <x-ui::icon name="trash-2" class="h-4 w-4" /> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

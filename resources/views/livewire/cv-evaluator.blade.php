@php
    $glassCard = 'overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $primaryBtn = 'inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30 disabled:pointer-events-none disabled:opacity-50';
    $ghostBtn   = 'inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';

    $criteria = [
        'contact_information'    => 'Contact Information',
        'professional_summary'   => 'Professional Summary',
        'work_experience'        => 'Work Experience',
        'skills_section'         => 'Skills Section',
        'education'              => 'Education',
        'ats_compatibility'      => 'ATS Compatibility',
        'formatting_readability' => 'Formatting & Readability',
        'achievements_impact'    => 'Achievements & Impact',
        'keyword_optimisation'   => 'Keyword Optimisation',
        'overall_completeness'   => 'Overall Completeness',
    ];
@endphp

<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.15),_transparent_50%)]"></div>
    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    <div class="relative mx-auto max-w-4xl px-4 py-10 md:px-6 lg:px-8">

        {{-- Page header --}}
        <div class="mb-10 text-center">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">
                AI Powered
            </div>
            <h1 class="mb-3 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                CV <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">Evaluator</span>
            </h1>
            <p class="mx-auto max-w-lg text-base text-zinc-400">
                Upload your CV and get an instant AI-powered score across 10 key criteria with actionable feedback.
            </p>
            <div class="mt-4">
                <a href="{{ route('evaluations.history') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white">
                    <x-ui::icon name="clock" class="h-4 w-4" /> View Evaluation History
                </a>
            </div>
        </div>

        {{-- ===== IDLE / UPLOAD STATE ===== --}}
        @if(in_array($evaluationState, ['idle', 'uploading']))
            <div class="{{ $glassCard }} mb-6">
                {{-- Mode toggle --}}
                <div class="mb-6 flex w-fit rounded-full border border-white/10 bg-white/5 p-1">
                    <button wire:click="$set('inputMode','upload')"
                        class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ $inputMode === 'upload' ? 'bg-white/10 text-white shadow' : 'text-zinc-400 hover:text-white' }}">
                        Upload File
                    </button>
                    <button wire:click="$set('inputMode','paste')"
                        class="rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ $inputMode === 'paste' ? 'bg-white/10 text-white shadow' : 'text-zinc-400 hover:text-white' }}">
                        Paste Text
                    </button>
                </div>

                @if($inputMode === 'upload')
                    <label
                        class="group flex cursor-pointer flex-col items-center justify-center gap-4 rounded-2xl border-2 border-dashed border-white/10 bg-white/5 px-6 py-14 text-center transition-all duration-300 hover:border-emerald-400/40 hover:bg-emerald-500/5"
                        for="cv-upload"
                    >
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/70 transition-all duration-300 group-hover:border-emerald-400/30">
                            <x-ui::icon name="upload" class="h-8 w-8 text-zinc-400 group-hover:text-emerald-300" />
                        </div>
                        <div>
                            <p class="mb-1 text-base font-semibold text-white">Drag & drop your CV here</p>
                            <p class="text-sm text-zinc-500">or <span class="text-emerald-400">browse files</span> — PDF, DOC, DOCX, TXT up to 5 MB</p>
                        </div>
                        <input id="cv-upload" type="file" class="sr-only" wire:model="uploadedFile" accept=".pdf,.doc,.docx,.txt" />
                    </label>

                    @if($uploadedFile)
                        <div class="mt-4 flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 p-4">
                            <x-ui::icon name="file-text" class="h-5 w-5 shrink-0 text-emerald-300" />
                            <span class="flex-1 truncate text-sm text-emerald-100">{{ $uploadedFile->getClientOriginalName() }}</span>
                            <button wire:click="$set('uploadedFile', null)" class="text-zinc-400 hover:text-white">
                                <x-ui::icon name="x" class="h-4 w-4" />
                            </button>
                        </div>
                    @endif

                    @error('uploadedFile')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                @else
                    <textarea
                        wire:model="pastedText"
                        rows="12"
                        placeholder="Paste your full CV text here…"
                        class="w-full resize-none rounded-2xl border border-white/10 bg-zinc-900/50 px-4 py-3 text-sm text-zinc-100 placeholder:text-zinc-500 backdrop-blur-sm focus:outline-none focus:ring-1 focus:ring-emerald-500/40"
                    ></textarea>
                    @error('pastedText')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                @endif

                <div class="mt-6 flex justify-end">
                    <button wire:click="evaluate"
                        wire:loading.attr="disabled"
                        wire:target="evaluate"
                        class="{{ $primaryBtn }}">
                        <span wire:loading.remove wire:target="evaluate">
                            <x-ui::icon name="sparkles" class="h-4 w-4" />
                        </span>
                        <span wire:loading wire:target="evaluate">
                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="evaluate">Evaluate My CV</span>
                        <span wire:loading wire:target="evaluate">Evaluating…</span>
                    </button>
                </div>
            </div>
        @endif


        {{-- ===== PROCESSING STATE ===== --}}
        @if($evaluationState === 'processing')
            <div class="{{ $glassCard }} mb-6 py-16 text-center"
                 @if($shouldPoll)
                     wire:poll.5000ms.keep-alive="checkEvaluationStatus"
                 @endif>
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10">
                    <x-ui::spinner size="lg" class="text-emerald-300" />
                </div>
                <h2 class="mb-3 text-xl font-bold text-white">Processing your CV…</h2>
                <p class="text-sm text-zinc-400">Our AI is evaluating 10 key criteria. You can navigate away and come back later.</p>
            </div>
        @endif

        {{-- ===== EVALUATING STATE ===== --}}
        @if($evaluationState === 'evaluating')
            <div class="{{ $glassCard }} mb-6 py-16 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10">
                    <x-ui::spinner size="lg" class="text-emerald-300" />
                </div>
                <h2 class="mb-3 text-xl font-bold text-white">Analysing your CV…</h2>
                <p class="text-sm text-zinc-400">Our AI is evaluating 10 key criteria. This takes about 15–30 seconds.</p>
            </div>
        @endif

        {{-- ===== ERROR STATE ===== --}}
        @if($evaluationState === 'error')
            <div class="{{ $glassCard }} mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-red-400/20 bg-red-500/10">
                        <x-ui::icon name="alert-triangle" class="h-6 w-6 text-red-400" />
                    </div>
                    <div class="flex-1">
                        <h3 class="mb-1 font-semibold text-white">Evaluation Failed</h3>
                        <p class="text-sm text-zinc-400">{{ $errorMessage }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <button wire:click="restart" class="{{ $ghostBtn }}">
                        <x-ui::icon name="refresh-cw" class="h-4 w-4" /> Try Again
                    </button>
                </div>
            </div>
        @endif

        {{-- ===== RESULTS STATE ===== --}}
        @if($evaluationState === 'complete' && $result)
            {{-- Score hero --}}
            <div class="{{ $glassCard }} mb-6">
                <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                    <div class="flex h-28 w-28 shrink-0 flex-col items-center justify-center rounded-full border-4 border-emerald-400/30 bg-emerald-500/10 shadow-xl shadow-emerald-500/20">
                        <span class="text-4xl font-black {{ $this->gradeColour($result['grade'] ?? 'F') }}">{{ $result['grade'] ?? '—' }}</span>
                        <span class="text-xs font-semibold text-zinc-400">{{ $result['overall_score'] ?? 0 }}/100</span>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="mb-2 text-2xl font-bold text-white">Your CV Score</h2>
                        <p class="leading-relaxed text-zinc-400">{{ $result['summary'] ?? '' }}</p>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        @if($result['cv_id'] ?? null)
                            <button wire:click="reevaluate({{ $result['cv_id'] }})" class="{{ $ghostBtn }}">
                                <x-ui::icon name="refresh-cw" class="h-4 w-4" /> Re-evaluate
                            </button>
                            <a href="{{ route('cv.edit', $result['cv_id']) }}" wire:navigate class="{{ $primaryBtn }}">
                                <x-ui::icon name="pencil" class="h-4 w-4" /> Edit CV
                            </a>
                        @else
                            <button wire:click="restart" class="{{ $ghostBtn }}">
                                <x-ui::icon name="refresh-cw" class="h-4 w-4" /> Evaluate Again
                            </button>
                            <a href="{{ route('cv.builder') }}" wire:navigate class="{{ $primaryBtn }}">
                                <x-ui::icon name="plus" class="h-4 w-4" /> Build CV
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Criteria breakdown --}}
            <div class="{{ $glassCard }} mb-6">
                <h3 class="mb-6 text-lg font-bold text-white">Criteria Breakdown</h3>
                <div class="space-y-5">
                    @foreach($criteria as $key => $label)
                        @php
                            $score  = $result['criteria'][$key]['score'] ?? 0;
                            $reason = $result['criteria'][$key]['reason'] ?? '';
                            $pct    = $score * 10;
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
                                <div class="h-full rounded-full bg-gradient-to-r {{ $colour }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                            @if($reason)
                                <p class="mt-1 text-xs text-zinc-500">{{ $reason }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Strengths & improvements --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="{{ $glassCard }}">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::icon name="check-circle" class="h-5 w-5 text-emerald-300" />
                        </div>
                        <h3 class="font-bold text-white">Top Strengths</h3>
                    </div>
                    <ul class="space-y-3">
                        @foreach(($result['top_strengths'] ?? []) as $strength)
                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                <x-ui::icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                {{ $strength }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="{{ $glassCard }}">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-500/10">
                            <x-ui::icon name="alert-triangle" class="h-5 w-5 text-amber-300" />
                        </div>
                        <h3 class="font-bold text-white">Critical Improvements</h3>
                    </div>
                    <ul class="space-y-3">
                        @foreach(($result['critical_improvements'] ?? []) as $improvement)
                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                <x-ui::icon name="arrow-right" class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" />
                                {{ $improvement }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ===== EVALUATION HISTORY ===== --}}
        @if(auth()->check() && count($evaluations) > 0)
        <div class="mt-10 {{ $glassCard }}">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Evaluation History</h3>
                    <p class="text-sm text-zinc-500">Select two evaluations to compare scores</p>
                </div>
                @if(count($selectedEvaluationIds) > 0)
                    <button wire:click="clearSelection"
                        class="{{ $ghostBtn }} text-xs">
                        <x-ui::icon name="x" class="h-3 w-3" /> Clear Selection
                    </button>
                @endif
            </div>

            {{-- Score progression sparkline --}}
            {{-- TODO: Re-Enable after enhancing the design --}}
            {{-- <div class="mb-6 flex items-end gap-1 h-16">
                @foreach($evaluations as $eval)
                    @php
                        $heightPct = max(15, ($eval['overall_score'] ?? 0));
                        $isSelected = in_array($eval['id'], $selectedEvaluationIds);
                        $barColor = $isSelected
                            ? 'bg-emerald-400'
                            : 'bg-zinc-600 hover:bg-zinc-500';
                    @endphp
                    <div class="flex-1 min-w-[4px] rounded-t {{ $barColor }} transition-all duration-300 cursor-pointer"
                         style="height: {{ $heightPct }}%"
                         wire:click="toggleEvaluationSelection({{ $eval['id'] }}">
                    </div>
                @endforeach
            </div> --}}

            {{-- History list --}}
            <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                @foreach($evaluations as $index => $eval)
                    @php
                        $isSelected = in_array($eval['id'], $selectedEvaluationIds);
                        $prevScore = isset($evaluations[$index + 1]) ? $evaluations[$index + 1]['overall_score'] : null;
                        $scoreDiff = $prevScore !== null ? $eval['overall_score'] - $prevScore : null;
                    @endphp
                    <div wire:click="toggleEvaluationSelection({{ $eval['id'] }})"
                        class="group flex items-center gap-4 rounded-2xl border {{ $isSelected ? 'border-emerald-400/30 bg-emerald-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/5' }} p-4 transition-all duration-200 cursor-pointer">

                        {{-- Selection checkbox --}}
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border {{ $isSelected ? 'border-emerald-400 bg-emerald-500/20' : 'border-white/20' }}">
                            @if($isSelected)
                                <x-ui::icon name="check" class="h-3 w-3 text-emerald-400" />
                            @endif
                        </div>

                        {{-- Score badge --}}
                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl border {{ $isSelected ? 'border-emerald-400/20 bg-emerald-500/10' : 'border-white/10 bg-white/5' }}">
                            <span class="text-sm font-bold {{ $this->gradeColour($eval['grade'] ?? 'F') }}">{{ $eval['grade'] ?? '—' }}</span>
                            <span class="text-[10px] text-zinc-500">{{ $eval['overall_score'] ?? 0 }}</span>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-200">
                                {{ $eval['filename'] ?? 'Pasted Text' }}
                            </p>
                            <p class="text-xs text-zinc-500">
                                {{ \Carbon\Carbon::parse($eval['created_at'])->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>

                        {{-- Score diff indicator --}}
                        @if($scoreDiff !== null)
                            <div class="shrink-0 flex items-center gap-1 text-xs font-semibold {{ $scoreDiff > 0 ? 'text-emerald-400' : ($scoreDiff < 0 ? 'text-red-400' : 'text-zinc-500') }}">
                                @if($scoreDiff > 0)
                                    <x-ui::icon name="arrow-up" class="h-3 w-3" />
                                    +{{ $scoreDiff }}
                                @elseif($scoreDiff < 0)
                                    <x-ui::icon name="arrow-down" class="h-3 w-3" />
                                    {{ $scoreDiff }}
                                @else
                                    <x-ui::icon name="minus" class="h-3 w-3" />
                                    0
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ===== COMPARISON PANEL ===== --}}
        @if($comparisonResult)
        <div class="mt-6 {{ $glassCard }}" wire:key="comparison-{{ implode('-', $selectedEvaluationIds) }}">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Comparison</h3>
                    <p class="text-sm text-zinc-500">Score differences between the two selected evaluations</p>
                </div>
            </div>

            {{-- Overall score comparison --}}
            <div class="mb-6 grid grid-cols-3 items-center gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-4">
                {{-- Eval A --}}
                <div class="text-center">
                    <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_a']['created_at'])->format('M j') }}</p>
                    <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                        <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_a']['grade']) }}">{{ $comparisonResult['eval_a']['grade'] }}</span>
                        <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_a']['overall_score'] }}</span>
                    </div>
                    <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_a']['filename'] ?? 'Pasted' }}</p>
                </div>

                {{-- Diff indicator --}}
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

                {{-- Eval B --}}
                <div class="text-center">
                    <p class="mb-1 text-xs text-zinc-500">{{ \Carbon\Carbon::parse($comparisonResult['eval_b']['created_at'])->format('M j') }}</p>
                    <div class="inline-flex h-16 w-16 flex-col items-center justify-center rounded-full border-2 border-white/10 bg-white/5">
                        <span class="text-xl font-black {{ $this->gradeColour($comparisonResult['eval_b']['grade']) }}">{{ $comparisonResult['eval_b']['grade'] }}</span>
                        <span class="text-[10px] text-zinc-400">{{ $comparisonResult['eval_b']['overall_score'] }}</span>
                    </div>
                    <p class="mt-1 truncate text-xs text-zinc-400">{{ $comparisonResult['eval_b']['filename'] ?? 'Pasted' }}</p>
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

    </div>
</div>

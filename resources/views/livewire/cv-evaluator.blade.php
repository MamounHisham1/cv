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
                    <button wire:click="evaluate" class="{{ $primaryBtn }}">
                        <x-ui::icon name="sparkles" class="h-4 w-4" />
                        Evaluate My CV
                    </button>
                </div>
            </div>
        @endif


        {{-- ===== EVALUATING STATE ===== --}}
        @if($evaluationState === 'evaluating')
            <div class="{{ $glassCard }} mb-6 py-16 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10">
                    <x-ui::icon name="sparkles" class="h-10 w-10 animate-pulse text-emerald-300" />
                </div>
                <h2 class="mb-3 text-xl font-bold text-white">Analysing your CV…</h2>
                <p class="text-sm text-zinc-400">Our AI is evaluating 10 key criteria. This takes about 15–30 seconds.</p>
                <div class="mx-auto mt-8 h-1.5 w-64 overflow-hidden rounded-full bg-white/10">
                    <div class="h-full w-3/5 animate-pulse rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
                </div>
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
                        <button wire:click="restart" class="{{ $ghostBtn }}">
                            <x-ui::icon name="refresh-cw" class="h-4 w-4" /> Evaluate Again
                        </button>
                        <a href="{{ route('cv.builder') }}" wire:navigate class="{{ $primaryBtn }}">
                            <x-ui::icon name="pencil" class="h-4 w-4" /> Build CV
                        </a>
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

    </div>
</div>

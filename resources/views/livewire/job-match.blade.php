@php
    $glassCard  = 'overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $primaryBtn = 'inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30 disabled:pointer-events-none disabled:opacity-50';
    $ghostBtn   = 'inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $fieldInput = 'mt-1 w-full rounded-2xl border border-white/10 bg-zinc-900/50 px-4 py-3 text-sm text-zinc-100 placeholder:text-zinc-500 backdrop-blur-sm focus:outline-none focus:ring-1 focus:ring-emerald-500/40';
@endphp

<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.15),_transparent_50%)]"></div>
    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    @if($shouldPoll)
        <div wire:poll.3s="checkStatus" class="sr-only">polling</div>
    @endif

    <div class="relative mx-auto max-w-5xl px-4 py-10 md:px-6 lg:px-8">

        {{-- Page header --}}
        <div class="mb-10 text-center">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">
                AI Powered
            </div>
            <h1 class="mb-3 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                Job <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">{{ __("Match") }}</span>
            </h1>
            <p class="mx-auto max-w-lg text-base text-zinc-400">
                Paste a job description and see how well your CV fits — compatibility score, matched and missing keywords, and concrete suggestions to close the gaps.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Input form --}}
            <section class="{{ $glassCard }} space-y-5">
                <div>
                    <x-ui::label for="selectedCvId">{{ __("Your CV") }}</x-ui::label>
                    <select id="selectedCvId" wire:model="selectedCvId" class="{{ $fieldInput }}">
                        @foreach($cvs as $cv)
                            <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-ui::label for="jobTitle">Job title (optional)</x-ui::label>
                    <input id="jobTitle" type="text" wire:model.blur="jobTitle" class="{{ $fieldInput }}" placeholder="Senior Backend Engineer">
                </div>

                <div>
                    <x-ui::label for="jobDescription">{{ __("Job description") }}</x-ui::label>
                    <textarea id="jobDescription" wire:model.blur="jobDescription" rows="10" class="{{ $fieldInput }}"
                              placeholder="Paste the full job posting here…"></textarea>
                    @error('jobDescription') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-zinc-500">{{ __("2 credits min") }}</span>
                    <button type="button" wire:click="runMatch" wire:loading.attr="disabled" wire:target="runMatch" class="{{ $primaryBtn }}">
                        <x-ui::icon name="sparkles" class="h-4 w-4" />
                        <span wire:loading.remove wire:target="runMatch">{{ __("Analyze match") }}</span>
                        <span wire:loading wire:target="runMatch">{{ __("Analyzing…") }}</span>
                    </button>
                </div>

                @if($state === 'error')
                    <div class="flex items-start gap-3 rounded-2xl border border-red-400/20 bg-red-500/10 p-4">
                        <x-ui::icon name="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0 text-red-400" />
                        <p class="text-sm text-red-300">{{ $errorMessage }}</p>
                    </div>
                @endif
            </section>

            {{-- Result --}}
            <section class="space-y-5">
                @if($state === 'processing')
                    <div class="{{ $glassCard }} mb-6 py-16 text-center">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::spinner size="lg" class="text-emerald-300" />
                        </div>
                        <h2 class="mb-3 text-xl font-bold text-white">{{ __("Analyzing your CV…") }}</h2>
                        <p class="text-sm text-zinc-400">{{ __("Comparing your experience against the job description. This takes about 15–30 seconds.") }}</p>
                    </div>
                @elseif($state === 'complete' && $result)
                    <div class="{{ $glassCard }} space-y-5">
                        {{-- Score hero --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="flex h-24 w-24 shrink-0 flex-col items-center justify-center rounded-full border-4 border-emerald-400/30 bg-emerald-500/10 shadow-xl shadow-emerald-500/20">
                                    <span class="text-3xl font-black {{ $this->scoreColor($result->compatibility_score ?? 0) }}">{{ $result->compatibility_score ?? '—' }}</span>
                                    <span class="text-[10px] font-semibold text-zinc-500">/100</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">{{ __("Compatibility") }}</p>
                                    <p class="mt-1 text-2xl font-bold {{ $this->scoreColor($result->compatibility_score ?? 0) }}">Grade {{ $result->grade }}</p>
                                </div>
                            </div>
                            <button type="button" wire:click="resetForm" class="{{ $ghostBtn }} text-xs">
                                <x-ui::icon name="arrow-path" class="h-3.5 w-3.5" /> New analysis
                            </button>
                        </div>

                        @if($result->summary)
                            <p class="text-sm leading-relaxed text-zinc-300">{{ $result->summary }}</p>
                        @endif

                        @if(!empty($result->matched_keywords))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-emerald-400">{{ __("Matched keywords") }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($result->matched_keywords as $kw)
                                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs text-emerald-300">{{ $kw }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($result->missing_keywords))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-red-400">{{ __("Missing keywords") }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($result->missing_keywords as $kw)
                                        <span class="rounded-full border border-red-400/20 bg-red-500/10 px-3 py-1 text-xs text-red-300">{{ $kw }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($result->gap_analysis))
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="{{ $glassCard }} !p-5">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-500/10">
                                            <x-ui::icon name="alert-triangle" class="h-5 w-5 text-amber-300" />
                                        </div>
                                        <h3 class="font-bold text-white">{{ __("Gap analysis") }}</h3>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach($result->gap_analysis as $gap)
                                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                                <x-ui::icon name="arrow-right" class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" />
                                                {{ $gap }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="{{ $glassCard }} !p-5">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                                            <x-ui::icon name="lightbulb" class="h-5 w-5 text-emerald-300" />
                                        </div>
                                        <h3 class="font-bold text-white">{{ __("Suggestions") }}</h3>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach($result->suggestions as $s)
                                            <li class="flex items-start gap-2 text-sm text-zinc-300">
                                                <x-ui::icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                                {{ $s }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="{{ $glassCard }} flex flex-col items-center justify-center py-20 text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/70">
                            <x-ui::icon name="search" class="h-8 w-8 text-zinc-500" />
                        </div>
                        <p class="text-sm text-zinc-500">{{ __("Your match report will appear here.") }}</p>
                        <p class="mt-1 text-xs text-zinc-600">{{ __("Fill the form and run an analysis.") }}</p>
                    </div>
                @endif
            </section>
        </div>

        {{-- History --}}
        @if($history && $history->isNotEmpty())
        <div class="mt-10 {{ $glassCard }}">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white">{{ __("Recent analyses") }}</h3>
                <p class="text-sm text-zinc-500">{{ __("Click any past analysis to view its full report again") }}</p>
            </div>
            <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                @foreach($history as $h)
                    <button type="button" wire:click="viewResult({{ $h->id }})"
                            class="group flex w-full items-center justify-between gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-4 text-left transition-all duration-200 hover:bg-white/5">
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-200">{{ $h->job_title ?: $h->cv?->title ?: 'Untitled role' }}</p>
                            <p class="text-xs text-zinc-500">{{ $h->created_at->diffForHumans() }} · {{ $h->cv?->title }}</p>
                        </div>
                        <div class="text-right">
                            @if($h->isCompleted())
                                <div class="inline-flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5">
                                    <span class="text-sm font-bold {{ $this->scoreColor($h->compatibility_score ?? 0) }}">{{ $h->compatibility_score ?? '—' }}</span>
                                    <span class="text-[10px] text-zinc-500">{{ $h->grade }}</span>
                                </div>
                            @elseif($h->isFailed())
                                <p class="text-xs text-red-400">{{ __("Failed") }}</p>
                            @else
                                <p class="text-xs text-zinc-400">{{ ucfirst($h->status) }}</p>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
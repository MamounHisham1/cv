<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>

    <div class="relative mx-auto max-w-5xl px-4 py-12 md:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-white md:text-4xl">Job Match</h1>
            <p class="text-sm text-zinc-400">Paste a job description and see how well your CV fits — compatibility score, matched and missing keywords, and concrete suggestions to close the gaps.</p>
        </div>

        @if($shouldPoll)
            <div wire:poll.3s="checkStatus" class="sr-only">polling</div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Input form --}}
            <section class="space-y-4 rounded-2xl border border-white/10 bg-zinc-900/50 p-6">
                <div>
                    <x-ui::label for="selectedCvId">Your CV</x-ui::label>
                    <select id="selectedCvId" wire:model="selectedCvId"
                            class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                        @foreach($cvs as $cv)
                            <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-ui::label for="jobTitle">Job title (optional)</x-ui::label>
                    <x-ui::input id="jobTitle" type="text" wire:model.blur="jobTitle" class="mt-1 w-full" placeholder="Senior Backend Engineer" />
                </div>

                <div>
                    <x-ui::label for="jobDescription">Job description</x-ui::label>
                    <textarea id="jobDescription" wire:model.blur="jobDescription" rows="10"
                              class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100"
                              placeholder="Paste the full job posting here…"></textarea>
                    @error('jobDescription') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-zinc-500">2 credits min</span>
                    <button type="button" wire:click="runMatch" wire:loading.attr="disabled" wire:target="runMatch"
                            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:from-emerald-400 hover:to-emerald-500 disabled:opacity-50">
                        <x-ui::icon name="sparkles" class="h-4 w-4" />
                        <span wire:loading.remove wire:target="runMatch">Analyze match</span>
                        <span wire:loading wire:target="runMatch">Analyzing…</span>
                    </button>
                </div>

                @if($state === 'error')
                    <div class="rounded-lg border border-red-500/20 bg-red-500/5 px-4 py-3 text-sm text-red-300">
                        {{ $errorMessage }}
                    </div>
                @endif
            </section>

            {{-- Result --}}
            <section class="space-y-5">
                @if($state === 'processing')
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-zinc-900/50 p-8 text-zinc-400">
                        <x-ui::icon name="sparkles" class="h-5 w-5 animate-pulse text-emerald-400" />
                        <span>Analyzing your CV against the job description…</span>
                    </div>
                @elseif($state === 'complete' && $result)
                    <div class="space-y-5 rounded-2xl border border-white/10 bg-zinc-900/50 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-zinc-500">Compatibility</p>
                                <p class="text-4xl font-bold {{ $this->scoreColor($result->compatibility_score ?? 0) }}">{{ $result->compatibility_score ?? '—' }}<span class="text-lg text-zinc-500">/100</span></p>
                                <p class="mt-1 text-sm text-zinc-400">Grade {{ $result->grade }}</p>
                            </div>
                            <button type="button" wire:click="resetForm" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300 hover:bg-white/10">New analysis</button>
                        </div>

                        @if($result->summary)
                            <p class="text-sm leading-relaxed text-zinc-300">{{ $result->summary }}</p>
                        @endif

                        @if(!empty($result->matched_keywords))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-emerald-400">Matched keywords</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($result->matched_keywords as $kw)
                                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs text-emerald-300">{{ $kw }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($result->missing_keywords))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-red-400">Missing keywords</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($result->missing_keywords as $kw)
                                        <span class="rounded-full border border-red-400/20 bg-red-500/10 px-3 py-1 text-xs text-red-300">{{ $kw }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($result->gap_analysis))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Gap analysis</p>
                                <ul class="space-y-1.5">
                                    @foreach($result->gap_analysis as $gap)
                                        <li class="flex gap-2 text-sm text-zinc-300"><span class="text-zinc-600">•</span> {{ $gap }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!empty($result->suggestions))
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Suggestions</p>
                                <ul class="space-y-1.5">
                                    @foreach($result->suggestions as $s)
                                        <li class="flex gap-2 text-sm text-zinc-300"><span class="text-emerald-400">→</span> {{ $s }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-white/10 p-8 text-center text-sm text-zinc-500">
                        Your match report will appear here.
                    </div>
                @endif
            </section>
        </div>

        {{-- History --}}
        @if($history && $history->isNotEmpty())
            <div class="mt-10">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-widest text-zinc-500">Recent analyses</h2>
                <div class="space-y-2">
                    @foreach($history as $h)
                        <button type="button" wire:click="viewResult({{ $h->id }})"
                                class="flex w-full items-center justify-between rounded-xl border border-white/10 bg-zinc-900/50 px-4 py-3 text-left transition-all duration-200 hover:bg-zinc-900/70">
                            <div>
                                <p class="text-sm font-medium text-zinc-200">{{ $h->job_title ?: $h->cv?->title ?: 'Untitled role' }}</p>
                                <p class="text-xs text-zinc-500">{{ $h->created_at->diffForHumans() }} · {{ $h->cv?->title }}</p>
                            </div>
                            <div class="text-right">
                                @if($h->isCompleted())
                                    <p class="text-lg font-bold {{ $this->scoreColor($h->compatibility_score ?? 0) }}">{{ $h->compatibility_score ?? '—' }}</p>
                                    <p class="text-xs text-zinc-500">{{ $h->grade }}</p>
                                @elseif($h->isFailed())
                                    <p class="text-xs text-red-400">Failed</p>
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

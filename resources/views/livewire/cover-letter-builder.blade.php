<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>

    @if($shouldPoll)
        <div wire:poll.5000ms.keep-alive="checkGenerationStatus" class="sr-only">polling</div>
    @endif

    <div class="relative mx-auto max-w-6xl px-4 py-12 md:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-white md:text-4xl">Cover Letters</h1>
            <p class="text-sm text-zinc-400">Generate tailored letters from your CVs in seconds, or write your own.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[340px_1fr]">
            {{-- LEFT: letters list --}}
            <aside class="space-y-2">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-500">Your letters</h2>
                    <span class="text-xs text-zinc-600">{{ $letters->count() }}</span>
                </div>

                @forelse($letters as $letter)
                    <button type="button" wire:click="edit({{ $letter->id }})"
                            class="group w-full rounded-xl border px-4 py-3 text-left transition-all duration-200 @if($editingId === $letter->id) border-emerald-400/30 bg-emerald-500/10 @else border-white/10 bg-zinc-900/50 hover:bg-zinc-900/70 @endif">
                        <div class="flex items-start justify-between gap-2">
                            <p class="truncate text-sm font-medium text-zinc-100">{{ $letter->title }}</p>
                            @if($letter->isGenerating())
                                <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-amber-400/20 bg-amber-500/10 px-2 py-0.5 text-[10px] font-medium text-amber-300">
                                    <svg class="h-2.5 w-2.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    Drafting
                                </span>
                            @elseif($letter->isFailed())
                                <span class="shrink-0 rounded-full border border-red-400/20 bg-red-500/10 px-2 py-0.5 text-[10px] font-medium text-red-300">Failed</span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-xs text-zinc-500">
                            {{ \App\CoverLetterTemplates::name($letter->template_id) }} · {{ $letter->updated_at->diffForHumans() }}
                        </p>
                    </button>
                @empty
                    <div class="rounded-xl border border-dashed border-white/10 px-4 py-8 text-center">
                        <x-ui::icon name="document-text" class="mx-auto mb-2 h-8 w-8 text-zinc-700" />
                        <p class="text-sm text-zinc-500">No cover letters yet.</p>
                        <p class="mt-1 text-xs text-zinc-600">Generate one from a CV below.</p>
                    </div>
                @endforelse
            </aside>

            {{-- RIGHT: editor / AI generation --}}
            <section class="space-y-6">
                {{-- GENERATING state: prominent progress card --}}
                @if($generationState === 'generating')
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-amber-400/20 bg-zinc-900/50 py-16 text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-amber-400/30 bg-amber-500/10">
                            <svg class="h-7 w-7 animate-spin text-amber-400" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </div>
                        <h3 class="mb-1 text-lg font-semibold text-white">Drafting your cover letter…</h3>
                        <p class="max-w-sm text-sm text-zinc-400">Our AI is writing a tailored letter from your CV. You can navigate away — we'll notify you when it's ready.</p>
                    </div>
                @endif

                {{-- ERROR state --}}
                @if($generationState === 'error')
                    <div class="rounded-2xl border border-red-500/20 bg-red-500/5 p-6">
                        <div class="flex items-start gap-3">
                            <x-ui::icon name="alert-triangle" class="h-5 w-5 shrink-0 text-red-400" />
                            <div class="flex-1">
                                <h3 class="mb-1 font-semibold text-white">Generation failed</h3>
                                <p class="text-sm text-zinc-400">{{ $errorMessage }}</p>
                            </div>
                            <button type="button" wire:click="resetGenerationState" class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 hover:bg-white/10">Try again</button>
                        </div>
                    </div>
                @endif

                {{-- AI generation panel (only when idle/complete, not while generating) --}}
                @if(in_array($generationState, ['idle', 'complete']))
                <div class="rounded-2xl border border-emerald-400/20 bg-gradient-to-br from-emerald-500/5 to-zinc-900/50 p-6">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/15">
                            <x-ui::icon name="sparkles" class="h-5 w-5 text-emerald-400" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-white">Generate with AI</h2>
                            <p class="text-xs text-zinc-500">Pick a CV and optionally a job description — get a tailored draft in ~15 seconds.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ui::label for="generateCvId">Source CV</x-ui::label>
                            <select id="generateCvId" wire:model="generateCvId"
                                    class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                                <option value="">— Select a CV —</option>
                                @foreach($cvs as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                                @endforeach
                            </select>
                            @error('generateCvId') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-ui::label for="templateId">Template</x-ui::label>
                            <select id="templateId" wire:model="templateId"
                                    class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                                @foreach($templates as $slug => $name)
                                    <option value="{{ $slug }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-ui::label for="jobDescription">Target job description (optional)</x-ui::label>
                        <textarea id="jobDescription" wire:model="jobDescription" rows="3"
                                  class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100"
                                  placeholder="Paste the job posting to tailor the letter to the role…"></textarea>
                        @error('jobDescription') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-5 flex items-center justify-between">
                        <span class="text-xs text-zinc-500">2 credits min · runs in background</span>
                        <button type="button" wire:click="startGeneration" wire:loading.attr="disabled" wire:target="startGeneration"
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:from-emerald-400 hover:to-emerald-500 disabled:opacity-50">
                            <x-ui::icon name="sparkles" class="h-4 w-4" />
                            <span wire:loading.remove wire:target="startGeneration">Generate draft</span>
                            <span wire:loading wire:target="startGeneration">Starting…</span>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Editor form --}}
                <form wire:submit="save" class="space-y-5 rounded-2xl border border-white/10 bg-zinc-900/50 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-widest text-zinc-500">{{ $editingId ? 'Edit letter' : 'Write your own' }}</h2>
                        @if(! $editingId)
                            <button type="button" wire:click="startNew" class="text-xs text-zinc-500 hover:text-zinc-300">Clear</button>
                        @endif
                    </div>

                    <div>
                        <x-ui::label for="title">Title</x-ui::label>
                        <input id="title" type="text" wire:model.blur="title"
                               class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100"
                               placeholder="Cover Letter — Senior Engineer at Acme">
                        @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-ui::label for="sourceCvId">Source CV (optional)</x-ui::label>
                        <select id="sourceCvId" wire:model.blur="sourceCvId"
                                class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                            <option value="">— None (standalone) —</option>
                            @foreach($cvs as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-ui::label for="body">Letter body</x-ui::label>
                        <textarea id="body" wire:model.blur="body" rows="14"
                                  class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm leading-relaxed text-zinc-100"
                                  placeholder="Dear Hiring Manager,…"></textarea>
                        @error('body') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-4">
                        <div class="flex gap-2">
                            @if($editingId)
                                <a href="{{ route('cover-letters.export', [$editingId, 'pdf']) }}"
                                   class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300 hover:bg-white/10">Export PDF</a>
                                <a href="{{ route('cover-letters.export', [$editingId, 'docx']) }}"
                                   class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300 hover:bg-white/10">Export DOCX</a>
                                <button type="button" @click="confirmAction({title: 'Delete this cover letter?', message: 'This cannot be undone.', method: 'delete', params: [{{ $editingId }}], source: $el, danger: true, confirmLabel: 'Delete'})"
                                        class="rounded-lg border border-red-500/20 bg-red-500/5 px-3 py-2 text-xs text-red-400 hover:bg-red-500/20">Delete</button>
                            @endif
                        </div>
                        <button type="submit"
                                class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-500">
                            {{ $editingId ? 'Save changes' : 'Create letter' }}
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

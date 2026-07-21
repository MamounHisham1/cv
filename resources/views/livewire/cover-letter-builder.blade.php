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
        <div wire:poll.5000ms.keep-alive="checkGenerationStatus" class="sr-only">polling</div>
    @endif

    <div class="relative mx-auto max-w-6xl px-4 py-10 md:px-6 lg:px-8">

        {{-- Page header --}}
        <div class="mb-10 text-center">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">
                AI Powered
            </div>
            <h1 class="mb-3 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                Cover <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">{{ __("Letters") }}</span>
            </h1>
            <p class="mx-auto max-w-lg text-base text-zinc-400">
                Generate tailored letters from your CVs in seconds, or write your own — then send straight from your inbox.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[340px_1fr]">
            {{-- LEFT: letters list --}}
            <aside class="{{ $glassCard }} !p-4">
                <div class="mb-3 flex items-center justify-between border-b border-white/10 pb-3">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-500">{{ __("Your letters") }}</h2>
                    <span class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-medium text-zinc-400">{{ $letters->count() }}</span>
                </div>

                <div class="space-y-2">
                    @forelse($letters as $letter)
                        <div
                            x-data="{ copied: false, copyBody(e) { e.stopPropagation(); navigator.clipboard.writeText(@js($letter->body)).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); } }"
                            wire:click="edit({{ $letter->id }})"
                            class="group relative w-full cursor-pointer rounded-xl border px-4 py-3 text-left transition-all duration-200 @if($editingId === $letter->id) border-emerald-400/30 bg-emerald-500/10 @else border-white/10 bg-zinc-900/50 hover:bg-zinc-900/70 @endif">
                            <div class="flex items-start justify-between gap-2 pr-6">
                                <p class="truncate text-sm font-medium text-zinc-100">{{ $letter->title }}</p>
                                @if($letter->isGenerating())
                                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-amber-400/20 bg-amber-500/10 px-2 py-0.5 text-[10px] font-medium text-amber-300">
                                        <svg class="h-2.5 w-2.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Drafting
                                    </span>
                                @elseif($letter->isFailed())
                                    <span class="shrink-0 rounded-full border border-red-400/20 bg-red-500/10 px-2 py-0.5 text-[10px] font-medium text-red-300">{{ __("Failed") }}</span>
                                @endif
                            </div>
                            <p class="mt-0.5 text-xs text-zinc-500">
                                {{ \App\CoverLetterTemplates::name($letter->template_id) }} · {{ $letter->updated_at->diffForHumans() }}
                            </p>
                            @if($letter->body && ! $letter->isGenerating())
                                <button type="button" @click="copyBody($event)"
                                        class="absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-lg text-zinc-500 transition-all hover:bg-white/10 hover:text-zinc-200"
                                        title="Copy letter">
                                    <x-ui::icon name="copy" class="h-3.5 w-3.5" />
                                    <span x-show="copied" x-cloak class="absolute -bottom-5 right-0 rounded bg-emerald-600 px-1.5 py-0.5 text-[9px] text-white">{{ __("Copied") }}</span>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-white/10 px-4 py-8 text-center">
                            <x-ui::icon name="document-text" class="mx-auto mb-2 h-8 w-8 text-zinc-700" />
                            <p class="text-sm text-zinc-500">{{ __("No cover letters yet.") }}</p>
                            <p class="mt-1 text-xs text-zinc-600">{{ __("Generate one from a CV below.") }}</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- RIGHT: editor / AI generation --}}
            <section class="space-y-6">

                {{-- GENERATING state: prominent progress card --}}
                @if($generationState === 'generating')
                    <div class="{{ $glassCard }} mb-6 py-16 text-center">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::spinner size="lg" class="text-emerald-300" />
                        </div>
                        <h2 class="mb-3 text-xl font-bold text-white">{{ __("Drafting your cover letter…") }}</h2>
                        <p class="text-sm text-zinc-400">Our AI is writing a tailored letter from your CV. You can navigate away — we'll notify you when it's ready.</p>
                    </div>
                @endif

                {{-- ERROR state --}}
                @if($generationState === 'error')
                    <div class="{{ $glassCard }} mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-red-400/20 bg-red-500/10">
                                <x-ui::icon name="alert-triangle" class="h-6 w-6 text-red-400" />
                            </div>
                            <div class="flex-1">
                                <h3 class="mb-1 font-semibold text-white">{{ __("Generation failed") }}</h3>
                                <p class="text-sm text-zinc-400">{{ $errorMessage }}</p>
                            </div>
                            <button type="button" wire:click="resetGenerationState" class="{{ $ghostBtn }} text-xs">
                                <x-ui::icon name="refresh-cw" class="h-4 w-4" /> Try again
                            </button>
                        </div>
                    </div>
                @endif

                {{-- AI generation panel --}}
                @if(in_array($generationState, ['idle', 'complete']))
                <div class="{{ $glassCard }}">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::icon name="sparkles" class="h-5 w-5 text-emerald-300" />
                        </div>
                        <div>
                            <h2 class="font-bold text-white">{{ __("Generate with AI") }}</h2>
                            <p class="text-sm text-zinc-500">{{ __("Pick a CV and optionally a job description — get a tailored draft in ~15 seconds.") }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ui::label for="generateCvId">{{ __("Source CV") }}</x-ui::label>
                            <select id="generateCvId" wire:model="generateCvId" class="{{ $fieldInput }}">
                                <option value="">{{ __("— Select a CV —") }}</option>
                                @foreach($cvs as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                                @endforeach
                            </select>
                            @error('generateCvId') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-ui::label for="templateId">{{ __("Template") }}</x-ui::label>
                            <select id="templateId" wire:model="templateId" class="{{ $fieldInput }}">
                                @foreach($templates as $slug => $name)
                                    <option value="{{ $slug }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-ui::label for="jobDescription">Target job description (optional)</x-ui::label>
                        <textarea id="jobDescription" wire:model="jobDescription" rows="3" class="{{ $fieldInput }}"
                                  placeholder="Paste the job posting to tailor the letter to the role…"></textarea>
                        @error('jobDescription') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs text-zinc-500">{{ __("2 credits min · runs in background") }}</span>
                        <button type="button" wire:click="startGeneration" wire:loading.attr="disabled" wire:target="startGeneration" class="{{ $primaryBtn }}">
                            <x-ui::icon name="sparkles" class="h-4 w-4" />
                            <span wire:loading.remove wire:target="startGeneration">{{ __("Generate draft") }}</span>
                            <span wire:loading wire:target="startGeneration">{{ __("Starting…") }}</span>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Editor form --}}
                <form wire:submit="save" class="{{ $glassCard }} space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-semibold uppercase tracking-widest text-zinc-500">{{ $editingId ? 'Edit letter' : 'Write your own' }}</h2>
                        @if(! $editingId)
                            <button type="button" wire:click="startNew" class="text-xs text-zinc-500 hover:text-zinc-300">{{ __("Clear") }}</button>
                        @endif
                    </div>

                    <div>
                        <x-ui::label for="title">{{ __("Title") }}</x-ui::label>
                        <input id="title" type="text" wire:model.blur="title" class="{{ $fieldInput }}"
                               placeholder="Cover Letter — Senior Engineer at Acme">
                        @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-ui::label for="sourceCvId">Source CV (optional)</x-ui::label>
                        <select id="sourceCvId" wire:model.blur="sourceCvId" class="{{ $fieldInput }}">
                            <option value="">— None (standalone) —</option>
                            @foreach($cvs as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-ui::label for="body">{{ __("Letter body") }}</x-ui::label>
                        <textarea id="body" wire:model.blur="body" rows="14" class="{{ $fieldInput }}"
                                  placeholder="Dear Hiring Manager,…"></textarea>
                        @error('body') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-4">
                        <div class="flex flex-wrap gap-2">
                            @if($editingId)
                                <a href="{{ route('cover-letters.export', [$editingId, 'pdf']) }}" wire:navigate
                                   class="{{ $ghostBtn }} text-xs">
                                    <x-ui::icon name="download" class="h-3.5 w-3.5" /> Export PDF
                                </a>
                                <a href="{{ route('cover-letters.export', [$editingId, 'docx']) }}" wire:navigate
                                   class="{{ $ghostBtn }} text-xs">
                                    <x-ui::icon name="download" class="h-3.5 w-3.5" /> Export DOCX
                                </a>
                                <button type="button" @click="confirmAction({title: 'Delete this cover letter?', message: 'This cannot be undone.', method: 'delete', params: [{{ $editingId }}], source: $el, danger: true, confirmLabel: 'Delete'})"
                                        class="inline-flex items-center gap-2 rounded-full border border-red-400/20 bg-red-500/10 px-4 py-2 text-xs font-medium text-red-300 backdrop-blur-sm transition-all duration-300 hover:bg-red-500/20 hover:text-red-200">{{ __("Delete") }}</button>
                            @endif
                        </div>
                        <button type="submit" class="{{ $primaryBtn }}">
                            {{ $editingId ? 'Save changes' : 'Create letter' }}
                        </button>
                    </div>
                </form>

                {{-- Email-ready: opens the user's mail client pre-filled --}}
                <div
                    x-data="{
                        recipient: '',
                        subject: 'Application for the role',
                        getBody() {
                            const ta = document.getElementById('body');
                            return ta ? ta.value : '';
                        },
                        buildMailto() {
                            const subject = encodeURIComponent(this.subject || 'Application for the role');
                            const to = encodeURIComponent(this.recipient || '');
                            const b = encodeURIComponent(this.getBody() || '');
                            return 'mailto:' + to + '?subject=' + subject + '&body=' + b;
                        }
                    }"
                    class="{{ $glassCard }}"
                >
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-500/10">
                            <x-ui::icon name="mail" class="h-5 w-5 text-blue-300" />
                        </div>
                        <div>
                            <h2 class="font-bold text-white">Email-ready</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-zinc-400">Recipient (employer email)</label>
                            <input type="email" x-model="recipient" class="{{ $fieldInput }}" placeholder="hiring@acme.com">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-zinc-400">{{ __("Subject") }}</label>
                            <input type="text" x-model="subject" class="{{ $fieldInput }}" placeholder="Application for Senior Engineer">
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <a :href="buildMailto()"
                           class="inline-flex items-center gap-2 rounded-full border border-blue-400/20 bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-blue-400 hover:to-blue-500">
                            <x-ui::icon name="mail" class="h-4 w-4" />
                            Open in email
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
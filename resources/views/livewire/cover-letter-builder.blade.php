<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>

    <div class="relative mx-auto max-w-6xl px-4 py-12 md:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-white md:text-4xl">Cover Letters</h1>
                <p class="text-sm text-zinc-400">Draft tailored letters from your CVs, or write your own.</p>
            </div>
            <button type="button" wire:click="startNew"
                    class="inline-flex items-center justify-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500">
                <x-ui::icon name="plus" class="h-4 w-4" />
                New Cover Letter
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[320px_1fr]">
            {{-- Letters list --}}
            <aside class="space-y-2">
                @forelse($letters as $letter)
                    <button type="button" wire:click="edit({{ $letter->id }})"
                            class="w-full rounded-xl border px-4 py-3 text-left transition-all duration-200 @if($editingId === $letter->id) border-emerald-400/30 bg-emerald-500/10 @else border-white/10 bg-zinc-900/50 hover:bg-zinc-900/70 @endif">
                        <p class="truncate text-sm font-medium text-zinc-100">{{ $letter->title }}</p>
                        <p class="mt-0.5 text-xs text-zinc-500">
                            {{ \App\CoverLetterTemplates::name($letter->template_id) }} · {{ $letter->updated_at->diffForHumans() }}
                        </p>
                    </button>
                @empty
                    <p class="rounded-xl border border-dashed border-white/10 px-4 py-6 text-center text-sm text-zinc-500">
                        No cover letters yet. Click "New Cover Letter" to start.
                    </p>
                @endforelse
            </aside>

            {{-- Editor + AI generation --}}
            <section class="space-y-6">
                @if($editingId || $errors->any() || true)
                <form wire:submit="save" class="space-y-5 rounded-2xl border border-white/10 bg-zinc-900/50 p-6">
                    <div>
                        <x-ui::label for="title">Title</x-ui::label>
                        <x-ui::input id="title" type="text" wire:model.blur="title" class="mt-1 w-full" placeholder="Cover Letter — Senior Engineer at Acme" />
                        @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ui::label for="templateId">Template</x-ui::label>
                            <select id="templateId" wire:model.blur="templateId"
                                    class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                                @foreach($templates as $slug => $name)
                                    <option value="{{ $slug }}">{{ $name }}</option>
                                @endforeach
                            </select>
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
                    </div>

                    <div>
                        <x-ui::label for="body">Letter body</x-ui::label>
                        <textarea id="body" wire:model.blur="body" rows="14"
                                  class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100"
                                  placeholder="Dear Hiring Manager,…"></textarea>
                        @error('body') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-xs text-zinc-500">PDF/DOCX export uses the selected template + your CV's contact info.</p>
                        <div class="flex gap-2">
                            @if($editingId)
                                <a href="{{ route('cover-letters.export', [$editingId, 'pdf']) }}"
                                   class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300 hover:bg-white/10">Export PDF</a>
                                <a href="{{ route('cover-letters.export', [$editingId, 'docx']) }}"
                                   class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300 hover:bg-white/10">Export DOCX</a>
                                <button type="button" @click="confirmAction({title: 'Delete this cover letter?', message: 'This cannot be undone.', method: 'delete', params: [{{ $editingId }}], source: $el, danger: true, confirmLabel: 'Delete'})"
                                        class="rounded-lg border border-red-500/20 bg-red-500/5 px-3 py-2 text-xs text-red-400 hover:bg-red-500/20">Delete</button>
                            @endif
                            <button type="submit"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-medium text-white hover:bg-emerald-500">
                                {{ $editingId ? 'Save changes' : 'Create letter' }}
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                {{-- AI generation --}}
                <div class="space-y-4 rounded-2xl border border-white/10 bg-zinc-900/50 p-6" wire:loading.delay.remove>
                    <div class="flex items-center gap-2">
                        <x-ui::icon name="sparkles" class="h-5 w-5 text-emerald-400" />
                        <h2 class="text-sm font-semibold uppercase tracking-widest text-zinc-400">AI Draft</h2>
                    </div>
                    <p class="text-xs text-zinc-500">Generate a first draft from one of your CVs, optionally tailored to a job description. Costs credits based on usage. The draft lands in the editor above — edit freely before saving.</p>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-ui::label for="generateCvId">Generate from CV</x-ui::label>
                            <select id="generateCvId" wire:model="generateCvId"
                                    class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100">
                                <option value="">— Select a CV —</option>
                                @foreach($cvs as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-ui::label for="jobDescription">Target job description (optional)</x-ui::label>
                            <textarea id="jobDescription" wire:model="jobDescription" rows="5"
                                      class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-zinc-100"
                                      placeholder="Paste the job posting here to tailor the letter…"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-2 text-sm font-medium text-white hover:from-emerald-400 hover:to-emerald-500 disabled:opacity-50">
                            <x-ui::icon name="sparkles" class="h-4 w-4" />
                            <span wire:loading.remove wire:target="generate">Generate draft</span>
                            <span wire:loading wire:target="generate">Generating…</span>
                        </button>
                        <span class="text-xs text-zinc-500">2 credits min</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

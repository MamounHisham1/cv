<div
    class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100"
    x-data="{
        copy(url) { navigator.clipboard.writeText(url) }
    }"
    @share-link-ready.window="copy($event.detail.url)"
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-64 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(16,185,129,0.06),_transparent_55%)]"></div>

    <div class="relative mx-auto max-w-5xl px-4 py-12 md:px-6 lg:px-8">
        <div>
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 class="mb-2 text-3xl font-bold text-white md:text-4xl">My CVs</h1>
                    <p class="text-sm text-zinc-400">You have {{ $cvs->count() }} CV{{ $cvs->count() > 1 ? 's' : '' }}</p>
                </div>
                <a href="{{ route('cv.builder', ['onboarding' => 1]) }}" wire:navigate
                   class="inline-flex items-center justify-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30">
                    <x-ui::icon name="plus" class="h-4 w-4" />
                    Create New CV
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($cvs as $cv)
                <div class="card-hover group relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 p-6 transition-all duration-300 hover:border-white/20 hover:bg-zinc-900/70">
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                            <x-ui::icon name="file-text" class="h-6 w-6 text-zinc-400" />
                        </div>
                        <div class="flex flex-wrap gap-1 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <a href="{{ route('cv.evaluator', $cv) }}" wire:navigate
                               class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Evaluate">
                                <x-ui::icon name="sparkles" class="h-4 w-4" />
                            </a>
                            <a href="{{ route('cv.edit', $cv) }}" wire:navigate
                               class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Edit">
                                <x-ui::icon name="pencil" class="h-4 w-4" />
                            </a>
                            <a href="{{ route('cv.preview', $cv) }}" target="_blank"
                               class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Preview">
                                <x-ui::icon name="eye" class="h-4 w-4" />
                            </a>
                            <a href="{{ route('cv.export', [$cv, 'pdf']) }}"
                               class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Download PDF">
                                <x-ui::icon name="download" class="h-4 w-4" />
                            </a>
                            <button type="button" wire:click="duplicate({{ $cv->id }})" wire:loading.attr="disabled"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white disabled:opacity-30" title="Duplicate">
                                <x-ui::icon name="copy" class="h-4 w-4" />
                            </button>
                            @if($cv->isShared())
                                <button type="button" wire:click="confirmUnshare({{ $cv->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-400/20 bg-emerald-500/10 text-emerald-300 transition-all duration-200 hover:bg-emerald-500/20" title="Sharing — click to disable">
                                    <x-ui::icon name="globe" class="h-4 w-4" />
                                </button>
                            @else
                                <button type="button" wire:click="share({{ $cv->id }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Create share link">
                                    <x-ui::icon name="globe" class="h-4 w-4" />
                                </button>
                            @endif
                            <button type="button" wire:click="confirmDelete({{ $cv->id }})"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/5 text-red-400 transition-all duration-200 hover:bg-red-500/20" title="Delete">
                                <x-ui::icon name="trash" class="h-4 w-4" />
                            </button>
                            <button type="button" wire:click="viewVersions({{ $cv->id }})"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Version history">
                                <x-ui::icon name="clock" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <h3 class="mb-1 text-lg font-semibold text-white">{{ $cv->title }}</h3>
                    <p class="mb-4 text-xs text-zinc-500">Last edited {{ $cv->updated_at->diffForHumans() }}</p>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-xs text-zinc-400">
                            <x-ui::icon name="briefcase" class="h-3.5 w-3.5" />
                            <span>{{ $cv->experiences->count() }} Experience{{ $cv->experiences->count() != 1 ? 's' : '' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-zinc-400">
                            <x-ui::icon name="zap" class="h-3.5 w-3.5" />
                            <span>{{ $cv->skills->count() }} Skill{{ $cv->skills->count() != 1 ? 's' : '' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-zinc-400">
                            <x-ui::icon name="trophy" class="h-3.5 w-3.5" />
                            <span>{{ $cv->certifications->count() }} Certification{{ $cv->certifications->count() != 1 ? 's' : '' }}</span>
                        </div>
                        @if($cv->isShared())
                            <div class="flex items-center gap-2 text-xs text-emerald-400">
                                <x-ui::icon name="globe" class="h-3.5 w-3.5" />
                                <span>Public link active</span>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('cv.edit', $cv) }}" wire:navigate
                       class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                        <span>Edit CV</span>
                        <x-ui::icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Delete confirmation modal --}}
    @if($confirmingDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" wire:click="cancelConfirm">
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-zinc-900 p-6 shadow-2xl" wire:click.stop>
            <h3 class="mb-2 text-lg font-semibold text-white">Delete this CV?</h3>
            <p class="mb-5 text-sm text-zinc-400">This permanently removes the CV and all of its sections. This cannot be undone.</p>
            <div class="flex justify-end gap-2">
                <button type="button" wire:click="cancelConfirm" class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm text-zinc-300 hover:bg-white/10">Cancel</button>
                <button type="button" wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete CV</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Unshare confirmation modal --}}
    @if($confirmingUnshare)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" wire:click="cancelConfirm">
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-zinc-900 p-6 shadow-2xl" wire:click.stop>
            <h3 class="mb-2 text-lg font-semibold text-white">Disable public sharing?</h3>
            <p class="mb-5 text-sm text-zinc-400">The share link will stop working immediately. Anyone who has it open will lose access.</p>
            <div class="flex justify-end gap-2">
                <button type="button" wire:click="cancelConfirm" class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm text-zinc-300 hover:bg-white/10">Cancel</button>
                <button type="button" wire:click="unshare" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">Disable sharing</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Version history modal --}}
    @if($viewingVersionsFor)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" wire:click="closeVersions">
        <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-zinc-900 p-6 shadow-2xl" wire:click.stop>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Version history</h3>
                <button type="button" wire:click="closeVersions" class="text-zinc-400 hover:text-white">
                    <x-ui::icon name="x" class="h-5 w-5" />
                </button>
            </div>
            @if($versionsFor && $versionsFor->isNotEmpty())
                <p class="mb-3 text-xs text-zinc-500">Snapshots are captured automatically when you export a CV. Reverting restores that state and captures your current state first so you can undo it.</p>
                <div class="max-h-80 space-y-2 overflow-y-auto pr-1">
                    @foreach($versionsFor as $version)
                        <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-200">{{ $version->label ?: 'Snapshot' }}</p>
                                <p class="text-xs text-zinc-500">{{ $version->created_at->toDayDateTimeString() }}</p>
                            </div>
                            <button type="button" wire:click="revertTo({{ $version->id }})" wire:loading.attr="disabled" wire:target="revertTo({{ $version->id }})"
                                    class="shrink-0 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-300 hover:bg-white/10 hover:text-white disabled:opacity-30">
                                Restore
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-6 text-center text-sm text-zinc-500">No versions yet. They're created automatically when you export this CV.</p>
            @endif
        </div>
    </div>
    @endif
</div>

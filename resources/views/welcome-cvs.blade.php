<x-layouts::app :title="__('My CVs')">
    <div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
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
                            <div class="flex gap-1 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                <a href="{{ route('cv.evaluator', $cv) }}" wire:navigate
                                   class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white" title="Evaluate">
                                    <x-ui::icon name="sparkles" class="h-4 w-4" />
                                </a>
                                <a href="{{ route('cv.edit', $cv) }}" wire:navigate
                                   class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                    <x-ui::icon name="pencil" class="h-4 w-4" />
                                </a>
                                <a href="{{ route('cv.preview', $cv) }}" target="_blank"
                                   class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                    <x-ui::icon name="eye" class="h-4 w-4" />
                                </a>
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
    </div>
</x-layouts::app>

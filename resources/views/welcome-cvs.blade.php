<x-layouts::app :title="__('My CVs')">
    <div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-64 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(16,185,129,0.06),_transparent_55%)]"></div>

        <div class="relative mx-auto max-w-5xl px-4 py-12 md:px-6 lg:px-8">
            @if($cvs->isEmpty())
            {{-- No CVs: Show two clear options --}}
            <div class="flex flex-col items-center text-center">
                <div class="mb-8 flex h-24 w-24 items-center justify-center rounded-3xl border border-emerald-400/20 bg-emerald-500/10">
                    <x-ui::icon name="file-text" class="h-12 w-12 text-emerald-400" />
                </div>

                <h1 class="mb-4 text-4xl font-bold text-white md:text-5xl">
                    Let's build your <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">perfect CV</span>
                </h1>
                <p class="mb-12 max-w-lg text-lg text-zinc-400">
                    Choose the easiest way to get started
                </p>

                <div class="grid w-full max-w-2xl grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Primary: Upload CV --}}
                    <a href="{{ route('cv.evaluator') }}" wire:navigate
                       class="group card-hover relative overflow-hidden rounded-3xl border-2 border-emerald-400/30 bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 p-8 text-left transition-all duration-300 hover:border-emerald-400/50 hover:shadow-2xl hover:shadow-emerald-500/20">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-emerald-400/30 bg-emerald-500/20">
                            <x-ui::icon name="upload" class="h-7 w-7 text-emerald-300" />
                        </div>
                        <h2 class="mb-3 text-2xl font-bold text-white">Upload Existing CV</h2>
                        <p class="mb-4 text-base leading-relaxed text-zinc-300">
                            Upload your current CV and we'll extract the data automatically. Just review and edit.
                        </p>
                        <div class="flex items-center gap-2 text-sm font-medium text-emerald-300">
                            <x-ui::icon name="check-circle" class="h-4 w-4" />
                            <span>Fastest option</span>
                        </div>
                        <div class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/20">
                            <x-ui::icon name="arrow-right" class="h-4 w-4 text-emerald-300" />
                        </div>
                    </a>

                    {{-- Secondary: Create New --}}
                    <a href="{{ route('cv.builder', ['onboarding' => 1]) }}" wire:navigate
                       class="card-hover group relative overflow-hidden rounded-3xl border border-white/10 bg-zinc-900/50 p-8 text-left transition-all duration-300 hover:border-white/20 hover:bg-zinc-900/70">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                            <x-ui::icon name="plus" class="h-7 w-7 text-zinc-400" />
                        </div>
                        <h2 class="mb-3 text-2xl font-bold text-white">Create from Scratch</h2>
                        <p class="mb-4 text-base leading-relaxed text-zinc-400">
                            Start with a blank canvas and build your CV step by step with AI assistance.
                        </p>
                        <div class="flex items-center gap-2 text-sm font-medium text-zinc-400">
                            <x-ui::icon name="sparkles" class="h-4 w-4" />
                            <span>AI-powered guidance</span>
                        </div>
                        <div class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-white/5">
                            <x-ui::icon name="arrow-right" class="h-4 w-4 text-zinc-400" />
                        </div>
                    </a>
                </div>
            </div>

            @else
            {{-- Has CVs: Show list --}}
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
            @endif
        </div>
    </div>
</x-layouts::app>

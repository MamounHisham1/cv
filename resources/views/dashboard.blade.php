<x-layouts::app :title="__('Dashboard')">
    <div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-96 bg-[radial-gradient(ellipse_at_top_right,_rgba(16,185,129,0.12),_transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-64 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(16,185,129,0.06),_transparent_55%)]"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-8 md:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="mb-1 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-400">Dashboard</p>
                    <h1 class="text-3xl font-bold text-white md:text-4xl">
                        Welcome back, <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="mt-1 text-sm text-zinc-400">Ready to build your perfect CV? Let's get started.</p>
                </div>
                <a href="{{ route('cv.builder') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30 lg:self-end">
                    <x-ui::icon name="plus" class="h-4 w-4" />
                    Create New CV
                </a>
            </div>

            {{-- Stats --}}
            <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/80 p-5 backdrop-blur-xl">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10">
                        <x-ui::icon name="file-text" class="h-5 w-5 text-emerald-400" />
                    </div>
                    <p class="text-2xl font-bold text-white">0</p>
                    <p class="text-xs text-zinc-500">Total CVs</p>
                </div>
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/80 p-5 backdrop-blur-xl">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-500/10">
                        <x-ui::icon name="eye" class="h-5 w-5 text-blue-400" />
                    </div>
                    <p class="text-2xl font-bold text-white">0</p>
                    <p class="text-xs text-zinc-500">Views This Week</p>
                </div>
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/80 p-5 backdrop-blur-xl">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl border border-purple-400/20 bg-purple-500/10">
                        <x-ui::icon name="sparkles" class="h-5 w-5 text-purple-400" />
                    </div>
                    <p class="text-2xl font-bold text-white">0</p>
                    <p class="text-xs text-zinc-500">AI Suggestions</p>
                </div>
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/80 p-5 backdrop-blur-xl">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-500/10">
                        <x-ui::icon name="clock" class="h-5 w-5 text-amber-400" />
                    </div>
                    <p class="text-2xl font-bold text-white">0h</p>
                    <p class="text-xs text-zinc-500">Time Saved</p>
                </div>
            </div>

            {{-- Main content --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Recent CVs --}}
                <div class="lg:col-span-2">
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 backdrop-blur-xl">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-white">Recent CVs</h2>
                            <a href="{{ route('cv.builder') }}" wire:navigate
                               class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                View All
                                <x-ui::icon name="arrow-right" class="h-3.5 w-3.5" />
                            </a>
                        </div>

                        <div class="flex flex-col items-center justify-center py-14 text-center">
                            <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/80">
                                <x-ui::icon name="file-text" class="h-9 w-9 text-zinc-500" />
                            </div>
                            <h3 class="mb-2 font-semibold text-white">No CVs Yet</h3>
                            <p class="mb-6 max-w-sm text-sm leading-relaxed text-zinc-400">
                                Start building your professional CV with our AI-powered builder. Create ATS-optimized resumes that get you noticed.
                            </p>
                            <a href="{{ route('cv.builder') }}" wire:navigate
                               class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500">
                                <x-ui::icon name="plus" class="h-4 w-4" />
                                Create Your First CV
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Quick Actions --}}
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 backdrop-blur-xl">
                        <h2 class="mb-5 text-lg font-bold text-white">Quick Actions</h2>
                        <div class="space-y-2">
                            <a href="{{ route('cv.builder') }}" wire:navigate
                               class="flex items-center gap-3 rounded-2xl border border-white/5 bg-white/5 px-4 py-3 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                <x-ui::icon name="plus" class="h-4 w-4 text-emerald-400" />
                                Create New CV
                            </a>
                            <a href="{{ route('cv.builder') }}" wire:navigate
                               class="flex items-center gap-3 rounded-2xl border border-white/5 bg-white/5 px-4 py-3 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                <x-ui::icon name="upload" class="h-4 w-4 text-blue-400" />
                                Import Existing CV
                            </a>
                            <a href="{{ route('cv.evaluator') }}" wire:navigate
                               class="flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300 transition-all duration-200 hover:bg-emerald-500/15 hover:text-emerald-200">
                                <x-ui::icon name="sparkles" class="h-4 w-4 text-emerald-400" />
                                AI CV Evaluator
                                <span class="ml-auto rounded-full border border-emerald-400/30 bg-emerald-500/20 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-emerald-300">New</span>
                            </a>
                            <a href="{{ route('cv.builder') }}" wire:navigate
                               class="flex items-center gap-3 rounded-2xl border border-white/5 bg-white/5 px-4 py-3 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                                <x-ui::icon name="copy" class="h-4 w-4 text-purple-400" />
                                Duplicate Template
                            </a>
                        </div>
                    </div>

                    {{-- Pro Tip --}}
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 backdrop-blur-xl">
                        <div class="mb-3 flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10">
                                <x-ui::icon name="lightbulb" class="h-4 w-4 text-emerald-300" />
                            </div>
                            <h3 class="font-bold text-white">Pro Tip</h3>
                        </div>
                        <p class="mb-4 text-sm leading-relaxed text-zinc-400">
                            Tailor your CV for each job application. Our AI can help identify the right keywords and optimize your resume for Applicant Tracking Systems (ATS).
                        </p>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                            <x-ui::icon name="sparkles" class="h-3 w-3" />
                            ATS Optimized
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layouts::app>

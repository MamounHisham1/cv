<x-layouts::app :title="__('Dashboard')">
    <div class="max-w-[1800px] mx-auto p-4 md:p-6 lg:p-8">
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-white mb-2">
                        Welcome back, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-sm md:text-base text-zinc-600 dark:text-zinc-400">
                        Ready to build your perfect CV? Let's get started.
                    </p>
                </div>
                <x-ui::button variant="primary" href="{{ route('cv.builder') }}" icon="plus" class="w-full lg:w-auto">
                    Create New CV
                </x-ui::button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-ui::icon name="file-text" size="lg" class="text-emerald-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Total CVs</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <x-ui::icon name="eye" size="lg" class="text-blue-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Views This Week</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <x-ui::icon name="sparkles" size="lg" class="text-purple-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">AI Suggestions</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-ui::icon name="clock" size="lg" class="text-amber-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0h</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Time Saved</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <x-ui::card class="card-hover">
                    <div class="flex items-center justify-between mb-6">
                        <x-ui::heading size="lg">Recent CVs</x-ui::heading>
                        <x-ui::button variant="ghost" size="sm" href="{{ route('cv.builder') }}" icon="arrow-right" class="text-emerald-600 hover:text-emerald-700">
                            View All
                        </x-ui::button>
                    </div>

                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <x-ui::icon name="file-text" size="xl" class="text-zinc-400" />
                        </div>
                        <x-ui::heading size="md" class="mb-2">No CVs Yet</x-ui::heading>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                            Start building your professional CV with our AI-powered builder. Create ATS-optimized resumes that get you noticed.
                        </p>
                        <x-ui::button variant="primary" href="{{ route('cv.builder') }}" icon="plus">
                            Create Your First CV
                        </x-ui::button>
                    </div>
                </x-ui::card>
            </div>

            <div class="space-y-6">
                <x-ui::card class="card-hover">
                    <x-ui::heading size="lg" class="mb-4">Quick Actions</x-ui::heading>
                    <div class="space-y-3">
                        <x-ui::button variant="ghost" href="{{ route('cv.builder') }}" icon="plus" class="w-full justify-start">
                            Create New CV
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="{{ route('cv.builder') }}" icon="refresh-cw" class="w-full justify-start">
                            Import Existing CV
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="{{ route('cv.builder') }}" icon="sparkles" class="w-full justify-start">
                            AI Resume Review
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="{{ route('cv.builder') }}" icon="copy" class="w-full justify-start">
                            Duplicate Template
                        </x-ui::button>
                    </div>
                </x-ui::card>

                <x-ui::card class="card-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <x-ui::icon name="lightbulb" size="md" class="text-emerald-600" />
                        </div>
                        <x-ui::heading size="lg">Pro Tip</x-ui::heading>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                        Tailor your CV for each job application. Our AI can help identify the right keywords and optimize your resume for Applicant Tracking Systems (ATS).
                    </p>
                    <x-ui::badge variant="outline">
                        <x-ui::icon name="sparkles" class="mr-1" style="width: 12px; height: 12px;" />
                        ATS Optimized
                    </x-ui::badge>
                </x-ui::card>
            </div>
        </div>
    </div>
</x-layouts::app>

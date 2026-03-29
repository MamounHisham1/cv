<x-layouts::app :title="__('Dashboard')">
    <div class="max-w-[1800px] mx-auto p-4 md:p-6 lg:p-8">
        <!-- Welcome Header -->
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
                <flux:button variant="primary" href="{{ route('cv.builder') }}" icon="plus" class="w-full lg:w-auto">
                    Create New CV
                </flux:button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <flux:icon name="document-text" class="w-6 h-6 text-emerald-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Total CVs</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <flux:icon name="eye" class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Views This Week</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <flux:icon name="sparkles" class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">AI Suggestions</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <flux:icon name="clock" class="w-6 h-6 text-amber-600" />
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white mb-1">0h</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Time Saved</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent CVs -->
            <div class="lg:col-span-2">
                <flux:card class="card-hover">
                    <div class="flex items-center justify-between mb-6">
                        <flux:heading size="lg">Recent CVs</flux:heading>
                        <flux:button variant="ghost" size="sm" href="{{ route('cv.builder') }}" icon="arrow-right" class="text-emerald-600 hover:text-emerald-700">
                            View All
                        </flux:button>
                    </div>

                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <flux:icon name="document-text" class="w-10 h-10 text-zinc-400" />
                        </div>
                        <flux:heading size="md" class="mb-2">No CVs Yet</flux:heading>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                            Start building your professional CV with our AI-powered builder. Create ATS-optimized resumes that get you noticed.
                        </p>
                        <flux:button variant="primary" href="{{ route('cv.builder') }}" icon="plus">
                            Create Your First CV
                        </flux:button>
                    </div>
                </flux:card>
            </div>

            <!-- Quick Actions & Tips -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <flux:card class="card-hover">
                    <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
                    <div class="space-y-3">
                        <flux:button variant="ghost" href="{{ route('cv.builder') }}" icon="plus" class="w-full justify-start">
                            Create New CV
                        </flux:button>
                        <flux:button variant="ghost" href="{{ route('cv.builder') }}" icon="arrow-path" class="w-full justify-start">
                            Import Existing CV
                        </flux:button>
                        <flux:button variant="ghost" href="{{ route('cv.builder') }}" icon="sparkles" class="w-full justify-start">
                            AI Resume Review
                        </flux:button>
                        <flux:button variant="ghost" href="{{ route('cv.builder') }}" icon="document-duplicate" class="w-full justify-start">
                            Duplicate Template
                        </flux:button>
                    </div>
                </flux:card>

                <!-- Pro Tips -->
                <flux:card class="card-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <flux:icon name="light-bulb" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <flux:heading size="lg">Pro Tip</flux:heading>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                        Tailor your CV for each job application. Our AI can help identify the right keywords and optimize your resume for Applicant Tracking Systems (ATS).
                    </p>
                    <flux:badge variant="outline" color="emerald">
                        <flux:icon name="sparkles" class="w-3 h-3 mr-1" />
                        ATS Optimized
                    </flux:badge>
                </flux:card>
            </div>
        </div>
    </div>
</x-layouts::app>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950" x-data>
    <div
        x-data="{ shown: false, timeout: null }"
        x-init="
            Livewire.on('cv-updated', () => {
                clearTimeout(timeout);
                shown = true;
                timeout = setTimeout(() => { shown = false }, 4000);
            });
        "
        x-show="shown"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-8"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-6 right-6 z-[60]"
        style="display: none;"
    >
        <div class="flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg shadow-emerald-600/20">
            <x-ui::icon name="check-circle" class="w-5 h-5 shrink-0" />
            <span class="text-sm font-medium">CV updated by AI assistant</span>
        </div>
    </div>

    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    <div class="max-w-[1800px] mx-auto p-4 md:p-6 lg:p-8">
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-white mb-2">
                        {{ $cv->exists ? 'Edit CV' : 'Create New CV' }}
                    </h1>
                    <p class="text-sm md:text-base text-zinc-600 dark:text-zinc-400">
                        Build your ATS-optimized CV with AI assistance
                    </p>
                </div>
                @if($cv->exists)
                    <x-ui::button variant="ghost" href="{{ route('cv.preview', $cv) }}" target="_blank" icon="external-link">
                        Open Preview
                    </x-ui::button>
                @endif
            </div>

            <div class="flex items-center gap-2 overflow-x-auto pb-2 -mx-2 px-2">
                @php
                    $sections = [
                        'personal' => ['name' => 'Personal', 'icon' => 'user'],
                        'experience' => ['name' => 'Experience', 'icon' => 'briefcase'],
                        'skills' => ['name' => 'Skills', 'icon' => 'zap'],
                        'certifications' => ['name' => 'Certifications', 'icon' => 'trophy'],
                        'education' => ['name' => 'Education', 'icon' => 'graduation-cap'],
                        'projects' => ['name' => 'Projects', 'icon' => 'folder'],
                    ];
                @endphp

                @foreach($sections as $key => $section)
                    <button
                        wire:click="setActiveSection('{{ $key }}')"
                        class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap {{ $activeSection === $key ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 border border-zinc-200 dark:border-zinc-700' }}"
                    >
                        <x-ui::icon name="{{ $section['icon'] }}" class="w-4 h-4" />
                        {{ $section['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @if($activeSection === 'personal')
                <x-ui::card class="card-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <x-ui::icon name="sparkles" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <x-ui::heading size="lg">Choose Template</x-ui::heading>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach($templates as $id => $template)
                            <button
                                wire:click="updateTemplate('{{ $id }}')"
                                class="relative p-4 rounded-xl border-2 text-left transition-all duration-200 card-hover {{ $selectedTemplate === $id ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-emerald-300' }}"
                            >
                                <div class="w-12 h-12 rounded-lg mb-3 flex items-center justify-center {{ $selectedTemplate === $id ? 'bg-emerald-500 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400' }}">
                                    <x-ui::icon name="{{ $template['icon'] }}" class="w-6 h-6" />
                                </div>
                                <div class="text-sm font-semibold {{ $selectedTemplate === $id ? 'text-emerald-900 dark:text-emerald-100' : 'text-zinc-900 dark:text-white' }}">
                                    {{ $template['name'] }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                    {{ $template['description'] }}
                                </div>
                                @if($selectedTemplate === $id)
                                    <div class="absolute top-2 right-2">
                                        <x-ui::icon name="check-circle" class="w-5 h-5 text-emerald-500" />
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </x-ui::card>

                <x-ui::card class="card-hover">
                    <div class="mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white mb-2">Personal Information</h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Start with your basic contact details</p>
                    </div>
                    <form wire:submit="savePersonalInfo" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui::input wire:model="personalInfo.first_name" label="First Name" placeholder="John" required />
                            <x-ui::input wire:model="personalInfo.last_name" label="Last Name" placeholder="Doe" required />
                        </div>

                        <x-ui::input wire:model="title" label="CV Title" placeholder="e.g., Senior Software Engineer" required />
                        <x-ui::description>How you want to be known professionally</x-ui::description>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui::input wire:model="personalInfo.email" type="email" label="Email" placeholder="john@example.com" required />
                            <x-ui::input wire:model="personalInfo.phone" type="tel" label="Phone" placeholder="+1 (555) 123-4567" />
                        </div>

                        <x-ui::input wire:model="personalInfo.location" label="Location" placeholder="City, Country" />
                        <x-ui::description>City and country where you're based</x-ui::description>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-ui::input wire:model="personalInfo.linkedin" label="LinkedIn" placeholder="linkedin.com/in/..." />
                            <x-ui::input wire:model="personalInfo.github" label="GitHub" placeholder="github.com/..." />
                            <x-ui::input wire:model="personalInfo.website" label="Website" placeholder="yoursite.com" />
                        </div>

                        <x-ui::textarea wire:model="summary" label="Professional Summary" placeholder="Write a brief summary of your professional background and career goals..." rows="5" />
                        <x-ui::description>2-4 sentences highlighting your key strengths and what you bring to the table</x-ui::description>

                        <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <x-ui::button type="submit" variant="primary" icon="check">
                                Save Personal Info
                            </x-ui::button>
                        </div>
                    </form>
                </x-ui::card>
            @endif

            @if($activeSection === 'experience')
                <livewire:cv-experience-manager :cv="$cv" />
            @endif

            @if($activeSection === 'skills')
                <livewire:cv-skills-manager :cv="$cv" />
            @endif

            @if($activeSection === 'certifications')
                <livewire:cv-certifications-manager :cv="$cv" />
            @endif

            @if($activeSection === 'education')
                <x-ui::card class="card-hover">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <x-ui::icon name="graduation-cap" class="w-8 h-8 text-zinc-400" />
                        </div>
                        <x-ui::heading size="lg" class="mb-2">Education</x-ui::heading>
                        <p class="text-zinc-600 dark:text-zinc-400">Education management coming soon...</p>
                    </div>
                </x-ui::card>
            @endif

            @if($activeSection === 'projects')
                <x-ui::card class="card-hover">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <x-ui::icon name="folder" class="w-8 h-8 text-zinc-400" />
                        </div>
                        <x-ui::heading size="lg" class="mb-2">Projects</x-ui::heading>
                        <p class="text-zinc-600 dark:text-zinc-400">Projects management coming soon...</p>
                    </div>
                </x-ui::card>
            @endif
        </div>
    </div>

    <div class="fixed bottom-6 right-6 z-50">
        <x-ui::button
            wire:click="toggleAiChat"
            variant="primary"
            icon="sparkles"
            class="rounded-full w-14 h-14 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 {{ $showAiChat ? 'bg-emerald-700' : 'bg-emerald-600' }}"
        />
    </div>

    @if($showAiChat)
        <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm" wire:click="toggleAiChat"></div>
        <div class="fixed bottom-0 right-0 w-full sm:w-[450px] h-[75vh] sm:h-[600px] z-50 bg-white dark:bg-zinc-900 rounded-t-2xl shadow-2xl flex flex-col">
            <div class="flex items-center justify-between p-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-ui::icon name="sparkles" class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <x-ui::heading size="md">AI Assistant</x-ui::heading>
                        <x-ui::text size="sm" class="text-zinc-500 dark:text-zinc-400">Always here to help</x-ui::text>
                    </div>
                </div>
                <x-ui::button variant="ghost" size="sm" wire:click="toggleAiChat" icon="x" class="text-zinc-500 hover:text-zinc-700" />
            </div>

            <div class="flex-1 overflow-hidden">
                <livewire:cv-ai-chat :cv="$cv" />
            </div>
        </div>
    @endif
</div>

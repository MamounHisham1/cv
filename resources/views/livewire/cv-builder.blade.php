<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950" x-data>
    <!-- Toast Notification -->
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
            <flux:icon name="check-circle" class="w-5 h-5 shrink-0" />
            <span class="text-sm font-medium">CV updated by AI assistant</span>
        </div>
    </div>

    <!-- Gradient Accent Header -->
    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    <div class="max-w-[1800px] mx-auto p-4 md:p-6 lg:p-8">
        <!-- Header -->
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
                    <flux:button variant="ghost" href="{{ route('cv.preview', $cv) }}" target="_blank" icon="arrow-top-right-on-square">
                        Open Preview
                    </flux:button>
                @endif
            </div>

            <!-- Section Navigation -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 -mx-2 px-2">
                @php
                    $sections = [
                        'personal' => ['name' => 'Personal', 'icon' => 'user'],
                        'experience' => ['name' => 'Experience', 'icon' => 'briefcase'],
                        'skills' => ['name' => 'Skills', 'icon' => 'bolt'],
                        'certifications' => ['name' => 'Certifications', 'icon' => 'trophy'],
                        'education' => ['name' => 'Education', 'icon' => 'academic-cap'],
                        'projects' => ['name' => 'Projects', 'icon' => 'folder'],
                    ];
                @endphp

                @foreach($sections as $key => $section)
                    <button
                        wire:click="setActiveSection('{{ $key }}')"
                        class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap {{ $activeSection === $key ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 border border-zinc-200 dark:border-zinc-700' }}"
                    >
                        <flux:icon name="{{ $section['icon'] }}" class="w-4 h-4" />
                        {{ $section['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Template Selector -->
            @if($activeSection === 'personal')
                <flux:card class="card-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <flux:icon name="sparkles" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <flux:heading size="lg">Choose Template</flux:heading>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach($templates as $id => $template)
                            <button
                                wire:click="updateTemplate('{{ $id }}')"
                                class="relative p-4 rounded-xl border-2 text-left transition-all duration-200 card-hover {{ $selectedTemplate === $id ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-emerald-300' }}"
                            >
                                <div class="w-12 h-12 rounded-lg mb-3 flex items-center justify-center {{ $selectedTemplate === $id ? 'bg-emerald-500 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400' }}">
                                    <flux:icon name="{{ $template['icon'] }}" class="w-6 h-6" />
                                </div>
                                <div class="text-sm font-semibold {{ $selectedTemplate === $id ? 'text-emerald-900 dark:text-emerald-100' : 'text-zinc-900 dark:text-white' }}">
                                    {{ $template['name'] }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                    {{ $template['description'] }}
                                </div>
                                @if($selectedTemplate === $id)
                                    <div class="absolute top-2 right-2">
                                        <flux:icon name="check-circle" class="w-5 h-5 text-emerald-500" />
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </flux:card>

                <!-- Personal Info Form -->
                <flux:card class="card-hover">
                    <div class="mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white mb-2">Personal Information</h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Start with your basic contact details</p>
                    </div>
                    <form wire:submit="savePersonalInfo" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>First Name</flux:label>
                                <flux:input wire:model="personalInfo.first_name" placeholder="John" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>Last Name</flux:label>
                                <flux:input wire:model="personalInfo.last_name" placeholder="Doe" required />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>CV Title</flux:label>
                            <flux:input wire:model="title" placeholder="e.g., Senior Software Engineer" required />
                            <flux:description>How you want to be known professionally</flux:description>
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Email</flux:label>
                                <flux:input wire:model="personalInfo.email" type="email" placeholder="john@example.com" required />
                            </flux:field>
                            <flux:field>
                                <flux:label>Phone</flux:label>
                                <flux:input wire:model="personalInfo.phone" type="tel" placeholder="+1 (555) 123-4567" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Location</flux:label>
                            <flux:input wire:model="personalInfo.location" placeholder="City, Country" />
                            <flux:description>City and country where you're based</flux:description>
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <flux:field>
                                <flux:label>LinkedIn</flux:label>
                                <flux:input wire:model="personalInfo.linkedin" placeholder="linkedin.com/in/..." />
                            </flux:field>
                            <flux:field>
                                <flux:label>GitHub</flux:label>
                                <flux:input wire:model="personalInfo.github" placeholder="github.com/..." />
                            </flux:field>
                            <flux:field>
                                <flux:label>Website</flux:label>
                                <flux:input wire:model="personalInfo.website" placeholder="yoursite.com" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Professional Summary</flux:label>
                            <flux:textarea wire:model="summary" placeholder="Write a brief summary of your professional background and career goals..." rows="5" />
                            <flux:description>2-4 sentences highlighting your key strengths and what you bring to the table</flux:description>
                        </flux:field>

                        <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:button type="submit" variant="primary" icon="check">
                                Save Personal Info
                            </flux:button>
                        </div>
                    </form>
                </flux:card>
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
                <flux:card class="card-hover">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <flux:icon name="academic-cap" class="w-8 h-8 text-zinc-400" />
                        </div>
                        <flux:heading size="lg" class="mb-2">Education</flux:heading>
                        <p class="text-zinc-600 dark:text-zinc-400">Education management coming soon...</p>
                    </div>
                </flux:card>
            @endif

            @if($activeSection === 'projects')
                <flux:card class="card-hover">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                            <flux:icon name="folder" class="w-8 h-8 text-zinc-400" />
                        </div>
                        <flux:heading size="lg" class="mb-2">Projects</flux:heading>
                        <p class="text-zinc-600 dark:text-zinc-400">Projects management coming soon...</p>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>

    <!-- Floating AI Chat Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <flux:button
            wire:click="toggleAiChat"
            variant="primary"
            icon="sparkles"
            class="rounded-full w-14 h-14 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 {{ $showAiChat ? 'bg-emerald-700' : 'bg-emerald-600' }}"
        />
    </div>

    <!-- AI Chat Drawer -->
    @if($showAiChat)
        <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm" wire:click="toggleAiChat"></div>
        <div class="fixed bottom-0 right-0 w-full sm:w-[450px] h-[75vh] sm:h-[600px] z-50 bg-white dark:bg-zinc-900 rounded-t-2xl shadow-2xl flex flex-col">
            <div class="flex items-center justify-between p-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <flux:icon name="sparkles" class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <flux:heading size="md">AI Assistant</flux:heading>
                        <flux:text size="xs" class="text-zinc-500 dark:text-zinc-400">Always here to help</flux:text>
                    </div>
                </div>
                <flux:button variant="ghost" size="sm" wire:click="toggleAiChat" icon="x-mark" class="text-zinc-500 hover:text-zinc-700" />
            </div>

            <div class="flex-1 overflow-hidden">
                <livewire:cv-ai-chat :cv="$cv" />
            </div>
        </div>
    @endif
</div>

@php
    $glassCardClasses = 'card-hover overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-5 md:p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
    $secondaryButtonClasses = 'border border-white/10 bg-white/5 text-zinc-100 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $emptyStateClasses = 'rounded-3xl border border-dashed border-white/10 bg-white/5 p-10 text-center shadow-xl shadow-black/10 backdrop-blur-xl';
@endphp

<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100" x-data>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.18),_transparent_45%)]"></div>
    <div class="pointer-events-none absolute right-0 top-24 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>

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
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-zinc-950/90 px-5 py-3 text-white shadow-xl shadow-emerald-500/20 backdrop-blur-xl">
            <x-ui::icon name="check-circle" class="w-5 h-5 shrink-0" />
            <span class="text-sm font-medium">CV updated by AI assistant</span>
        </div>
    </div>

    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    {{-- ===================== ONBOARDING STAGE ===================== --}}
    @if($stage === 'onboarding')
    <div class="relative mx-auto max-w-5xl px-4 py-16 md:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-12 text-center">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-300">
                Welcome — Let's get started
            </div>
            <h1 class="mb-4 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                Choose your <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">template</span>
            </h1>
            <p class="mx-auto max-w-xl text-base text-zinc-400">
                Pick the layout that fits your career goals. You can always switch later.
            </p>
        </div>

        {{-- Template gallery --}}
        <div class="mb-10 grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-5">
            @foreach($templates as $id => $template)
                <button
                    wire:click="onboardingSelectTemplate('{{ $id }}')"
                    class="group card-hover relative flex flex-col items-center gap-3 rounded-2xl border p-6 text-center transition-all duration-300
                        {{ $selectedTemplate === $id
                            ? 'border-emerald-400/50 bg-emerald-500/10 shadow-xl shadow-emerald-500/20'
                            : 'border-white/10 bg-white/5 hover:border-emerald-400/30 hover:bg-white/10' }}"
                >
                    <div class="h-32 w-32 shrink-0 rounded-xl bg-white shadow-lg ring-1 ring-black/5">
                        @switch($id)
                            @case('professional-classic')
                                <div class="flex h-full flex-col font-serif text-zinc-800">
                                    <div class="border-b border-zinc-700 pb-2 px-3">
                                        <div class="h-1.5 w-12 rounded-full bg-zinc-900"></div>
                                        <div class="mt-1 h-1 w-full rounded-full bg-zinc-300"></div>
                                    </div>
                                    <div class="mt-3 px-3 space-y-2">
                                        <div><div class="h-1 w-10 rounded-full bg-zinc-700"></div></div>
                                        <div><div class="h-1 w-8 rounded-full bg-zinc-200"></div></div>
                                    </div>
                                </div>
                            @break
                            @case('technical-ats')
                                <div class="flex h-full flex-col text-zinc-900">
                                    <div class="h-1.5 w-14 rounded-full bg-zinc-900"></div>
                                    <div class="mt-1 h-1 w-20 rounded-full bg-zinc-300"></div>
                                    <div class="mt-3 px-3 space-y-2">
                                        <div><div class="h-1 w-9 rounded-full bg-zinc-600"></div></div>
                                        <div><div class="h-1 w-7 rounded-full bg-zinc-300"></div></div>
                                    </div>
                                </div>
                            @break
                            @case('modern-minimal')
                                <div class="flex h-full flex-col text-zinc-800">
                                    <div class="h-1 w-full rounded-full bg-zinc-900"></div>
                                    <div class="mt-2 px-3 space-y-2">
                                        <div><div class="h-1 w-11 rounded-full bg-zinc-500"></div></div>
                                        <div><div class="h-1 w-9 rounded-full bg-zinc-400"></div></div>
                                    </div>
                                </div>
                            @break
                            @case('creative')
                                <div class="flex h-full flex-col bg-slate-100">
                                    <div class="bg-slate-600 p-2">
                                        <div class="h-3 w-12 rounded-full bg-white/80"></div>
                                    </div>
                                    <div class="mt-3 px-2 space-y-1">
                                        <div><div class="h-1 w-10 rounded-full bg-slate-400"></div></div>
                                        <div><div class="h-1 w-8 rounded-full bg-slate-300"></div></div>
                                        <div><div class="h-1 w-9 rounded-full bg-slate-300"></div></div>
                                    </div>
                                </div>
                            @break
                            @case('executive')
                                <div class="flex h-full flex-col items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-amber-500"></div>
                                    <div class="mt-3 space-y-2">
                                        <div><div class="h-1 w-10 rounded-full bg-amber-600"></div></div>
                                        <div><div class="h-1 w-7 rounded-full bg-amber-700"></div></div>
                                    </div>
                                </div>
                            @break
                        @endswitch
                    </div>
                    <div class="text-sm font-bold {{ $selectedTemplate === $id ? 'text-emerald-100' : 'text-white' }}">
                        {{ $template['name'] }}
                    </div>
                    @if($selectedTemplate === $id)
                        <div class="absolute right-3 top-3 z-10">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/30">
                                <x-ui::icon name="check" class="h-3 w-3 text-white" />
                            </div>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Action row --}}
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
            <button
                wire:click="onboardingSelectTemplate('{{ $selectedTemplate }}')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-3.5 text-sm font-semibold text-white shadow-xl shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-emerald-500/40 sm:w-auto"
            >
                <x-ui::icon name="pencil" class="h-4 w-4" />
                Create from Scratch
            </button>
            <a
                href="{{ route('cv.evaluator') }}"
                wire:navigate
                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-8 py-3.5 text-sm font-semibold text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white sm:w-auto"
            >
                <x-ui::icon name="upload" class="h-4 w-4" />
                Upload & Parse Existing CV
            </a>
        </div>

        <p class="mt-6 text-center text-xs text-zinc-500">
            You can skip template selection and
            <button wire:click="skipOnboarding" class="text-emerald-400 underline-offset-2 hover:underline">go straight to the builder</button>.
        </p>
    </div>
    @else
    {{-- ===================== BUILDER STAGE ===================== --}}
    <div class="relative mx-auto max-w-[1800px] p-4 md:p-6 lg:p-8">
        <div class="mb-6 rounded-3xl border border-white/10 bg-zinc-950/80 p-5 shadow-2xl shadow-black/20 backdrop-blur-xl md:p-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="mb-3 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
                        Design 4 Builder
                    </div>
                    <h1 class="mb-2 text-2xl font-bold text-white md:text-3xl lg:text-4xl">
                        {{ $cv->exists ? 'Edit CV' : 'Create New CV' }}
                    </h1>
                    <p class="text-sm text-zinc-400 md:text-base">
                        Build your ATS-optimized CV with AI assistance
                    </p>
                </div>
                @if($cv->exists)
                    <x-ui::button variant="ghost" href="{{ route('cv.preview', $cv) }}" target="_blank" icon="external-link" class="{{ $secondaryButtonClasses }} w-full sm:w-auto">
                        Open Preview
                    </x-ui::button>
                @endif
            </div>

            <div class="flex items-center gap-2 overflow-x-auto rounded-full border border-white/10 bg-white/5 p-2 backdrop-blur-xl">
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
                        class="shrink-0 whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition-all duration-300 {{ $activeSection === $key ? 'bg-white/10 text-white shadow-lg shadow-emerald-500/10' : 'text-zinc-400 hover:bg-white/10 hover:text-white' }}"
                    >
                        <span class="inline-flex items-center gap-2">
                            <x-ui::icon name="{{ $section['icon'] }}" class="w-4 h-4" />
                            {{ $section['name'] }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @if($activeSection === 'personal')
                <x-ui::card class="{{ $glassCardClasses }}">
                    <div class="mb-6">
                        <h2 class="mb-2 text-xl font-bold text-white md:text-2xl">Personal Information</h2>
                        <p class="text-sm text-zinc-400">Start with your basic contact details</p>
                    </div>
                    <form wire:submit="savePersonalInfo" class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.first_name" label="First Name" placeholder="John" required :error="$errors->first('personalInfo.first_name')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.first_name') ? $errorFieldClasses : '' }}" />
                            </div>
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.last_name" label="Last Name" placeholder="Doe" required :error="$errors->first('personalInfo.last_name')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.last_name') ? $errorFieldClasses : '' }}" />
                            </div>
                        </div>

                        <x-ui::input wire:model="title" label="CV Title" placeholder="e.g., Senior Software Engineer" required :error="$errors->first('title')" class="{{ $fieldClasses }} {{ $errors->has('title') ? $errorFieldClasses : '' }}" />
                        <x-ui::description class="text-zinc-400">How you want to be known professionally</x-ui::description>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.email" type="email" label="Email" placeholder="john@example.com" required :error="$errors->first('personalInfo.email')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.email') ? $errorFieldClasses : '' }}" />
                            </div>
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.phone" type="tel" label="Phone" placeholder="+1 (555) 123-4567" :error="$errors->first('personalInfo.phone')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.phone') ? $errorFieldClasses : '' }}" />
                            </div>
                        </div>

                        <x-ui::input wire:model="personalInfo.location" label="Location" placeholder="City, Country" :error="$errors->first('personalInfo.location')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.location') ? $errorFieldClasses : '' }}" />
                        <x-ui::description class="text-zinc-400">City and country where you're based</x-ui::description>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.linkedin" label="LinkedIn" placeholder="linkedin.com/in/..." :error="$errors->first('personalInfo.linkedin')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.linkedin') ? $errorFieldClasses : '' }}" />
                            </div>
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.github" label="GitHub" placeholder="github.com/..." :error="$errors->first('personalInfo.github')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.github') ? $errorFieldClasses : '' }}" />
                            </div>
                            <div class="form-field">
                                <x-ui::input wire:model="personalInfo.website" label="Website" placeholder="yoursite.com" :error="$errors->first('personalInfo.website')" class="{{ $fieldClasses }} {{ $errors->has('personalInfo.website') ? $errorFieldClasses : '' }}" />
                            </div>
                        </div>

                        <x-ui::textarea wire:model="summary" label="Professional Summary" placeholder="Write a brief summary of your professional background and career goals..." rows="5" :error="$errors->first('summary')" class="{{ $fieldClasses }} {{ $errors->has('summary') ? $errorFieldClasses : '' }}" />
                        <x-ui::description class="text-zinc-400">2-4 sentences highlighting your key strengths and what you bring to the table</x-ui::description>

                        <div class="flex justify-end border-t border-white/10 pt-4">
                            <x-ui::button type="submit" variant="primary" icon="check" class="{{ $primaryButtonClasses }}">
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
                <x-ui::card class="{{ $glassCardClasses }}">
                    <div class="text-center py-12">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                            <x-ui::icon name="graduation-cap" class="w-8 h-8 text-emerald-300" />
                        </div>
                        <x-ui::heading size="lg" class="mb-2 text-white">Education</x-ui::heading>
                        <p class="text-zinc-400">Education management coming soon...</p>
                    </div>
                </x-ui::card>
            @endif

            @if($activeSection === 'projects')
                <x-ui::card class="{{ $glassCardClasses }}">
                    <div class="text-center py-12">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                            <x-ui::icon name="folder" class="w-8 h-8 text-emerald-300" />
                        </div>
                        <x-ui::heading size="lg" class="mb-2 text-white">Projects</x-ui::heading>
                        <p class="text-zinc-400">Projects management coming soon...</p>
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
            class="h-14 w-14 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-xl shadow-emerald-500/30 transition-all duration-300 hover:scale-105 hover:from-emerald-400 hover:to-emerald-500"
        />
    </div>

    @if($showAiChat)
        {{-- Scrim — full viewport, z-[55] overlays the z-50 nav --}}
        <div class="fixed inset-0 z-[55] bg-black/50 backdrop-blur-sm" wire:click="toggleAiChat"></div>

        {{-- Chat panel — full-height right sidebar
             z-[60] sits above scrim (z-[55]) and nav (z-50).
             Mobile  → full-screen overlay
             Desktop → 420px sidebar pinned to the right edge
        --}}
        <div class="fixed inset-0 z-[60] flex flex-col border-l border-white/10 bg-zinc-950/90 shadow-2xl shadow-black/40 backdrop-blur-xl
                    sm:inset-y-0 sm:left-auto sm:right-0 sm:w-[420px]">
            {{-- Panel header --}}
            <div class="flex shrink-0 items-center justify-between border-b border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                        <x-ui::icon name="sparkles" class="w-5 h-5 text-emerald-300" />
                    </div>
                    <div>
                        <x-ui::heading size="md" class="text-white">AI Assistant</x-ui::heading>
                        <x-ui::text size="sm" class="text-zinc-400">Always here to help</x-ui::text>
                    </div>
                </div>
                <x-ui::button variant="ghost" size="sm" wire:click="toggleAiChat" icon="x" class="{{ $secondaryButtonClasses }} h-9 w-9 px-0 text-zinc-400 hover:text-white" />
            </div>

            {{-- Scrollable chat body + input — min-h-0 enables flex shrink for overflow --}}
            <div class="min-h-0 flex-1 overflow-hidden">
                <livewire:cv-ai-chat :cv="$cv" />
            </div>
        </div>
    @endif
    @endif {{-- end @else (builder stage) --}}
</div>

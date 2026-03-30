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
        <div class="mb-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($templates as $id => $template)
                <button
                    wire:click="onboardingSelectTemplate('{{ $id }}')"
                    class="group card-hover relative flex flex-col items-center gap-4 rounded-2xl border p-5 text-center transition-all duration-300
                        {{ $selectedTemplate === $id
                            ? 'border-emerald-400/50 bg-emerald-500/10 shadow-xl shadow-emerald-500/20'
                            : 'border-white/10 bg-white/5 hover:border-emerald-400/30 hover:bg-white/10' }}"
                >
                    <div class="w-full shrink-0 rounded-lg bg-white shadow-lg ring-1 ring-black/5 overflow-hidden" style="aspect-ratio: 210/297;">
                        @switch($id)
                            @case('professional-classic')
                                {{-- Single column, serif, header with bottom border, underlined section headings --}}
                                <div class="flex h-full flex-col p-4 font-serif text-gray-900">
                                    <div class="border-b-2 border-gray-800 pb-2 mb-3">
                                        <div class="h-2 w-16 rounded-full bg-gray-900"></div>
                                        <div class="mt-1.5 h-1 w-full rounded bg-gray-200"></div>
                                        <div class="mt-1 h-0.5 w-24 rounded bg-gray-300"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex items-center gap-1.5 mb-1"><div class="h-1 w-12 rounded bg-gray-800"></div><div class="h-px flex-1 bg-gray-300"></div></div>
                                        <div class="ml-1 space-y-1">
                                            <div class="h-1.5 w-full rounded bg-gray-200"></div>
                                            <div class="h-1 w-3/4 rounded bg-gray-100"></div>
                                            <div class="h-1 w-5/6 rounded bg-gray-200"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex items-center gap-1.5 mb-1"><div class="h-1 w-14 rounded bg-gray-800"></div><div class="h-px flex-1 bg-gray-300"></div></div>
                                        <div class="ml-1 space-y-1">
                                            <div class="h-1 w-full rounded bg-gray-200"></div>
                                            <div class="h-1 w-2/3 rounded bg-gray-100"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5 mb-1"><div class="h-1 w-8 rounded bg-gray-800"></div><div class="h-px flex-1 bg-gray-300"></div></div>
                                        <div class="ml-1 flex flex-wrap gap-1">
                                            <div class="h-1.5 w-8 rounded bg-gray-300"></div>
                                            <div class="h-1.5 w-10 rounded bg-gray-200"></div>
                                            <div class="h-1.5 w-6 rounded bg-gray-300"></div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('technical-ats')
                                {{-- 1/3 dark sidebar + 2/3 main content, teal accents, skill bars --}}
                                <div class="flex h-full text-gray-900">
                                    <div class="w-[38%] bg-slate-800 p-3 flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-slate-600 mb-2"></div>
                                        <div class="w-full space-y-1 mb-3">
                                            <div class="h-0.5 w-full rounded bg-slate-500"></div>
                                            <div class="h-0.5 w-4/5 rounded bg-slate-500"></div>
                                            <div class="h-0.5 w-3/5 rounded bg-slate-500"></div>
                                        </div>
                                        <div class="w-full space-y-2">
                                            <div class="h-1 bg-slate-600 rounded-full"><div class="h-full w-4/5 bg-teal-400 rounded-full"></div></div>
                                            <div class="h-1 bg-slate-600 rounded-full"><div class="h-full w-3/5 bg-teal-400 rounded-full"></div></div>
                                            <div class="h-1 bg-slate-600 rounded-full"><div class="h-full w-full bg-teal-400 rounded-full"></div></div>
                                            <div class="h-1 bg-slate-600 rounded-full"><div class="h-full w-2/5 bg-teal-400 rounded-full"></div></div>
                                        </div>
                                    </div>
                                    <div class="w-[62%] bg-white p-3">
                                        <div class="mb-2">
                                            <div class="h-2 w-14 rounded bg-slate-800"></div>
                                            <div class="mt-0.5 h-1 w-10 rounded bg-teal-500"></div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="flex items-center gap-1 mb-1"><div class="h-0.5 w-4 bg-teal-500 rounded"></div><div class="h-1 w-8 rounded bg-slate-700"></div></div>
                                            <div class="ml-2 space-y-1">
                                                <div class="h-1 w-full rounded bg-gray-200"></div>
                                                <div class="h-1 w-3/4 rounded bg-gray-100"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1 mb-1"><div class="h-0.5 w-4 bg-teal-500 rounded"></div><div class="h-1 w-10 rounded bg-slate-700"></div></div>
                                            <div class="ml-2 border-l-2 border-teal-200 pl-2 space-y-1">
                                                <div class="h-1 w-full rounded bg-gray-200"></div>
                                                <div class="h-1 w-2/3 rounded bg-gray-100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('modern-minimal')
                                {{-- Single column, light weights, contact with dots, skill pills, clean --}}
                                <div class="flex h-full flex-col p-4 font-sans text-gray-900">
                                    <div class="mb-3">
                                        <div class="h-2 w-14 rounded bg-gray-900"></div>
                                        <div class="mt-1.5 flex gap-2 items-center">
                                            <div class="h-0.5 w-8 rounded bg-gray-400"></div>
                                            <div class="w-0.5 h-0.5 rounded-full bg-gray-400"></div>
                                            <div class="h-0.5 w-10 rounded bg-gray-400"></div>
                                            <div class="w-0.5 h-0.5 rounded-full bg-gray-400"></div>
                                            <div class="h-0.5 w-6 rounded bg-gray-400"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="h-1 w-full rounded bg-gray-200"></div>
                                        <div class="h-1 w-5/6 rounded bg-gray-100 mt-0.5"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="h-0.5 w-10 rounded bg-gray-400 mb-1.5"></div>
                                        <div class="space-y-1">
                                            <div class="h-1 w-full rounded bg-gray-200"></div>
                                            <div class="h-1 w-3/4 rounded bg-gray-100"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h-0.5 w-6 rounded bg-gray-400 mb-1.5"></div>
                                        <div class="flex flex-wrap gap-1">
                                            <div class="h-2.5 w-7 rounded-full bg-gray-100"></div>
                                            <div class="h-2.5 w-9 rounded-full bg-gray-200"></div>
                                            <div class="h-2.5 w-6 rounded-full bg-gray-100"></div>
                                            <div class="h-2.5 w-8 rounded-full bg-gray-200"></div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('creative')
                                {{-- Single column serif, traditional with sidebar-like header accent --}}
                                <div class="flex h-full flex-col bg-white">
                                    <div class="bg-emerald-600 px-4 py-2">
                                        <div class="h-1.5 w-12 rounded-full bg-white/90"></div>
                                        <div class="mt-1 h-0.5 w-full rounded-full bg-white/40"></div>
                                    </div>
                                    <div class="flex-1 p-4 font-serif text-gray-900">
                                        <div class="mb-2">
                                            <div class="h-1 w-16 rounded bg-gray-800 mb-1"></div>
                                            <div class="h-1 w-full rounded bg-gray-200"></div>
                                            <div class="h-1 w-5/6 rounded bg-gray-100 mt-0.5"></div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="h-1 w-20 rounded bg-gray-800 mb-1"></div>
                                            <div class="space-y-1">
                                                <div class="h-1 w-full rounded bg-gray-200"></div>
                                                <div class="h-1 w-3/4 rounded bg-gray-100"></div>
                                                <div class="h-1 w-4/5 rounded bg-gray-200"></div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 flex-wrap">
                                            <div class="h-1 w-8 rounded bg-emerald-300"></div>
                                            <div class="h-1 w-10 rounded bg-emerald-200"></div>
                                            <div class="h-1 w-6 rounded bg-emerald-300"></div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('executive')
                                {{-- Centered header, centered sections, serif, dot bullets --}}
                                <div class="flex h-full flex-col p-4 font-serif text-gray-900">
                                    <div class="text-center border-b-2 border-gray-800 pb-2 mb-3">
                                        <div class="mx-auto h-2 w-16 rounded bg-gray-900"></div>
                                        <div class="mx-auto mt-1 h-1 w-10 rounded bg-gray-400"></div>
                                        <div class="mt-1 flex justify-center gap-2">
                                            <div class="h-0.5 w-8 rounded bg-gray-300"></div>
                                            <div class="h-0.5 w-10 rounded bg-gray-300"></div>
                                            <div class="h-0.5 w-6 rounded bg-gray-300"></div>
                                        </div>
                                    </div>
                                    <div class="text-center mb-2">
                                        <div class="mx-auto h-1 w-14 rounded bg-gray-700 mb-1"></div>
                                        <div class="mx-auto h-0.5 w-full rounded bg-gray-200"></div>
                                        <div class="mx-auto h-0.5 w-4/5 rounded bg-gray-100 mt-0.5"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="mx-auto h-1 w-16 rounded bg-gray-700 mb-1.5"></div>
                                        <div class="flex flex-wrap justify-center gap-x-2 gap-y-0.5">
                                            <div class="flex items-center gap-0.5"><div class="w-0.5 h-0.5 rounded-full bg-gray-800"></div><div class="h-0.5 w-6 rounded bg-gray-300"></div></div>
                                            <div class="flex items-center gap-0.5"><div class="w-0.5 h-0.5 rounded-full bg-gray-800"></div><div class="h-0.5 w-8 rounded bg-gray-300"></div></div>
                                            <div class="flex items-center gap-0.5"><div class="w-0.5 h-0.5 rounded-full bg-gray-800"></div><div class="h-0.5 w-5 rounded bg-gray-300"></div></div>
                                        </div>
                                    </div>
                                    <div class="border-b border-gray-300 pb-1">
                                        <div class="mx-auto h-1 w-20 rounded bg-gray-700 mb-1"></div>
                                        <div class="space-y-0.5">
                                            <div class="h-1 w-full rounded bg-gray-200"></div>
                                            <div class="h-1 w-2/3 rounded bg-gray-100"></div>
                                        </div>
                                    </div>
                                </div>
                            @break
                        @endswitch
                    </div>
                    <div class="text-sm font-bold {{ $selectedTemplate === $id ? 'text-emerald-100' : 'text-white' }}">
                        {{ $template['name'] }}
                    </div>
                    <div class="text-xs {{ $selectedTemplate === $id ? 'text-emerald-200/70' : 'text-zinc-500' }}">
                        {{ $template['description'] }}
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

    </div>

    {{-- ===================== ONBOARDING MODAL ===================== --}}
    @if($showOnboardingModal)
        {{-- Scrim --}}
        <div class="fixed inset-0 z-[55] bg-black/60 backdrop-blur-sm" wire:click="closeOnboardingModal"></div>

        {{-- Modal --}}
        <div
            x-data="{ step: 'choose' }"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        >
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-zinc-950/95 p-6 shadow-2xl shadow-black/40 backdrop-blur-xl md:p-8"
                 @click.stop>
                {{-- Choose step --}}
                <div x-show="step === 'choose'" x-transition>
                    <div class="mb-6 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::icon name="document-text" class="w-7 h-7 text-emerald-300" />
                        </div>
                        <h2 class="text-xl font-bold text-white">How would you like to start?</h2>
                        <p class="mt-1 text-sm text-zinc-400">Using the {{ $templates[$selectedTemplate]['name'] ?? '' }} template</p>
                    </div>

                    <div class="space-y-3">
                        <button
                            @click="$wire.createFromScratch()"
                            class="flex w-full items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-left transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10"
                        >
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10">
                                <x-ui::icon name="pencil" class="w-5 h-5 text-emerald-300" />
                            </div>
                            <div>
                                <div class="font-semibold text-white">Create from Scratch</div>
                                <div class="text-xs text-zinc-400">Start with a blank CV and fill in your details</div>
                            </div>
                            <x-ui::icon name="chevron-right" class="ml-auto w-4 h-4 shrink-0 text-zinc-500" />
                        </button>

                        <button
                            @click="step = 'upload'"
                            class="flex w-full items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-left transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10"
                        >
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-500/10">
                                <x-ui::icon name="upload" class="w-5 h-5 text-blue-300" />
                            </div>
                            <div>
                                <div class="font-semibold text-white">Import Existing CV</div>
                                <div class="text-xs text-zinc-400">Upload a file and we'll fill in the forms for you</div>
                            </div>
                            <x-ui::icon name="chevron-right" class="ml-auto w-4 h-4 shrink-0 text-zinc-500" />
                        </button>
                    </div>

                    <button
                        wire:click="closeOnboardingModal"
                        class="mt-4 w-full rounded-xl py-2.5 text-sm text-zinc-400 transition-colors hover:text-white"
                    >
                        Cancel
                    </button>
                </div>

                {{-- Upload step --}}
                <div x-show="step === 'upload'" x-cloak x-transition>
                    <div class="mb-6 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-500/10">
                            <x-ui::icon name="upload" class="w-7 h-7 text-blue-300" />
                        </div>
                        <h2 class="text-xl font-bold text-white">Import Your CV</h2>
                        <p class="mt-1 text-sm text-zinc-400">Upload a PDF, DOCX, DOC, or TXT file</p>
                    </div>

                    @if($importError)
                        <div class="mb-4 rounded-xl border border-red-400/20 bg-red-500/10 p-3 text-sm text-red-300">
                            {{ $importError }}
                        </div>
                    @endif

                    <div
                        x-data="{ dragging: false }"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="
                            dragging = false;
                            $refs.fileInput.files = event.dataTransfer.files;
                            $refs.fileInput.dispatchEvent(new Event('change'));
                        "
                        class="mb-4 cursor-pointer rounded-2xl border-2 border-dashed p-8 text-center transition-colors duration-200
                            {{ $errors->has('uploadedFile') ? 'border-red-400/50 bg-red-500/5' : 'border-white/10 bg-white/5 hover:border-emerald-400/30 hover:bg-white/10' }}"
                        @click="$refs.fileInput.click()"
                    >
                        <input
                            type="file"
                            wire:model="uploadedFile"
                            accept=".pdf,.doc,.docx,.txt"
                            class="hidden"
                            x-ref="fileInput"
                        >
                        @if($uploadedFile)
                            <div class="flex items-center justify-center gap-3">
                                <x-ui::icon name="document-text" class="w-8 h-8 text-emerald-300" />
                                <div class="text-left">
                                    <div class="text-sm font-medium text-white">{{ $uploadedFile->getClientOriginalName() }}</div>
                                    <div class="text-xs text-zinc-400">{{ number_format($uploadedFile->getSize() / 1024, 1) }} KB</div>
                                </div>
                            </div>
                        @else
                            <x-ui::icon name="cloud-arrow-up" class="mx-auto mb-3 w-8 h-8 text-zinc-500" />
                            <div class="text-sm text-zinc-300">Click to upload or drag and drop</div>
                            <div class="mt-1 text-xs text-zinc-500">PDF, DOCX, DOC, TXT — Max 5 MB</div>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="step = 'choose'"
                            class="flex-1 rounded-xl border border-white/10 bg-white/5 py-3 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white"
                        >
                            Back
                        </button>
                        <button
                            wire:click="importCv"
                            wire:loading.attr="disabled"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:from-emerald-400 hover:to-emerald-500 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="importCv">
                                <x-ui::icon name="sparkles" class="w-4 h-4" />
                                Import & Parse
                            </span>
                            <span wire:loading wire:target="importCv" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
                <div class="flex gap-2">
                    <button
                        wire:click="goToOnboarding"
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white"
                    >
                        <x-ui::icon name="plus" class="h-4 w-4" />
                        New CV
                    </button>
                    @if($cv->exists)
                        <x-ui::button variant="ghost" href="{{ route('cv.preview', $cv) }}" target="_blank" icon="external-link" class="{{ $secondaryButtonClasses }}">
                            Open Preview
                        </x-ui::button>
                    @endif
                </div>
            </div>

            @if($cv->exists)
            <div class="mb-4">
                <div class="mb-2 text-xs font-semibold uppercase tracking-widest text-zinc-500">Template</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($templates as $id => $template)
                        <button
                            wire:click="updateTemplate('{{ $id }}')"
                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-all duration-200
                                {{ $selectedTemplate === $id
                                    ? 'border border-emerald-400/30 bg-emerald-500/15 text-emerald-300 shadow-sm shadow-emerald-500/10'
                                    : 'border border-white/10 bg-white/5 text-zinc-400 hover:border-white/20 hover:bg-white/10 hover:text-zinc-200' }}"
                        >
                            <x-ui::icon name="{{ $template['icon'] }}" class="w-3.5 h-3.5" />
                            {{ $template['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-2 overflow-x-auto rounded-full border border-white/10 bg-white/5 p-2 backdrop-blur-xl">
                @php
                    $sections = [
                        'personal' => ['name' => 'Personal', 'icon' => 'user'],
                        'experience' => ['name' => 'Experience', 'icon' => 'briefcase'],
                        'skills' => ['name' => 'Skills', 'icon' => 'zap'],
                        'certifications' => ['name' => 'Certifications', 'icon' => 'trophy'],
                        'education' => ['name' => 'Education', 'icon' => 'graduation-cap'],
                        'projects' => ['name' => 'Projects', 'icon' => 'folder'],
                        'languages' => ['name' => 'Languages', 'icon' => 'globe'],
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
                <livewire:cv-education-manager :cv="$cv" />
            @endif

            @if($activeSection === 'projects')
                <livewire:cv-project-manager :cv="$cv" />
            @endif

            @if($activeSection === 'languages')
                <livewire:cv-language-manager :cv="$cv" />
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

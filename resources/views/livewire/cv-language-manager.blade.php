@php
    $glassCardClasses = 'card-hover overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-5 md:p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-200 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $levelWidths = [
        'beginner' => 15,
        'elementary' => 25,
        'intermediate' => 50,
        'upper_intermediate' => 65,
        'advanced' => 80,
        'fluent' => 90,
        'native' => 100,
    ];
@endphp

<div>
    <x-ui::card class="{{ $glassCardClasses }}">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <x-ui::heading size="lg" class="text-white">Languages</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Showcase your language skills</p>
            </div>
            <x-ui::button
                wire:click="addLanguage"
                variant="primary"
                size="sm"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Language
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveLanguage" class="mb-8 space-y-4 form-section">
                <x-ui::heading size="md" class="text-emerald-300">
                    {{ $editingId ? 'Edit Language' : 'Add New Language' }}
                </x-ui::heading>

                <x-ui::input
                    wire:model="form.language"
                    label="Language"
                    placeholder="e.g., English"
                    required
                    :error="$errors->first('form.language')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.language') ? $errorFieldClasses : '' }}"
                />

                <x-ui::select
                    wire:model="form.proficiency"
                    label="Proficiency"
                    :options="$proficiencies"
                    required
                    :error="$errors->first('form.proficiency')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.proficiency') ? $errorFieldClasses : '' }}"
                />

                <div class="flex flex-col justify-end gap-2 border-t border-white/10 pt-4 sm:flex-row">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit" class="{{ $ghostButtonClasses }}">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Language
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-0">
            @forelse($languages as $language)
                <div class="timeline-item group">
                    <div class="timeline-dot">
                        <x-ui::icon name="globe" class="w-3 h-3 text-white" />
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10 md:p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                                        <x-ui::icon name="globe" class="w-5 h-5 text-emerald-300" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white">{{ $language['language'] }}</h4>
                                        <p class="text-sm text-zinc-400 capitalize">{{ $language['proficiency'] }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 ml-[52px]">
                                    <div class="h-1.5 w-48 overflow-hidden rounded-full bg-zinc-900/80">
                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all duration-500" style="width: {{ $levelWidths[$language['proficiency']] ?? 50 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex lg:flex-col gap-2 lg:shrink-0">
                                <x-ui::button variant="ghost" size="sm" wire:click="editLanguage({{ $language['id'] }})" icon="pencil" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Edit</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="deleteLanguage({{ $language['id'] }})" wire:confirm="Are you sure you want to delete this language?" icon="trash-2" class="flex-1 border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Delete</span>
                                </x-ui::button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-12 text-center backdrop-blur-xl">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/60">
                        <x-ui::icon name="globe" class="w-8 h-8 text-emerald-300" />
                    </div>
                    <x-ui::heading size="md" class="mb-2 text-white">No Languages Added Yet</x-ui::heading>
                    <p class="mb-4 text-sm text-zinc-400">Add the languages you speak</p>
                    <x-ui::button wire:click="addLanguage" variant="primary" icon="plus" class="{{ $primaryButtonClasses }}">
                        Add Your First Language
                    </x-ui::button>
                </div>
            @endforelse
        </div>

        @if(!$showForm && count($languages) < 5)
            <div class="mt-4">
                <p class="mb-3 text-xs font-medium uppercase tracking-wide text-zinc-400">Quick Add</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($commonLanguages as $lang)
                        <button
                            wire:click="quickAddLanguage('{{ $lang }}')"
                            class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-400 transition-all duration-200 hover:bg-emerald-500/15 hover:border-emerald-400/30 hover:text-emerald-200"
                        >
                            {{ $lang }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </x-ui::card>
</div>

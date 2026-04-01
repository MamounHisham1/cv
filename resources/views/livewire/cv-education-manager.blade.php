@php
    $glassCardClasses = 'card-hover overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-5 md:p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-200 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
@endphp

<div>
    @if($isLazy ?? false)
        @include('livewire.partials.section-skeleton')
    @else
    <x-ui::card class="{{ $glassCardClasses }}">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <x-ui::heading size="lg" class="text-white">Education</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Showcase your academic background</p>
            </div>
            <x-ui::button
                wire:click="addEducation"
                variant="primary"
                size="sm"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Education
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveEducation" class="mb-8 space-y-4 form-section">
                <x-ui::heading size="md" class="text-emerald-300">
                    {{ $editingId ? 'Edit Education' : 'Add New Education' }}
                    {{ $editingId ? '<span class="text-xs text-zinc-500">Auto-saves as you type</span>' : '' }}
                </x-ui::heading>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.institution"
                            label="Institution"
                            placeholder="e.g., Cairo University"
                            required
                            :error="$errors->first('form.institution')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.institution') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.degree"
                            label="Degree"
                            placeholder="e.g., Bachelor of Computer Science"
                            required
                            :error="$errors->first('form.degree')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.degree') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.field_of_study"
                            label="Field of Study"
                            placeholder="e.g., Software Engineering"
                            :error="$errors->first('form.field_of_study')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.field_of_study') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.location"
                            label="Location"
                            placeholder="City, Country (optional)"
                            :error="$errors->first('form.location')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.location') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.start_date"
                            type="date"
                            label="Start Date"
                            required
                            :error="$errors->first('form.start_date')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.start_date') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field space-y-3">
                        @if(!$form['is_current'])
                            <x-ui::input
                                wire:model.live.debounce.1000ms="form.end_date"
                                type="date"
                                label="End Date"
                                :error="$errors->first('form.end_date')"
                                class="{{ $fieldClasses }} {{ $errors->has('form.end_date') ? $errorFieldClasses : '' }}"
                            />
                        @else
                            <x-ui::input value="Present" label="End Date" disabled class="{{ $fieldClasses }}" />
                        @endif
                        <div>
                            <x-ui::checkbox wire:model="form.is_current" label="I currently study here" class="border-white/15 bg-white/5 text-emerald-500 accent-emerald-500 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0" />
                        </div>
                    </div>
                </div>

                <x-ui::textarea
                    wire:model.live.debounce.1000ms="form.description"
                    label="Description (optional)"
                    placeholder="Relevant coursework, honors, activities..."
                    rows="3"
                    :error="$errors->first('form.description')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.description') ? $errorFieldClasses : '' }}"
                />

                <div class="flex flex-col justify-end gap-2 border-t border-white/10 pt-4 sm:flex-row">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit" class="{{ $ghostButtonClasses }}">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Education
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-0" wire:sort="handleSort">
            @forelse($educations as $education)
                <div class="timeline-item group" wire:sort:item="{{ $education['id'] }}">
                    <div class="timeline-dot">
                        <x-ui::icon name="graduation-cap" class="w-3 h-3 text-white" />
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10 md:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                                        <x-ui::icon name="graduation-cap" class="w-6 h-6 text-emerald-300" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">
                                            {{ $education['degree'] }}
                                        </h3>
                                        <p class="text-emphasis text-zinc-300">
                                            {{ $education['institution'] }}
                                        </p>
                                        @if($education['field_of_study'])
                                            <p class="text-sm text-zinc-400">{{ $education['field_of_study'] }}</p>
                                        @endif
                                        <p class="mt-1 text-sm text-zinc-400">
                                            {{ \Carbon\Carbon::parse($education['start_date'])->format('M Y') }} -
                                            {{ $education['is_current'] ? 'Present' : \Carbon\Carbon::parse($education['end_date'])->format('M Y') }}
                                            @if($education['location'])
                                                <span class="mx-2">&bull;</span>
                                                {{ $education['location'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if(!empty($education['description']))
                                    <p class="text-sm leading-relaxed text-zinc-300">
                                        {{ Str::limit($education['description'], 300) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex lg:flex-col gap-2 lg:shrink-0">
                                <x-ui::button variant="ghost" size="sm" wire:click="editEducation({{ $education['id'] }})" icon="pencil" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Edit</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="deleteEducation({{ $education['id'] }})" wire:confirm="Are you sure you want to delete this education?" icon="trash-2" class="flex-1 border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Delete</span>
                                </x-ui::button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-12 text-center backdrop-blur-xl">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/60">
                        <x-ui::icon name="graduation-cap" class="w-8 h-8 text-emerald-300" />
                    </div>
                    <x-ui::heading size="md" class="mb-2 text-white">No Education Added Yet</x-ui::heading>
                    <p class="mb-4 text-sm text-zinc-400">Add your academic qualifications</p>
                    <x-ui::button wire:click="addEducation" variant="primary" icon="plus" class="{{ $primaryButtonClasses }}">
                        Add Your First Education
                    </x-ui::button>
                </div>
            @endforelse
        </div>
    </x-ui::card>
    @endif
</div>

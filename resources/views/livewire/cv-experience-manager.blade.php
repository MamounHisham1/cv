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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <x-ui::heading size="lg" class="text-white">Work Experience</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Add your professional journey</p>
            </div>
            <x-ui::button
                wire:click="addExperience"
                variant="primary"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Experience
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveExperience" class="space-y-6 mb-8 form-section">
                <div class="flex items-center justify-between mb-2">
                    <x-ui::heading size="md" class="text-emerald-300">
                        {{ $editingId ? 'Edit Experience' : 'Add New Experience' }}
                    </x-ui::heading>
                    @if($editingId)
                        <span class="text-xs text-zinc-500">Auto-saves as you type</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input wire:model.live.debounce.1000ms="form.company" label="Company" placeholder="Company Name" required :error="$errors->first('form.company')" class="{{ $fieldClasses }} {{ $errors->has('form.company') ? $errorFieldClasses : '' }}" />
                    </div>
                    <div class="form-field">
                        <x-ui::input wire:model.live.debounce.1000ms="form.title" label="Job Title" placeholder="e.g., Senior Project Manager" required :error="$errors->first('form.title')" class="{{ $fieldClasses }} {{ $errors->has('form.title') ? $errorFieldClasses : '' }}" />
                    </div>
                </div>

                <x-ui::input wire:model.live.debounce.1000ms="form.location" label="Location" placeholder="City, Country (optional)" :error="$errors->first('form.location')" class="{{ $fieldClasses }} {{ $errors->has('form.location') ? $errorFieldClasses : '' }}" />

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input wire:model.live.debounce.1000ms="form.start_date" type="date" label="Start Date" required :error="$errors->first('form.start_date')" class="{{ $fieldClasses }} {{ $errors->has('form.start_date') ? $errorFieldClasses : '' }}" />
                    </div>
                    <div class="form-field space-y-3">
                        @if(!$form['is_current'])
                            <x-ui::input wire:model.live.debounce.1000ms="form.end_date" type="date" label="End Date" :error="$errors->first('form.end_date')" class="{{ $fieldClasses }} {{ $errors->has('form.end_date') ? $errorFieldClasses : '' }}" />
                        @else
                            <x-ui::input value="Present" label="End Date" disabled class="{{ $fieldClasses }}" />
                        @endif
                        <div>
                            <x-ui::checkbox wire:model.live="form.is_current" label="I currently work here" class="border-white/15 bg-white/5 text-emerald-500 accent-emerald-500 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0" />
                        </div>
                    </div>
                </div>

                <x-ui::textarea wire:model.live.debounce.1000ms="form.description" label="Description" placeholder="Describe your role, responsibilities, and achievements..." rows="4" required :error="$errors->first('form.description')" class="{{ $fieldClasses }} {{ $errors->has('form.description') ? $errorFieldClasses : '' }}" />
                <x-ui::description class="text-zinc-400">Use action verbs and include quantifiable achievements when possible</x-ui::description>

                <div>
                    <x-ui::label class="mb-3 block text-zinc-200">Key Skills & Technologies Used</x-ui::label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($commonSkills as $skill)
                            <label class="cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="form.technologies"
                                    value="{{ $skill }}"
                                    class="sr-only peer"
                                />
                                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 peer-checked:border-emerald-400/30 peer-checked:bg-emerald-500/15 peer-checked:text-emerald-200">
                                    {{ $skill }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <x-ui::label class="mb-3 block text-zinc-200">Key Achievements</x-ui::label>
                    @foreach($form['achievements'] as $index => $achievement)
                        <div class="flex gap-2 mb-2">
                            <x-ui::input
                                wire:model="form.achievements.{{ $index }}"
                                placeholder="e.g., Reduced costs by 30% through optimization"
                                class="{{ $fieldClasses }} flex-1"
                            />
                            <x-ui::button type="button" variant="ghost" size="sm" wire:click="removeAchievement({{ $index }})" icon="x" class="{{ $ghostButtonClasses }} shrink-0" />
                        </div>
                    @endforeach
                    <x-ui::button type="button" variant="ghost" size="sm" wire:click="addAchievement" icon="plus" class="{{ $ghostButtonClasses }} mt-2">
                        Add Achievement
                    </x-ui::button>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 border-t border-white/10 pt-4">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit" class="{{ $ghostButtonClasses }}">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" icon="check" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Experience
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-0">
            @forelse($experiences as $experience)
                <div class="timeline-item group">
                    <div class="timeline-dot">
                        <x-ui::icon name="briefcase" class="w-3 h-3 text-white" />
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10 md:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                                        <x-ui::icon name="building-2" class="w-6 h-6 text-emerald-300" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">
                                            {{ $experience['title'] }}
                                        </h3>
                                        <p class="text-emphasis text-zinc-300">
                                            {{ $experience['company'] }}
                                        </p>
                                        <p class="mt-1 text-sm text-zinc-400">
                                            {{ \Carbon\Carbon::parse($experience['start_date'])->format('M Y') }} -
                                            {{ $experience['is_current'] ? 'Present' : \Carbon\Carbon::parse($experience['end_date'])->format('M Y') }}
                                            @if($experience['location'])
                                                <span class="mx-2">&bull;</span>
                                                <x-ui::icon name="map-pin" class="w-4 h-4 inline" />
                                                {{ $experience['location'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($experience['description'])
                                    <p class="mb-3 text-sm leading-relaxed text-zinc-300">
                                        {{ Str::limit($experience['description'], 200) }}
                                    </p>
                                @endif

                                @if(!empty($experience['technologies']))
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($experience['technologies'] as $skill)
                                            <span class="skill-badge border border-emerald-400/20 bg-emerald-500/10 text-emerald-200">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if(!empty($experience['achievements']) && collect($experience['achievements'])->filter()->count() > 0)
                                    <div class="mt-3 border-t border-white/10 pt-3">
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-400">
                                            Key Achievements
                                        </p>
                                        <ul class="space-y-1">
                                            @foreach(collect($experience['achievements'])->filter() as $achievement)
                                                <li class="flex items-start gap-2 text-sm text-zinc-300">
                                                    <x-ui::icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                                                    {{ $achievement }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="flex lg:flex-col gap-2 lg:shrink-0">
                                <x-ui::button variant="ghost" size="sm" wire:click="moveUp({{ $experience['id'] }})" icon="chevron-up" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only">Move Up</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="moveDown({{ $experience['id'] }})" icon="chevron-down" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only">Move Down</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="editExperience({{ $experience['id'] }})" icon="pencil" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Edit</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="deleteExperience({{ $experience['id'] }})" wire:confirm="Are you sure you want to delete this experience?" icon="trash-2" class="flex-1 border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Delete</span>
                                </x-ui::button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-12 text-center backdrop-blur-xl">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/60">
                        <x-ui::icon name="briefcase" class="w-8 h-8 text-emerald-300" />
                    </div>
                    <x-ui::heading size="md" class="mb-2 text-white">No Experience Added Yet</x-ui::heading>
                    <p class="mb-4 text-sm text-zinc-400">Start building your professional history</p>
                    <x-ui::button wire:click="addExperience" variant="primary" icon="plus" class="{{ $primaryButtonClasses }}">
                        Add Your First Experience
                    </x-ui::button>
                </div>
            @endforelse
        </div>
    </x-ui::card>
    @endif
</div>

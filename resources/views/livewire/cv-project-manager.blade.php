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
                <x-ui::heading size="lg" class="text-white">Projects</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Showcase your best work and side projects</p>
            </div>
            <x-ui::button
                wire:click="addProject"
                variant="primary"
                size="sm"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Project
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveProject" class="mb-8 space-y-4 form-section">
                <x-ui::heading size="md" class="text-emerald-300">
                    {{ $editingId ? 'Edit Project' : 'Add New Project' }}
                    {{ $editingId ? '<span class="text-xs text-zinc-500">Auto-saves as you type</span>' : '' }}
                </x-ui::heading>

                <x-ui::input
                    wire:model.live.debounce.1000ms="form.name"
                    label="Project Name"
                    placeholder="e.g., E-Commerce Platform"
                    required
                    :error="$errors->first('form.name')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.name') ? $errorFieldClasses : '' }}"
                />

                <x-ui::textarea
                    wire:model.live.debounce.1000ms="form.description"
                    label="Description"
                    placeholder="Describe what the project does, your role, and the technologies used..."
                    rows="4"
                    required
                    :error="$errors->first('form.description')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.description') ? $errorFieldClasses : '' }}"
                />
                <x-ui::description class="text-zinc-400">Focus on what you built and the impact it had</x-ui::description>

                <div>
                    <x-ui::label class="mb-3 block text-zinc-200">Key Achievements</x-ui::label>
                    @foreach($form['key_achievements'] as $index => $achievement)
                        <div class="flex gap-2 mb-2">
                            <x-ui::input
                                wire:model.live.debounce.1000ms="form.key_achievements.{{ $index }}"
                                placeholder="e.g., Served 10,000+ users"
                                class="{{ $fieldClasses }} flex-1"
                            />
                            <x-ui::button type="button" variant="ghost" size="sm" wire:click="removeAchievement({{ $index }})" icon="x" class="{{ $ghostButtonClasses }} shrink-0" />
                        </div>
                    @endforeach
                    <x-ui::button type="button" variant="ghost" size="sm" wire:click="addAchievement" icon="plus" class="{{ $ghostButtonClasses }} mt-2">
                        Add Achievement
                    </x-ui::button>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.project_url"
                            label="Project URL"
                            placeholder="https://..."
                            :error="$errors->first('form.project_url')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.project_url') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.github_url"
                            label="GitHub URL"
                            placeholder="https://github.com/..."
                            :error="$errors->first('form.github_url')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.github_url') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.start_date"
                            type="date"
                            label="Start Date"
                            :error="$errors->first('form.start_date')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.start_date') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model.live.debounce.1000ms="form.end_date"
                            type="date"
                            label="End Date (optional)"
                            :error="$errors->first('form.end_date')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.end_date') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="flex flex-col justify-end gap-2 border-t border-white/10 pt-4 sm:flex-row">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit" class="{{ $ghostButtonClasses }}">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Project
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-0">
            @forelse($projects as $project)
                <div class="timeline-item group">
                    <div class="timeline-dot">
                        <x-ui::icon name="folder" class="w-3 h-3 text-white" />
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10 md:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                                        <x-ui::icon name="folder" class="w-6 h-6 text-emerald-300" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">
                                            {{ $project['name'] }}
                                        </h3>
                                        @if($project['start_date'])
                                            <p class="mt-1 text-sm text-zinc-400">
                                                {{ \Carbon\Carbon::parse($project['start_date'])->format('M Y') }}
                                                @if($project['end_date'])
                                                    - {{ \Carbon\Carbon::parse($project['end_date'])->format('M Y') }}
                                                @endif
                                            </p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @if($project['project_url'])
                                                <a href="{{ $project['project_url'] }}" target="_blank" class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-xs text-zinc-300 transition-colors hover:bg-white/10 hover:text-white">
                                                    <x-ui::icon name="external-link" class="w-3 h-3" />
                                                    Live
                                                </a>
                                            @endif
                                            @if($project['github_url'])
                                                <a href="{{ $project['github_url'] }}" target="_blank" class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-xs text-zinc-300 transition-colors hover:bg-white/10 hover:text-white">
                                                    <x-ui::icon name="github" class="w-3 h-3" />
                                                    GitHub
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($project['description'])
                                    <p class="mb-3 text-sm leading-relaxed text-zinc-300">
                                        {{ Str::limit($project['description'], 300) }}
                                    </p>
                                @endif

                                @if(!empty($project['key_achievements']) && collect($project['key_achievements'])->filter()->count() > 0)
                                    <div class="mt-3 border-t border-white/10 pt-3">
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-400">
                                            Key Achievements
                                        </p>
                                        <ul class="space-y-1">
                                            @foreach(collect($project['key_achievements'])->filter() as $achievement)
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
                                <x-ui::button variant="ghost" size="sm" wire:click="moveUp({{ $project['id'] }})" icon="chevron-up" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only">Move Up</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="moveDown({{ $project['id'] }})" icon="chevron-down" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only">Move Down</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="editProject({{ $project['id'] }})" icon="pencil" class="{{ $ghostButtonClasses }} flex-1 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Edit</span>
                                </x-ui::button>
                                <x-ui::button variant="ghost" size="sm" wire:click="deleteProject({{ $project['id'] }})" wire:confirm="Are you sure you want to delete this project?" icon="trash-2" class="flex-1 border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Delete</span>
                                </x-ui::button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-12 text-center backdrop-blur-xl">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/60">
                        <x-ui::icon name="folder" class="w-8 h-8 text-emerald-300" />
                    </div>
                    <x-ui::heading size="md" class="mb-2 text-white">No Projects Added Yet</x-ui::heading>
                    <p class="mb-4 text-sm text-zinc-400">Showcase your best work</p>
                    <x-ui::button wire:click="addProject" variant="primary" icon="plus" class="{{ $primaryButtonClasses }}">
                        Add Your First Project
                    </x-ui::button>
                </div>
            @endforelse
        </div>
    </x-ui::card>
    @endif
</div>

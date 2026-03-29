<div>
    <x-ui::card class="card-hover">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <x-ui::heading size="lg">Skills</x-ui::heading>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Showcase your expertise</p>
            </div>
            <x-ui::button
                wire:click="addSkill"
                variant="primary"
                icon="plus"
                class="w-full sm:w-auto"
            >
                Add Skill
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveSkill" class="space-y-4 mb-8 form-section">
                <x-ui::heading size="md" class="text-emerald-600 dark:text-emerald-400">
                    {{ $editingId ? 'Edit Skill' : 'Add New Skill' }}
                </x-ui::heading>

                <x-ui::input wire:model="form.name" label="Skill Name" placeholder="e.g., Python, React, Project Management" required />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui::select wire:model="form.category" label="Category" :options="$categories" />

                    <x-ui::select wire:model="form.level" label="Proficiency Level" :options="$levels" />
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" icon="check">
                        {{ $editingId ? 'Update' : 'Add' }} Skill
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="mb-8">
            <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Quick Add Common Skills</h4>
            <div class="flex flex-wrap gap-2">
                @foreach(array_slice($commonSkills, 0, 15) as $skill)
                    <button
                        wire:click="quickAddSkill('{{ $skill }}')"
                        class="skill-badge bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:shadow-sm"
                    >
                        <x-ui::icon name="plus" class="w-3 h-3" />
                        {{ $skill }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @foreach($categories as $categoryKey => $categoryLabel)
                @if(isset($skills[$categoryKey]) && count($skills[$categoryKey]) > 0)
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-ui::icon name="{{ $categoryIcons[$categoryKey] ?? 'check' }}" class="w-4 h-4 text-emerald-500" />
                            {{ $categoryLabel }}
                            <span class="text-xs font-normal text-zinc-500 dark:text-zinc-400">({{ count($skills[$categoryKey]) }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($skills[$categoryKey] as $skill)
                                <div class="group relative bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-4 transition-all duration-200 hover:shadow-sm border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-semibold text-zinc-900 dark:text-white truncate">
                                                {{ $skill['name'] }}
                                            </h5>
                                            @if($skill['level'])
                                                <div class="flex items-center gap-2 mt-2">
                                                    <div class="flex-1 bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5 overflow-hidden">
                                                        <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full" style="width: {{ $skill['level'] * 20 }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-zinc-500 dark:text-zinc-400 shrink-0">
                                                        {{ $levels[$skill['level']] ?? $skill['level'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <x-ui::button variant="ghost" size="sm" wire:click="editSkill({{ $skill['id'] }})" icon="pencil" class="shrink-0" />
                                            <x-ui::button variant="ghost" size="sm" wire:click="deleteSkill({{ $skill['id'] }})" wire:confirm="Delete this skill?" icon="trash-2" class="shrink-0 text-red-600 hover:text-red-700" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(empty($skills))
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                    <x-ui::icon name="zap" class="w-8 h-8 text-zinc-400" />
                </div>
                <x-ui::heading size="md" class="mb-2">No Skills Added Yet</x-ui::heading>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">Start showcasing your technical and soft skills</p>
                <x-ui::button wire:click="addSkill" variant="primary" icon="plus">
                    Add Your First Skill
                </x-ui::button>
            </div>
        @endif
    </x-ui::card>
</div>

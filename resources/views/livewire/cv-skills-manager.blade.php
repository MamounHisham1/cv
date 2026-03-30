@php
    $glassCardClasses = 'card-hover overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-5 md:p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-200 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $categoryIcons = [
        'general' => 'star',
        'technical' => 'code',
        'software' => 'layout-grid',
        'industry' => 'briefcase',
        'soft' => 'users',
        'frontend' => 'monitor',
        'backend' => 'server',
        'tools' => 'wrench',
        'Frontend' => 'monitor',
        'Backend' => 'server',
        'Tools' => 'wrench',
    ];
    $allCategoryKeys = array_unique(array_merge(array_keys($categories), array_keys($skills)));
    $categoryLabels = array_merge($categories, array_combine($allCategoryKeys, $allCategoryKeys));
    $categoryLabels = array_map(fn ($k) => ucfirst($k), $categoryLabels);
    $levelWidths = [
        'beginner' => 25,
        'intermediate' => 50,
        'advanced' => 75,
        'expert' => 100,
    ];
@endphp

<div>
    <x-ui::card class="{{ $glassCardClasses }}">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <x-ui::heading size="lg" class="text-white">Skills</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Showcase your expertise</p>
            </div>
            <x-ui::button
                wire:click="addSkill"
                variant="primary"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Skill
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveSkill" class="space-y-4 mb-8 form-section">
                <x-ui::heading size="md" class="text-emerald-300">
                    {{ $editingId ? 'Edit Skill' : 'Add New Skill' }}
                </x-ui::heading>

                <x-ui::input wire:model="form.name" label="Skill Name" placeholder="e.g., Python, React, Project Management" required :error="$errors->first('form.name')" class="{{ $fieldClasses }}" />

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::select wire:model="form.category" label="Category" :options="$categories" class="{{ $fieldClasses }}" />
                    </div>

                    <div class="form-field">
                        <x-ui::select wire:model="form.level" label="Proficiency Level" :options="$levels" class="{{ $fieldClasses }}" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 border-t border-white/10 pt-4">
                    <x-ui::button type="button" variant="ghost" wire:click="cancelEdit" class="{{ $ghostButtonClasses }}">
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" icon="check" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Skill
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="mb-8">
            <h4 class="mb-3 text-sm font-semibold text-zinc-200">Quick Add Common Skills</h4>
            <div class="flex flex-wrap gap-2">
                @foreach(array_slice($commonSkills, 0, 15) as $skill)
                    <button
                        wire:click="quickAddSkill('{{ $skill }}')"
                        class="skill-badge border border-emerald-400/20 bg-emerald-500/10 text-emerald-200 hover:bg-emerald-500/15"
                    >
                        <x-ui::icon name="plus" class="w-3 h-3" />
                        {{ $skill }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @foreach($allCategoryKeys as $categoryKey)
                @if(isset($skills[$categoryKey]) && count($skills[$categoryKey]) > 0)
                    <div>
                        <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-white">
                            <x-ui::icon name="{{ $categoryIcons[$categoryKey] ?? 'check' }}" class="w-4 h-4 text-emerald-500" />
                            {{ $categoryLabels[$categoryKey] ?? ucfirst($categoryKey) }}
                            <span class="text-xs font-normal text-zinc-400">({{ count($skills[$categoryKey]) }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($skills[$categoryKey] as $skill)
                                <div class="group relative rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h5 class="truncate font-semibold text-white">
                                                {{ $skill['name'] }}
                                            </h5>
                                            @if($skill['level'])
                                                <div class="flex items-center gap-2 mt-2">
                                                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-zinc-900/80">
                                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600" style="width: {{ $levelWidths[$skill['level']] ?? 50 }}%"></div>
                                                    </div>
                                                    <span class="shrink-0 text-xs text-zinc-400">
                                                        {{ $levels[$skill['level']] ?? $skill['level'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <x-ui::button variant="ghost" size="sm" wire:click="editSkill({{ $skill['id'] }})" icon="pencil" class="{{ $ghostButtonClasses }} shrink-0" />
                                            <x-ui::button variant="ghost" size="sm" wire:click="deleteSkill({{ $skill['id'] }})" wire:confirm="Delete this skill?" icon="trash-2" class="shrink-0 border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200" />
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
            <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-12 text-center backdrop-blur-xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-zinc-900/60">
                    <x-ui::icon name="zap" class="w-8 h-8 text-emerald-300" />
                </div>
                <x-ui::heading size="md" class="mb-2 text-white">No Skills Added Yet</x-ui::heading>
                <p class="mb-4 text-sm text-zinc-400">Start showcasing your technical and soft skills</p>
                <x-ui::button wire:click="addSkill" variant="primary" icon="plus" class="{{ $primaryButtonClasses }}">
                    Add Your First Skill
                </x-ui::button>
            </div>
        @endif
    </x-ui::card>
</div>

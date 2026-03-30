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

                <div
                    x-data="{
                        open: false,
                        focused: false,
                        query: @js($form['category']),
                        presets: @js(array_values($categories)),
                        highlighted: -1,
                        get filtered() {
                            const q = (this.query || '').toLowerCase();
                            return (this.presets || []).filter(c => c && c.toLowerCase().includes(q));
                        },
                        get isExactMatch() {
                            const q = (this.query || '').toLowerCase();
                            return (this.presets || []).some(c => c && c.toLowerCase() === q);
                        },
                        get hasCreateOption() {
                            return this.query && !this.isExactMatch;
                        },
                        get totalOptions() {
                            return this.filtered.length + (this.hasCreateOption ? 1 : 0);
                        },
                        get highlightedOption() {
                            if (this.highlighted < 0) {
                                return this.filtered[0] || (this.hasCreateOption ? this.query : null);
                            }
                            if (this.highlighted < this.filtered.length) {
                                return this.filtered[this.highlighted];
                            }
                            if (this.hasCreateOption && this.highlighted === this.filtered.length) {
                                return this.query;
                            }
                            return null;
                        },
                        select(value) {
                            this.query = value;
                            this.open = false;
                            $wire.set('form.category', value).then(() => this.$refs.input.blur());
                        }
                    }"
                    @click.away="if (!focused) open = false"
                    class="form-field"
                >
                    <label class="mb-1.5 block text-sm font-medium text-zinc-300">Category</label>
                    <div class="relative">
                        <div class="relative">
                            <x-ui::icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-500" />
                            <input
                                type="text"
                                x-model="query"
                                x-ref="input"
                                @focus="focused = true; open = true"
                                @blur="focused = false; $wire.set('form.category', query); $nextTick(() => { if (!focused) open = false; })"
                                @keydown.escape="open = false"
                                @keydown.enter.prevent="select(highlightedOption || query)"
                                @keydown.down.prevent="highlighted = Math.min(highlighted + 1, totalOptions - 1)"
                                @keydown.up.prevent="highlighted = Math.max(highlighted - 1, 0)"
                                placeholder="Type to search or create new..."
                                class="flex h-10 w-full rounded-lg border border-white/10 bg-zinc-900/50 pl-10 pr-3 py-2 text-sm text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm transition-colors focus-visible:outline-none focus-visible:border-emerald-500/50 focus-visible:ring-2 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0"
                            />
                        </div>

                        <template x-if="open">
                            <div class="absolute z-50 mt-1 w-full max-h-48 overflow-y-auto rounded-xl border border-white/10 bg-zinc-900/95 py-1 shadow-xl shadow-black/30 backdrop-blur-xl">
                                <template x-if="filtered.length > 0">
                                    <div>
                                        <template x-for="(option, index) in filtered" :key="option">
                                            <button
                                                type="button"
                                                @mousedown.prevent="select(option)"
                                                @mouseenter="highlighted = index"
                                                :class="index === highlighted ? 'bg-emerald-500/15 text-emerald-300' : ((query || '').toLowerCase() === option.toLowerCase() ? 'bg-emerald-500/15 text-emerald-300' : 'text-zinc-200 hover:bg-white/10 hover:text-white')"
                                                class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm transition-colors"
                                            >
                                                <x-ui::icon name="check" class="w-3.5 h-3.5 shrink-0" x-show="(query || '').toLowerCase() === option.toLowerCase() && index !== highlighted" />
                                                <span class="flex-1" x-text="option"></span>
                                            </button>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="query && !isExactMatch">
                                    <div class="border-t border-white/10 pt-1 mt-1">
                                        <button
                                            type="button"
                                            @mousedown.prevent="select(query)"
                                            @mouseenter="highlighted = filtered.length"
                                            :class="highlighted === filtered.length ? 'bg-emerald-500/15 text-emerald-300' : 'text-emerald-400 hover:bg-emerald-500/10'"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm transition-colors"
                                        >
                                            <x-ui::icon name="plus" class="w-3.5 h-3.5 shrink-0" />
                                            <span>Create "<span x-text="query"></span>"</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="form-field">
                    <x-ui::select wire:model="form.level" label="Proficiency Level" :options="$levels" class="{{ $fieldClasses }}" />
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

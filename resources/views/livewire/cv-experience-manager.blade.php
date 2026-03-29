<div>
    <flux:card class="card-hover">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <flux:heading size="lg">Work Experience</flux:heading>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Add your professional journey</p>
            </div>
            <flux:button
                wire:click="addExperience"
                variant="primary"
                icon="plus"
                class="w-full sm:w-auto"
            >
                Add Experience
            </flux:button>
        </div>

        @if($showForm)
            <form wire:submit="saveExperience" class="space-y-6 mb-8 form-section">
                <flux:heading size="md" class="text-emerald-600 dark:text-emerald-400">
                    {{ $editingId ? 'Edit Experience' : 'Add New Experience' }}
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Company</flux:label>
                        <flux:input wire:model="form.company" placeholder="Company Name" required />
                    </flux:field>
                    <flux:field>
                        <flux:label>Job Title</flux:label>
                        <flux:input wire:model="form.title" placeholder="e.g., Senior Project Manager" required />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Location</flux:label>
                    <flux:input wire:model="form.location" placeholder="City, Country (optional)" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Start Date</flux:label>
                        <flux:input wire:model="form.start_date" type="date" required />
                    </flux:field>
                    <flux:field>
                        <flux:label>End Date</flux:label>
                        @if(!$form['is_current'])
                            <flux:input wire:model="form.end_date" type="date" />
                        @else
                            <flux:input value="Present" disabled />
                        @endif
                        <flux:checkbox wire:model="form.is_current" class="mt-2">
                            I currently work here
                        </flux:checkbox>
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="form.description" placeholder="Describe your role, responsibilities, and achievements..." rows="4" required />
                    <flux:description>Use action verbs and include quantifiable achievements when possible</flux:description>
                </flux:field>

                <div>
                    <flux:label class="mb-3 block">Key Skills & Technologies Used</flux:label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($commonSkills as $skill)
                            <label class="cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="form.technologies"
                                    value="{{ $skill }}"
                                    class="sr-only peer"
                                />
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 peer-checked:bg-emerald-100 dark:peer-checked:bg-emerald-900/30 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-300 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                    {{ $skill }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <flux:label class="mb-3 block">Key Achievements</flux:label>
                    @foreach($form['achievements'] as $index => $achievement)
                        <div class="flex gap-2 mb-2">
                            <flux:input
                                wire:model="form.achievements.{{ $index }}"
                                placeholder="e.g., Reduced costs by 30% through optimization"
                                class="flex-1"
                            />
                            <flux:button type="button" variant="ghost" size="sm" wire:click="removeAchievement({{ $index }})" icon="x-mark" class="shrink-0" />
                        </div>
                    @endforeach
                    <flux:button type="button" variant="ghost" size="sm" wire:click="addAchievement" icon="plus" class="mt-2">
                        Add Achievement
                    </flux:button>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="button" variant="ghost" wire:click="cancelEdit">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check">
                        {{ $editingId ? 'Update' : 'Add' }} Experience
                    </flux:button>
                </div>
            </form>
        @endif

        <!-- Experience Timeline -->
        <div class="space-y-0">
            @forelse($experiences as $experience)
                <div class="timeline-item group">
                    <div class="timeline-dot">
                        <flux:icon name="briefcase" class="w-3 h-3 text-white" />
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-4 md:p-6 transition-all duration-200 hover:shadow-sm border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                        <flux:icon name="building-office" class="w-6 h-6 text-emerald-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                            {{ $experience['title'] }}
                                        </h3>
                                        <p class="text-emphasis text-zinc-700 dark:text-zinc-300">
                                            {{ $experience['company'] }}
                                        </p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ \Carbon\Carbon::parse($experience['start_date'])->format('M Y') }} -
                                            {{ $experience['is_current'] ? 'Present' : \Carbon\Carbon::parse($experience['end_date'])->format('M Y') }}
                                            @if($experience['location'])
                                                <span class="mx-2">•</span>
                                                <flux:icon name="map-pin" class="w-4 h-4 inline" />
                                                {{ $experience['location'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($experience['description'])
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                                        {{ Str::limit($experience['description'], 200) }}
                                    </p>
                                @endif

                                @if(!empty($experience['technologies']))
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($experience['technologies'] as $skill)
                                            <span class="skill-badge bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if(!empty($experience['achievements']) && collect($experience['achievements'])->filter()->count() > 0)
                                    <div class="mt-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                        <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">
                                            Key Achievements
                                        </p>
                                        <ul class="space-y-1">
                                            @foreach(collect($experience['achievements'])->filter() as $achievement)
                                                <li class="text-sm text-zinc-600 dark:text-zinc-400 flex items-start gap-2">
                                                    <flux:icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                                                    {{ $achievement }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="flex lg:flex-col gap-2 lg:shrink-0">
                                <flux:button variant="ghost" size="sm" wire:click="editExperience({{ $experience['id'] }})" icon="pencil" class="flex-1 lg:flex-none">
                                    <span class="sr-only lg:not-sr-only">Edit</span>
                                </flux:button>
                                <flux:button variant="ghost" size="sm" wire:click="deleteExperience({{ $experience['id'] }})" wire:confirm="Are you sure you want to delete this experience?" icon="trash" class="flex-1 lg:flex-none text-red-600 hover:text-red-700 dark:text-red-400">
                                    <span class="sr-only lg:not-sr-only">Delete</span>
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mx-auto mb-4 flex items-center justify-center">
                        <flux:icon name="briefcase" class="w-8 h-8 text-zinc-400" />
                    </div>
                    <flux:heading size="md" class="mb-2">No Experience Added Yet</flux:heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">Start building your professional history</p>
                    <flux:button wire:click="addExperience" variant="primary" icon="plus">
                        Add Your First Experience
                    </flux:button>
                </div>
            @endforelse
        </div>
    </flux:card>
</div>

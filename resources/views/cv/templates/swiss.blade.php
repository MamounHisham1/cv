<div class="font-sans text-gray-900 bg-white">
    <div class="h-2 bg-red-600" style="print-color-adjust: exact;"></div>

    <div class="px-8 pt-8 pb-6">
        @php
            $allSections = $cv->getSectionOrder();
            $leftSections = ['skills', 'languages', 'certifications', 'education'];
            $rightSections = ['experience', 'projects'];
            $leftOrder = array_values(array_intersect($allSections, $leftSections));
            $rightOrder = array_values(array_intersect($allSections, $rightSections));
        @endphp

        <header class="mb-6">
            <h1 class="text-5xl font-black leading-none tracking-tight">
                {{ strtoupper($cv->personal_info['first_name'] ?? '') }}
            </h1>
            <h1 class="text-5xl font-light leading-none tracking-tight text-gray-400">
                {{ strtoupper($cv->personal_info['last_name'] ?? '') }}
            </h1>
            @if($cv->personal_info['title'] ?? false)
                <p class="text-red-600 font-medium mt-2 text-lg">{{ $cv->personal_info['title'] }}</p>
            @endif
        </header>

        <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600 border-t-2 border-gray-900 pt-3 mb-6">
            @if($cv->personal_info['email'] ?? false)
                <span>{{ $cv->personal_info['email'] }}</span>
            @endif
            @if($cv->personal_info['phone'] ?? false)
                <span>{{ $cv->personal_info['phone'] }}</span>
            @endif
            @if($cv->personal_info['location'] ?? false)
                <span>{{ $cv->personal_info['location'] }}</span>
            @endif
            @if($cv->personal_info['linkedin'] ?? false)
                <span>LinkedIn</span>
            @endif
            @if($cv->personal_info['github'] ?? false)
                <span>GitHub</span>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-1 space-y-6">
                @foreach($leftOrder as $sectionKey)
                    @switch($sectionKey)
                        @case('skills')
                            @if($cv->skills->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Skills</h2>
                                    <div class="space-y-1">
                                        @foreach($cv->skills as $skill)
                                            <div class="flex justify-between items-baseline text-sm">
                                                <span class="font-medium">{{ $skill->name }}</span>
                                                <span class="text-xs text-gray-400">{{ ucfirst($skill->level) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @break

                        @case('languages')
                            @if($cv->languages->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Languages</h2>
                                    <div class="space-y-1">
                                        @foreach($cv->languages as $lang)
                                            <div class="text-sm">
                                                <span class="font-medium">{{ $lang->language }}</span>
                                                <span class="text-gray-400 ml-1">{{ ucfirst($lang->proficiency) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @break

                        @case('certifications')
                            @if($cv->certifications->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Certifications</h2>
                                    <div class="space-y-2">
                                        @foreach($cv->certifications as $cert)
                                            <div class="text-sm">
                                                <div class="font-medium">{{ $cert->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $cert->issuing_organization }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @break

                        @case('education')
                            @if($cv->educations->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Education</h2>
                                    <div class="space-y-2">
                                        @foreach($cv->educations as $edu)
                                            <div>
                                                <div class="font-medium text-sm">{{ $edu->institution }}</div>
                                                <div class="text-xs text-gray-500">{{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</div>
                                                <div class="text-xs text-gray-400">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @break
                    @endswitch
                @endforeach
            </div>

            <div class="col-span-2 space-y-6">
                @if($cv->summary)
                    <section>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Profile</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $cv->summary }}</p>
                    </section>
                @endif

                @foreach($rightOrder as $sectionKey)
                    @switch($sectionKey)
                        @case('experience')
                            @if($cv->experiences->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-4 border-b border-red-600 pb-1">Experience</h2>
                                    <div class="space-y-5">
                                        @foreach($cv->experiences as $exp)
                                            <div>
                                                <div class="flex justify-between items-baseline mb-1">
                                                    <h3 class="font-bold">{{ $exp->title }}</h3>
                                                    <span class="text-xs text-red-600 font-medium">
                                                        {{ $exp->start_date?->format('m/Y') }} - {{ $exp->is_current ? 'Present' : $exp->end_date?->format('m/Y') }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-600">{{ $exp->company }}{{ $exp->location ? ' · ' . $exp->location : '' }}</div>
                                                @if($exp->description)
                                                    <p class="text-sm text-gray-500 mt-1">{{ $exp->description }}</p>
                                                @endif
                                                @if($exp->achievements)
                                                    <ul class="mt-1 text-sm text-gray-600 list-disc list-inside space-y-0.5">
                                                        @foreach($exp->achievements as $achievement)
                                                            <li>{{ $achievement }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @break

                        @case('projects')
                            @if($cv->projects->count() > 0)
                                <section>
                                    <h2 class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3 border-b border-red-600 pb-1">Projects</h2>
                                    @foreach($cv->projects as $project)
                                        <div class="mb-3">
                                            <h3 class="font-bold">{{ $project->name }}</h3>
                                            @if($project->description)
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $project->description }}</p>
                                            @endif
                                            @if($project->key_achievements && count($project->key_achievements) > 0)
                                                <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                                                    @foreach($project->key_achievements as $achievement)
                                                        <li>{{ $achievement }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </section>
                            @endif
                            @break
                    @endswitch
                @endforeach
            </div>
        </div>
    </div>
</div>

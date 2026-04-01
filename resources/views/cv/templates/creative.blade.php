<div class="flex min-h-full">
    @php
        $allSections = $cv->getSectionOrder();
        $sidebarSections = ['skills', 'certifications', 'languages'];
        $mainSections = ['experience', 'education', 'projects'];
        $sidebarOrder = array_values(array_intersect($allSections, $sidebarSections));
        $mainOrder = array_values(array_intersect($allSections, $mainSections));
    @endphp

    <aside class="w-1/3 bg-slate-800 text-white p-6">
        <div class="w-24 h-24 bg-slate-600 rounded-full mx-auto mb-4 flex items-center justify-center text-3xl font-bold">
            {{ substr($cv->personal_info['first_name'] ?? '', 0, 1) }}{{ substr($cv->personal_info['last_name'] ?? '', 0, 1) }}
        </div>

        <div class="mb-5">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">Contact</h3>
            <div class="space-y-1 text-sm">
                @if($cv->personal_info['email'] ?? false)
                    <div>{{ $cv->personal_info['email'] }}</div>
                @endif
                @if($cv->personal_info['phone'] ?? false)
                    <div>{{ $cv->personal_info['phone'] }}</div>
                @endif
                @if($cv->personal_info['location'] ?? false)
                    <div>{{ $cv->personal_info['location'] }}</div>
                @endif
                @if($cv->personal_info['linkedin'] ?? false)
                    <div>{{ $cv->personal_info['linkedin'] }}</div>
                @endif
            </div>
        </div>

        @foreach($sidebarOrder as $sectionKey)
            @switch($sectionKey)
                @case('skills')
                    @if($cv->skills->count() > 0)
                        <div class="mb-5">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">Skills</h3>
                            <div class="space-y-1.5">
                                @foreach($cv->skills as $skill)
                                    <div>
                                        <div class="text-xs mb-0.5">{{ $skill->name }}</div>
                                        <div class="h-1.5 bg-slate-600 rounded-full overflow-hidden">
                                            <div class="h-full bg-teal-400 rounded-full" style="width: {{ (match($skill->level) { 'beginner' => 1, 'intermediate' => 2, 'advanced' => 3, 'expert' => 4, default => 3 }) * 25 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @break

                @case('certifications')
                    @if($cv->certifications->count() > 0)
                        <div class="mb-5">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">Certifications</h3>
                            <div class="space-y-2 text-sm">
                                @foreach($cv->certifications as $cert)
                                    <div>
                                        <div class="font-medium">{{ $cert->name }}</div>
                                        <div class="text-slate-400 text-xs">{{ $cert->issuing_organization }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @break

                @case('languages')
                    @if($cv->languages->count() > 0)
                        <div class="mb-5">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Languages</h3>
                            <div class="space-y-2 text-sm">
                                @foreach($cv->languages as $lang)
                                    <div class="flex justify-between">
                                        <span>{{ $lang->language }}</span>
                                        <span class="text-slate-400">{{ ucfirst($lang->proficiency) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @break
            @endswitch
        @endforeach
    </aside>

    <main class="w-2/3 p-8 bg-white">
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">
                {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
            </h1>
            @if($cv->personal_info['title'] ?? false)
                <p class="text-xl text-teal-600">{{ $cv->personal_info['title'] }}</p>
            @endif
        </header>

        @if($cv->summary)
            <section class="mb-8">
                <h2 class="text-lg font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <span class="w-8 h-1 bg-teal-500 rounded"></span>
                    About Me
                </h2>
                <p class="text-gray-600 leading-relaxed">{{ $cv->summary }}</p>
            </section>
        @endif

        @foreach($mainOrder as $sectionKey)
            @switch($sectionKey)
                @case('experience')
                    @if($cv->experiences->count() > 0)
                        <section class="mb-8">
                            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-1 bg-teal-500 rounded"></span>
                                Experience
                            </h2>
                            <div class="space-y-6">
                                @foreach($cv->experiences as $exp)
                                    <div class="border-l-2 border-teal-200 pl-4">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="font-bold text-slate-800">{{ $exp->title }}</h3>
                                            <span class="text-sm text-teal-600 font-medium">{{ $exp->duration }}</span>
                                        </div>
                                        <div class="text-gray-700 mb-2">{{ $exp->company }}</div>
                                        <p class="text-gray-600 text-sm">{{ $exp->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                    @break

                @case('education')
                    @if($cv->educations->count() > 0)
                        <section class="mb-8">
                            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-1 bg-teal-500 rounded"></span>
                                Education
                            </h2>
                            <div class="space-y-4">
                                @foreach($cv->educations as $edu)
                                    <div class="border-l-2 border-teal-200 pl-4">
                                        <div class="font-bold text-slate-800">{{ $edu->degree }}</div>
                                        <div class="text-gray-700">{{ $edu->institution }}</div>
                                        @if($edu->field_of_study)
                                            <div class="text-sm text-gray-500">{{ $edu->field_of_study }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                    @break

                @case('projects')
                    @if($cv->projects->count() > 0)
                        <section class="mb-8">
                            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-1 bg-teal-500 rounded"></span>
                                Projects
                            </h2>
                            <div class="space-y-4">
                                @foreach($cv->projects as $project)
                                    <div class="border-l-2 border-teal-200 pl-4">
                                        <div class="font-bold text-slate-800">{{ $project->name }}</div>
                                        @if($project->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $project->description }}</p>
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
                            </div>
                        </section>
                    @endif
                    @break
            @endswitch
        @endforeach
    </main>
</div>

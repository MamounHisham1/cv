<div class="p-8 font-sans text-gray-900 bg-white">
    <!-- Header -->
    <header class="mb-8">
        <div class="flex items-baseline gap-4 mb-1">
            <h1 class="text-3xl font-bold">
                {{ $cv->personal_info['first_name'] ?? '' }} <span class="font-light">{{ $cv->personal_info['last_name'] ?? '' }}</span>
            </h1>
        </div>
        @if($cv->personal_info['title'] ?? false)
            <p class="text-gray-500 text-lg">{{ $cv->personal_info['title'] }}</p>
        @endif
        <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-500 mt-2">
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
    </header>

    <!-- Summary -->
    @if($cv->summary)
        <section class="mb-8">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">About</h2>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $cv->summary }}</p>
        </section>
    @endif

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Experience</h2>
                        <div class="relative ml-3">
                            <div class="absolute left-0 top-2 bottom-2 w-px bg-gray-300"></div>

                            @foreach($cv->experiences as $i => $exp)
                                <div class="relative pl-8 pb-6 {{ $loop->last ? 'pb-0' : '' }}">
                                    <div class="absolute left-[-4px] top-2 w-[9px] h-[9px] rounded-full {{ $loop->first ? 'bg-gray-800 ring-4 ring-gray-100' : 'bg-gray-400' }}"></div>

                                    <div class="text-xs text-gray-400 font-medium mb-1">
                                        {{ $exp->start_date?->format('m/Y') }} — {{ $exp->is_current ? 'Present' : $exp->end_date?->format('m/Y') }}
                                    </div>

                                    <h3 class="font-bold text-gray-900">{{ $exp->title }}</h3>
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

                                    @if($exp->technologies)
                                        <div class="mt-1.5 flex flex-wrap gap-1">
                                            @foreach($exp->technologies as $tech)
                                                <span class="text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Skills</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($cv->skills as $skill)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('education')
                @if($cv->educations->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Education</h2>
                        @foreach($cv->educations as $edu)
                            <div class="mb-2 flex justify-between items-baseline">
                                <div>
                                    <span class="font-medium">{{ $edu->institution }}</span>
                                    <span class="text-sm text-gray-500"> — {{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</span>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Certifications</h2>
                        @foreach($cv->certifications as $cert)
                            <div class="text-sm mb-1">
                                <span class="font-medium">{{ $cert->name }}</span>
                                <span class="text-gray-400"> — {{ $cert->issuing_organization }}</span>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('languages')
                @if($cv->languages->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Languages</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach($cv->languages as $lang)
                                <span class="text-sm">{{ $lang->language }} <span class="text-gray-400">({{ ucfirst($lang->proficiency) }})</span></span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-3">
                                <h3 class="font-semibold">{{ $project->name }}</h3>
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

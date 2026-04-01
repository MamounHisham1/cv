<div class="p-10 font-sans text-gray-800 bg-white">
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-4xl font-light text-gray-900 mb-2">
            {{ $cv->personal_info['first_name'] ?? '' }} <span class="font-semibold">{{ $cv->personal_info['last_name'] ?? '' }}</span>
        </h1>
        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
            @if($cv->personal_info['email'] ?? false)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ $cv->personal_info['email'] }}
                </span>
            @endif
            @if($cv->personal_info['phone'] ?? false)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    {{ $cv->personal_info['phone'] }}
                </span>
            @endif
            @if($cv->personal_info['location'] ?? false)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $cv->personal_info['location'] }}
                </span>
            @endif
        </div>
    </header>

    @if($cv->summary)
        <section class="mb-8">
            <p class="text-gray-600 leading-relaxed">{{ $cv->summary }}</p>
        </section>
    @endif

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Experience</h2>
                        @foreach($cv->experiences as $exp)
                            <div class="mb-6">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-semibold text-lg">{{ $exp->title }}</h3>
                                    <span class="text-sm text-gray-500">{{ $exp->duration }}</span>
                                </div>
                                <div class="text-gray-700 mb-2">{{ $exp->company }}</div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $exp->description }}</p>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Skills</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($cv->skills as $skill)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('education')
                @if($cv->educations->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Education</h2>
                        @foreach($cv->educations as $edu)
                            <div class="mb-2">
                                <div class="font-medium">{{ $edu->institution }}</div>
                                <div class="text-sm text-gray-600">{{ $edu->degree }}{{ $edu->field_of_study ? ', ' . $edu->field_of_study : '' }}</div>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Certifications</h2>
                        <div class="space-y-1">
                            @foreach($cv->certifications as $cert)
                                <div class="text-sm">
                                    <span class="font-medium">{{ $cert->name }}</span>
                                    <span class="text-gray-500">- {{ $cert->issuing_organization }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-4">
                                <h3 class="font-semibold">{{ $project->name }}</h3>
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
                    </section>
                @endif
                @break

            @case('languages')
                @if($cv->languages->count() > 0)
                    <section>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Languages</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($cv->languages as $lang)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $lang->language }} <span class="text-gray-500">{{ ucfirst($lang->proficiency) }}</span></span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break
        @endswitch
    @endforeach
</div>

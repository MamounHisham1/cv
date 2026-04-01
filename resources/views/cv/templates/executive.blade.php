<div class="p-10 font-serif text-gray-900 bg-white">
    <!-- Executive Header -->
    <header class="text-center mb-10 border-b-2 border-gray-800 pb-6">
        <h1 class="text-4xl font-bold uppercase tracking-widest mb-3">
            {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
        </h1>
        @if($cv->personal_info['title'] ?? false)
            <p class="text-xl text-gray-600 italic mb-4">{{ $cv->personal_info['title'] }}</p>
        @endif
        <div class="flex justify-center gap-6 text-sm text-gray-600">
            @if($cv->personal_info['email'] ?? false)
                <span>{{ $cv->personal_info['email'] }}</span>
            @endif
            @if($cv->personal_info['phone'] ?? false)
                <span>{{ $cv->personal_info['phone'] }}</span>
            @endif
            @if($cv->personal_info['location'] ?? false)
                <span>{{ $cv->personal_info['location'] }}</span>
            @endif
        </div>
    </header>

    <!-- Executive Summary -->
    @if($cv->summary)
        <section class="mb-8">
            <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide">Executive Summary</h2>
            <p class="text-center text-gray-700 leading-relaxed max-w-3xl mx-auto">{{ $cv->summary }}</p>
        </section>
    @endif

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide">Core Competencies</h2>
                        <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm">
                            @foreach($cv->skills as $skill)
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-gray-800 rounded-full"></span>
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-lg font-bold uppercase text-center mb-6 tracking-wide border-b border-gray-300 pb-2">Professional Experience</h2>
                        @foreach($cv->experiences as $exp)
                            <div class="mb-6">
                                <div class="flex justify-between items-baseline mb-2">
                                    <h3 class="text-xl font-bold">{{ $exp->title }}</h3>
                                    <span class="text-sm text-gray-600">{{ $exp->duration }}</span>
                                </div>
                                <div class="text-gray-700 mb-2 font-medium">{{ $exp->company }}{{ $exp->location ? ' | ' . $exp->location : '' }}</div>
                                <p class="text-gray-700 mb-2">{{ $exp->description }}</p>
                                @if($exp->achievements)
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 ml-4">
                                        @foreach($exp->achievements as $achievement)
                                            <li>{{ $achievement }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('education')
                @if($cv->educations->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide border-b border-gray-300 pb-2">Education</h2>
                        <div class="space-y-3">
                            @foreach($cv->educations as $edu)
                                <div class="flex justify-between items-baseline">
                                    <div>
                                        <span class="font-bold">{{ $edu->institution }}</span>
                                        <span class="text-gray-600"> — {{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</span>
                                    </div>
                                    <span class="text-sm text-gray-600">
                                        {{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide border-b border-gray-300 pb-2">Certifications & Professional Development</h2>
                        <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm">
                            @foreach($cv->certifications as $cert)
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-gray-800 rounded-full"></span>
                                    {{ $cert->name }} ({{ $cert->issuing_organization }})
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section class="mb-8">
                        <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide border-b border-gray-300 pb-2">Key Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-4">
                                <h3 class="font-bold">{{ $project->name }}</h3>
                                @if($project->description)
                                    <p class="text-sm text-gray-700 mt-1">{{ $project->description }}</p>
                                @endif
                                @if($project->key_achievements && count($project->key_achievements) > 0)
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 ml-4">
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
                        <h2 class="text-lg font-bold uppercase text-center mb-4 tracking-wide border-b border-gray-300 pb-2">Languages</h2>
                        <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm">
                            @foreach($cv->languages as $lang)
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-gray-800 rounded-full"></span>
                                    {{ $lang->language }} ({{ ucfirst($lang->proficiency) }})
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break
        @endswitch
    @endforeach
</div>

<div class="p-8 font-serif text-gray-900">
    <!-- Header -->
    <header class="border-b-2 border-gray-800 pb-4 mb-6">
        <h1 class="text-3xl font-bold uppercase tracking-wide">
            {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
        </h1>
        @if($cv->summary)
            <p class="mt-2 text-gray-700 text-sm leading-relaxed">{{ $cv->summary }}</p>
        @endif
        <div class="mt-3 text-sm text-gray-600 flex flex-wrap gap-4">
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
        </div>
    </header>

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Professional Experience</h2>
                        @foreach($cv->experiences as $exp)
                            <div class="mb-4">
                                <div class="flex justify-between items-baseline">
                                    <h3 class="font-bold">{{ $exp->title }}</h3>
                                    <span class="text-sm text-gray-600">{{ $exp->duration }}</span>
                                </div>
                                <div class="text-gray-700">{{ $exp->company }}</div>
                                @if($exp->location)
                                    <div class="text-sm text-gray-600">{{ $exp->location }}</div>
                                @endif
                                <p class="mt-1 text-sm text-gray-700">{{ $exp->description }}</p>
                                @if($exp->achievements)
                                    <ul class="mt-1 text-sm list-disc list-inside text-gray-700">
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
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Education</h2>
                        @foreach($cv->educations as $edu)
                            <div class="mb-2">
                                <div class="flex justify-between">
                                    <span class="font-bold">{{ $edu->institution }}</span>
                                    <span class="text-sm text-gray-600">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</span>
                                </div>
                                <div class="text-gray-700">{{ $edu->degree }}{{ $edu->field_of_study ? ', ' . $edu->field_of_study : '' }}</div>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Skills</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($cv->skills as $skill)
                                <span class="text-sm">{{ $skill->name }}</span>@if(!$loop->last), @endif
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Certifications</h2>
                        @foreach($cv->certifications as $cert)
                            <div class="mb-1">
                                <span class="font-semibold">{{ $cert->name }}</span> - {{ $cert->issuing_organization }}
                                @if($cert->issue_date)
                                    <span class="text-sm text-gray-600">({{ $cert->issue_date->format('Y') }})</span>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-3">
                                <div class="font-bold">{{ $project->name }}</div>
                                @if($project->description)
                                    <p class="text-sm text-gray-700">{{ $project->description }}</p>
                                @endif
                                @if($project->key_achievements && count($project->key_achievements) > 0)
                                    <ul class="mt-1 text-sm list-disc list-inside text-gray-700">
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
                        <h2 class="text-lg font-bold uppercase border-b border-gray-400 mb-3">Languages</h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach($cv->languages as $lang)
                                <span class="text-sm"><strong>{{ $lang->language }}</strong> - {{ ucfirst($lang->proficiency) }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break
        @endswitch
    @endforeach
</div>

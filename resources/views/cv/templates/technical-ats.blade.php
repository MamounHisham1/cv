<div class="p-8 font-sans text-gray-900 max-w-[210mm]">
    <!-- Header - Simple format for ATS -->
    <header class="mb-6">
        <h1 class="text-2xl font-bold">
            {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
        </h1>
        <div class="text-sm mt-2">
            {{ $cv->personal_info['email'] ?? '' }} | {{ $cv->personal_info['phone'] ?? '' }} | {{ $cv->personal_info['location'] ?? '' }}
            @if($cv->personal_info['linkedin'] ?? false)
                | {{ $cv->personal_info['linkedin'] }}
            @endif
        </div>
    </header>

    <!-- Summary -->
    @if($cv->summary)
        <section class="mb-6">
            <h2 class="text-lg font-bold uppercase mb-2">Professional Summary</h2>
            <p class="text-sm">{{ $cv->summary }}</p>
        </section>
    @endif

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Technical Skills</h2>
                        <p class="text-sm">
                            <strong>Core Competencies:</strong>
                            {{ $cv->skills->pluck('name')->implode(', ') }}
                        </p>
                    </section>
                @endif
                @break

            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Work Experience</h2>
                        @foreach($cv->experiences as $exp)
                            <div class="mb-4">
                                <div class="flex justify-between">
                                    <span class="font-bold">{{ $exp->title }}</span>
                                    <span class="text-sm">{{ $exp->start_date?->format('m/Y') }} - {{ $exp->is_current ? 'Present' : $exp->end_date?->format('m/Y') }}</span>
                                </div>
                                <div class="text-sm">{{ $exp->company }}{{ $exp->location ? ' | ' . $exp->location : '' }}</div>
                                <p class="text-sm mt-1">{{ $exp->description }}</p>
                                @if($exp->technologies)
                                    <p class="text-sm mt-1"><strong>Technologies:</strong> {{ implode(', ', $exp->technologies) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Certifications</h2>
                        @foreach($cv->certifications as $cert)
                            <div class="text-sm">
                                {{ $cert->name }} - {{ $cert->issuing_organization }}
                                @if($cert->issue_date)
                                    ({{ $cert->issue_date->format('Y') }})
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('education')
                @if($cv->educations->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Education</h2>
                        @foreach($cv->educations as $edu)
                            <div class="text-sm">
                                <strong>{{ $edu->degree }}</strong>{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }} - {{ $edu->institution }}
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-3">
                                <div class="font-bold text-sm">{{ $project->name }}</div>
                                @if($project->description)
                                    <p class="text-sm">{{ $project->description }}</p>
                                @endif
                                @if($project->key_achievements && count($project->key_achievements) > 0)
                                    <ul class="text-sm list-disc list-inside">
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
                    <section class="mb-6">
                        <h2 class="text-lg font-bold uppercase mb-2">Languages</h2>
                        <p class="text-sm">
                            {{ $cv->languages->map(fn ($lang) => $lang->language . ' (' . ucfirst($lang->proficiency) . ')')->implode(' | ') }}
                        </p>
                    </section>
                @endif
                @break
        @endswitch
    @endforeach
</div>

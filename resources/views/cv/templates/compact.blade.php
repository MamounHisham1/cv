<div class="p-8 font-serif text-gray-900 bg-white" style="font-size: 10px; line-height: 1.4;">
    <!-- Compact Header -->
    <header class="mb-3 pb-2 border-b border-gray-800">
        <div class="flex justify-between items-baseline">
            <h1 class="font-bold uppercase tracking-wide" style="font-size: 20px;">
                {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
            </h1>
            <div class="text-right" style="font-size: 9px;">
                @if($cv->personal_info['email'] ?? false)
                    <span>{{ $cv->personal_info['email'] }}</span><br>
                @endif
                @if($cv->personal_info['phone'] ?? false)
                    <span>{{ $cv->personal_info['phone'] }}</span>@if($cv->personal_info['location'] ?? false) · @endif
                @endif
                @if($cv->personal_info['location'] ?? false)
                    <span>{{ $cv->personal_info['location'] }}</span>
                @endif
                @if($cv->personal_info['linkedin'] ?? false || $cv->personal_info['github'] ?? false || $cv->personal_info['website'] ?? false)
                    <br>
                    @if($cv->personal_info['linkedin'] ?? false)
                        <span>LinkedIn</span>@if($cv->personal_info['github'] ?? false) · @endif
                    @endif
                    @if($cv->personal_info['github'] ?? false)
                        <span>GitHub</span>
                    @endif
                @endif
            </div>
        </div>
        @if($cv->personal_info['title'] ?? false)
            <p class="text-gray-600 italic mt-0.5" style="font-size: 11px;">{{ $cv->personal_info['title'] }}</p>
        @endif
    </header>

    <!-- Summary - Very tight -->
    @if($cv->summary)
        <section class="mb-3">
            <p class="text-gray-700 leading-snug">{{ $cv->summary }}</p>
        </section>
    @endif

    @foreach($cv->getSectionOrder() as $sectionKey)
        @if($sectionKey === 'personal') @continue @endif

        @switch($sectionKey)
            @case('skills')
                @if($cv->skills->count() > 0)
                    <section class="mb-3">
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5" style="font-size: 11px;">Skills</h2>
                        <div class="flex flex-wrap gap-x-1 gap-y-0.5">
                            @foreach($cv->skills as $skill)
                                @if(!$loop->first)<span class="text-gray-400">·</span>@endif
                                <span>{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('experience')
                @if($cv->experiences->count() > 0)
                    <section class="mb-3">
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5" style="font-size: 11px;">Professional Experience</h2>
                        @foreach($cv->experiences as $exp)
                            <div class="mb-2">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-bold" style="font-size: 10.5px;">
                                        {{ $exp->title }}
                                        <span class="font-normal text-gray-600"> | {{ $exp->company }}{{ $exp->location ? ', ' . $exp->location : '' }}</span>
                                    </span>
                                    <span class="text-gray-500 whitespace-nowrap" style="font-size: 9px;">
                                        {{ $exp->start_date?->format('m/Y') }} - {{ $exp->is_current ? 'Present' : $exp->end_date?->format('m/Y') }}
                                    </span>
                                </div>
                                @if($exp->description)
                                    <p class="text-gray-600 mt-0.5">{{ $exp->description }}</p>
                                @endif
                                @if($exp->achievements)
                                    <ul class="list-disc list-inside text-gray-600 mt-0.5 space-y-0">
                                        @foreach($exp->achievements as $achievement)
                                            <li>{{ $achievement }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if($exp->technologies)
                                    <div class="text-gray-400 mt-0.5">Tech: {{ implode(', ', $exp->technologies) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('education')
                @if($cv->educations->count() > 0)
                    <section class="mb-3">
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5" style="font-size: 11px;">Education</h2>
                        @foreach($cv->educations as $edu)
                            <div class="mb-1">
                                <span class="font-semibold">{{ $edu->institution }}</span> —
                                {{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}
                                <span class="text-gray-400">({{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }})</span>
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('certifications')
                @if($cv->certifications->count() > 0)
                    <section class="mb-3">
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5" style="font-size: 11px;">Certifications</h2>
                        @foreach($cv->certifications as $cert)
                            <div class="mb-1">
                                <span class="font-semibold">{{ $cert->name }}</span> — {{ $cert->issuing_organization }}
                                {{ $cert->issue_date ? ' (' . $cert->issue_date->format('Y') . ')' : '' }}
                            </div>
                        @endforeach
                    </section>
                @endif
                @break

            @case('projects')
                @if($cv->projects->count() > 0)
                    <section class="mb-3">
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5" style="font-size: 11px;">Key Projects</h2>
                        @foreach($cv->projects as $project)
                            <div class="mb-1.5">
                                <span class="font-bold">{{ $project->name }}</span>
                                @if($project->description)
                                    — <span class="text-gray-600">{{ $project->description }}</span>
                                @endif
                                @if($project->key_achievements && count($project->key_achievements) > 0)
                                    <ul class="list-disc list-inside text-gray-600 mt-0.5">
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
                        <h2 class="font-bold uppercase tracking-wide border-b border-gray-300 pb-0.5 mb-1.5 inline" style="font-size: 11px;">Languages</h2>
                        <span class="ml-2">
                            {{ $cv->languages->map(fn($l) => $l->language . ' (' . ucfirst($l->proficiency) . ')')->implode(' · ') }}
                        </span>
                    </section>
                @endif
                @break
        @endswitch
    @endforeach
</div>

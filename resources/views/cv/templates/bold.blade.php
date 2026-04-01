<div class="font-sans text-gray-900 bg-white">
    <!-- Bold Header Band -->
    <header class="bg-indigo-700 text-white px-8 py-7" style="print-color-adjust: exact;">
        <h1 class="text-3xl font-bold mb-1">
            {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
        </h1>
        @if($cv->personal_info['title'] ?? false)
            <p class="text-indigo-200 text-lg mb-3">{{ $cv->personal_info['title'] }}</p>
        @endif
        <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-indigo-100">
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
            @if($cv->personal_info['website'] ?? false)
                <span>{{ $cv->personal_info['website'] }}</span>
            @endif
        </div>
    </header>

    <div class="px-8 py-6">
    <!-- Summary -->
    @if($cv->summary)
        <section class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-2">Profile</h2>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $cv->summary }}</p>
        </section>
    @endif

    <!-- Skills by Category -->
    @if($cv->skills->count() > 0)
        <section class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-3">Skills</h2>
            @php($grouped = $cv->skills->groupBy('category'))
            <div class="space-y-2">
                @foreach($grouped as $category => $skills)
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">{{ ucfirst($category) }}</span>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @foreach($skills as $skill)
                                <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-800 text-xs rounded-full border border-indigo-200">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Experience -->
    @if($cv->experiences->count() > 0)
        <section class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-3">Experience</h2>
            @foreach($cv->experiences as $exp)
                <div class="mb-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $exp->title }}</h3>
                            <div class="text-sm text-gray-600">{{ $exp->company }}{{ $exp->location ? ' · ' . $exp->location : '' }}</div>
                        </div>
                        <span class="text-xs text-indigo-600 font-medium whitespace-nowrap ml-2">
                            {{ $exp->start_date?->format('m/Y') }} - {{ $exp->is_current ? 'Present' : $exp->end_date?->format('m/Y') }}
                        </span>
                    </div>
                    @if($exp->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $exp->description }}</p>
                    @endif
                    @if($exp->achievements)
                        <ul class="mt-1 text-sm text-gray-600 list-disc list-inside space-y-0.5">
                            @foreach($exp->achievements as $achievement)
                                <li>{{ $achievement }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if($exp->technologies)
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($exp->technologies as $tech)
                                <span class="text-xs text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $tech }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    <!-- Education -->
    @if($cv->educations->count() > 0)
        <section class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-3">Education</h2>
            @foreach($cv->educations as $edu)
                <div class="mb-2 flex justify-between">
                    <div>
                        <span class="font-semibold">{{ $edu->institution }}</span>
                        <span class="text-gray-600"> — {{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</span>
                </div>
            @endforeach
        </section>
    @endif

    <!-- Bottom Row: Certifications & Languages -->
    @if($cv->certifications->count() > 0 || $cv->languages->count() > 0)
        <div class="grid grid-cols-2 gap-6 mb-6">
            @if($cv->certifications->count() > 0)
                <section>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-2">Certifications</h2>
                    @foreach($cv->certifications as $cert)
                        <div class="text-sm mb-1">
                            <span class="font-medium">{{ $cert->name }}</span>
                            <span class="text-gray-500"> — {{ $cert->issuing_organization }}</span>
                        </div>
                    @endforeach
                </section>
            @endif

            @if($cv->languages->count() > 0)
                <section>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-2">Languages</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($cv->languages as $lang)
                            <span class="text-sm">{{ $lang->language }} <span class="text-gray-400">({{ ucfirst($lang->proficiency) }})</span></span>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    @endif

    <!-- Projects -->
    @if($cv->projects->count() > 0)
        <section>
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-700 mb-3">Projects</h2>
            @foreach($cv->projects as $project)
                <div class="mb-3">
                    <h3 class="font-semibold">{{ $project->name }}</h3>
                    @if($project->description)
                        <p class="text-sm text-gray-600 mt-0.5">{{ $project->description }}</p>
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
    </div>
</div>

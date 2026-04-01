<div class="flex font-sans text-gray-900 bg-white">
    <!-- Warm Light Sidebar -->
    <aside class="w-1/3 bg-amber-50 p-6" style="print-color-adjust: exact;">
        <!-- Profile Circle -->
        <div class="w-20 h-20 bg-amber-200 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl font-bold text-amber-800">
            {{ substr($cv->personal_info['first_name'] ?? '', 0, 1) }}{{ substr($cv->personal_info['last_name'] ?? '', 0, 1) }}
        </div>

        <h1 class="text-xl font-bold text-center mb-1">
            {{ $cv->personal_info['first_name'] ?? '' }} {{ $cv->personal_info['last_name'] ?? '' }}
        </h1>
        @if($cv->personal_info['title'] ?? false)
            <p class="text-sm text-amber-700 text-center mb-4">{{ $cv->personal_info['title'] }}</p>
        @endif

        <!-- Contact -->
        <div class="mb-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-2">Contact</h3>
            <div class="space-y-1 text-sm text-gray-700">
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
                    <div>LinkedIn</div>
                @endif
                @if($cv->personal_info['github'] ?? false)
                    <div>GitHub</div>
                @endif
                @if($cv->personal_info['website'] ?? false)
                    <div>{{ $cv->personal_info['website'] }}</div>
                @endif
            </div>
        </div>

        <!-- Skills with Dots -->
        @if($cv->skills->count() > 0)
            <div class="mb-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-2">Skills</h3>
                <div class="space-y-2">
                    @foreach($cv->skills as $skill)
                        <div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-sm">{{ $skill->name }}</span>
                            </div>
                            <div class="flex gap-1 mt-0.5">
                                @php
                                    $filled = match($skill->level) {
                                        'beginner' => 1,
                                        'intermediate' => 2,
                                        'advanced' => 3,
                                        'expert' => 4,
                                        default => 3
                                    };
                                @endphp
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="w-2.5 h-2.5 rounded-full {{ $i <= $filled ? 'bg-amber-500' : 'bg-amber-200' }}" style="print-color-adjust: exact;"></div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Languages -->
        @if($cv->languages->count() > 0)
            <div class="mb-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-2">Languages</h3>
                <div class="space-y-1 text-sm">
                    @foreach($cv->languages as $lang)
                        <div class="flex justify-between">
                            <span>{{ $lang->language }}</span>
                            <span class="text-gray-500">{{ ucfirst($lang->proficiency) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Certifications -->
        @if($cv->certifications->count() > 0)
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-2">Certifications</h3>
                <div class="space-y-2 text-sm">
                    @foreach($cv->certifications as $cert)
                        <div>
                            <div class="font-medium">{{ $cert->name }}</div>
                            <div class="text-xs text-gray-500">{{ $cert->issuing_organization }}{{ $cert->issue_date ? ' (' . $cert->issue_date->format('Y') . ')' : '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </aside>

    <!-- Main Content -->
    <main class="w-2/3 p-8">
        <!-- Summary -->
        @if($cv->summary)
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-3 flex items-center gap-2">
                    <span class="w-6 h-0.5 bg-amber-500 rounded"></span> About
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $cv->summary }}</p>
            </section>
        @endif

        <!-- Experience -->
        @if($cv->experiences->count() > 0)
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4 flex items-center gap-2">
                    <span class="w-6 h-0.5 bg-amber-500 rounded"></span> Experience
                </h2>
                <div class="space-y-5">
                    @foreach($cv->experiences as $exp)
                        <div class="border-l-2 border-amber-200 pl-4">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-bold text-gray-900">{{ $exp->title }}</h3>
                                <span class="text-xs text-amber-600 font-medium">
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

        <!-- Education -->
        @if($cv->educations->count() > 0)
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-3 flex items-center gap-2">
                    <span class="w-6 h-0.5 bg-amber-500 rounded"></span> Education
                </h2>
                @foreach($cv->educations as $edu)
                    <div class="mb-2">
                        <div class="font-bold">{{ $edu->institution }}</div>
                        <div class="text-sm text-gray-600">{{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</div>
                        <div class="text-xs text-gray-400">{{ $edu->start_date?->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date?->format('Y') }}</div>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Projects -->
        @if($cv->projects->count() > 0)
            <section>
                <h2 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-3 flex items-center gap-2">
                    <span class="w-6 h-0.5 bg-amber-500 rounded"></span> Projects
                </h2>
                @foreach($cv->projects as $project)
                    <div class="mb-3 border-l-2 border-amber-200 pl-4">
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
    </main>
</div>

@php
    $glassCard = 'rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-xl';
    $primaryBtn = 'inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostBtn = 'inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
@endphp

<div class="mx-auto max-w-5xl space-y-6 p-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Interview History</h1>
            <p class="mt-1 text-sm text-zinc-400">View your past mock interviews and results.</p>
        </div>
        <a href="{{ route('ai.interview') }}" wire:navigate class="{{ $primaryBtn }}">
            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
            New Interview
        </a>
    </div>

    {{-- Filter bar --}}
    <div class="{{ $glassCard }}">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            {{-- Type tabs --}}
            <div class="flex flex-wrap gap-2">
                @foreach(['all' => 'All', 'behavioral' => 'Behavioral', 'technical' => 'Technical', 'mixed' => 'Mixed'] as $key => $label)
                    <button
                        wire:click="setFilter('{{ $key }}')"
                        class="rounded-full px-3 py-1.5 text-xs font-medium transition-colors
                            {{ $typeFilter === $key ? 'bg-emerald-600 text-white' : 'border border-white/10 text-zinc-400 hover:bg-white/5 hover:text-white' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Sort --}}
            <div class="flex flex-wrap items-center gap-2">
                <button
                    wire:click="setSortBy('newest')"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 transition-colors
                        {{ $sortBy === 'newest' ? 'bg-emerald-600 text-white' : 'hover:bg-white/10 hover:text-white' }}"
                >
                    Newest {{ $sortBy === 'newest' ? '↓' : '' }}
                </button>
                <button
                    wire:click="setSortBy('oldest')"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 transition-colors
                        {{ $sortBy === 'oldest' ? 'bg-emerald-600 text-white' : 'hover:bg-white/10 hover:text-white' }}"
                >
                    Oldest {{ $sortBy === 'oldest' ? '↑' : '' }}
                </button>
                <button
                    wire:click="setSortBy('highest-score')"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-zinc-300 transition-colors
                        {{ $sortBy === 'highest-score' ? 'bg-emerald-600 text-white' : 'hover:bg-white/10 hover:text-white' }}"
                >
                    Top Score
                </button>
            </div>
        </div>
    </div>

    {{-- Sessions list --}}
    @if($sessions->isEmpty())
        <div class="rounded-2xl border border-white/10 bg-white/5 p-12 text-center backdrop-blur-xl">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/10 bg-white/5">
                <svg class="h-8 w-8 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-white">No Interviews Yet</h3>
            <p class="mb-6 text-sm text-zinc-400">Complete your first mock interview to see your results here.</p>
            <a href="{{ route('ai.interview') }}" wire:navigate class="{{ $primaryBtn }}">
                <svg class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                Start Interview
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($sessions as $session)
                @php
                    $eval = $session->evaluation;
                    $score = $eval->overall_score ?? 0;
                    $grade = $eval->grade ?? '—';
                    $gradeColor = $score >= 80 ? 'text-emerald-400 border-emerald-500' : ($score >= 60 ? 'text-yellow-400 border-yellow-500' : 'text-red-400 border-red-500');
                    $scoreColor = $score >= 80 ? 'from-emerald-500 to-emerald-400' : ($score >= 60 ? 'from-yellow-500 to-yellow-400' : 'from-red-500 to-red-400');
                    $typeColor = $session->interview_type === 'behavioral' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : ($session->interview_type === 'technical' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20');
                @endphp
                <a href="{{ route('ai.interview', ['session' => $session->id]) }}" wire:navigate
                    class="group flex flex-col gap-3 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/5 p-4 transition-all duration-200 sm:flex-row sm:items-center sm:gap-4"
                >
                    {{-- Score circle --}}
                    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-full border-2 {{ $gradeColor }} bg-white/5">
                        <span class="text-lg font-bold {{ $gradeColor }}">{{ $grade }}</span>
                        <span class="text-[10px] text-zinc-400">{{ $score }}/100</span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-zinc-200">
                            {{ $session->cv->title ?? 'Untitled CV' }}
                        </p>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-zinc-500">
                            <span>{{ $session->completed_at->format('M j, Y') }}</span>
                            <span>·</span>
                            <span>{{ $session->durationFormatted() }}</span>
                            <span>·</span>
                            <span>{{ $session->total_questions }} questions</span>
                        </div>
                    </div>

                    {{-- Type badge --}}
                    <div class="shrink-0">
                        <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium {{ $typeColor }}">
                            {{ ucfirst($session->interview_type) }}
                        </span>
                    </div>

                    {{-- Score bar --}}
                    <div class="hidden w-32 sm:block">
                        <div class="flex items-center justify-between text-xs text-zinc-400 mb-1">
                            <span>Score</span>
                            <span>{{ $score }}/100</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $scoreColor }}" style="width: {{ $score }}%"></div>
                        </div>
                    </div>

                    {{-- View arrow --}}
                    <div class="hidden shrink-0 sm:flex">
                        <svg class="h-5 w-5 text-zinc-500 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center pt-4">
            {{ $sessions->links() }}
        </div>
    @endif

</div>

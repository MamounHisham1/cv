<div class="min-h-screen bg-zinc-950 text-zinc-300 py-12 px-4 sm:px-6 lg:px-8" x-data="aiInterviewer()">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white tracking-tight">AI Interviewer</h1>
            @if($state !== 'setup')
                <button wire:click="resetSession" class="text-sm text-zinc-400 hover:text-white transition">End & Start Over</button>
            @endif
        </div>

        @if (session()->has('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif
        @error('credits')
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6">
                {{ $message }}
            </div>
        @enderror
        @error('ai')
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6">
                {{ $message }}
            </div>
        @enderror

        <!-- SETUP STATE -->
        @if ($state === 'setup')
            <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sm:p-8 shadow-2xl">
                <p class="text-zinc-400 mb-8">Select a CV and optionally paste a job description to start a realistic, voice-based mock interview. This is a live voice call — just talk naturally and the AI will respond.</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Select CV</label>
                        <select wire:model="selectedCvId" class="w-full bg-zinc-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-emerald-500 focus:border-emerald-500">
                            @if($this->cvs->isEmpty())
                                <option value="">No CVs found. Please create one first.</option>
                            @else
                                @foreach($this->cvs as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->title }} ({{ $cv->updated_at->diffForHumans() }})</option>
                                @endforeach
                            @endif
                        </select>
                        @error('selectedCvId') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Interview Focus</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model="interviewType" value="behavioral" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-white">Behavioral</span>
                                        <span class="mt-1 flex items-center text-xs text-zinc-400">STAR method & cultural fit</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-emerald-500 {{ $interviewType === 'behavioral' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model="interviewType" value="technical" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-white">Technical</span>
                                        <span class="mt-1 flex items-center text-xs text-zinc-400">Hard skills & system design</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-emerald-500 {{ $interviewType === 'technical' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model="interviewType" value="mixed" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-white">Mixed</span>
                                        <span class="mt-1 flex items-center text-xs text-zinc-400">Comprehensive screening</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-emerald-500 {{ $interviewType === 'mixed' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </label>
                        </div>
                        @error('interviewType') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Job Description (Optional)</label>
                        <textarea wire:model="jobDescription" rows="4" class="w-full bg-zinc-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-emerald-500 focus:border-emerald-500 placeholder-zinc-600" placeholder="Paste the job description here to get role-specific questions..."></textarea>
                    </div>

                    <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                        <div class="text-sm text-zinc-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                Costs 3 credits
                            </span>
                        </div>
                        <button
                            wire:click="startInterview"
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-zinc-900 bg-emerald-500 hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 focus:ring-offset-zinc-950 transition-colors disabled:opacity-50"
                            {{ $this->cvs->isEmpty() ? 'disabled' : '' }}
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                            Start Voice Interview
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- ACTIVE INTERVIEW STATE -->
        @if ($state === 'active')
            <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl flex flex-col h-[70vh]">

                <!-- Header -->
                <div class="p-4 border-b border-white/5 flex items-center justify-between shrink-0 bg-zinc-900/80 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </div>
                        <span class="text-sm font-medium text-white">Live Interview</span>
                        <span class="text-xs text-zinc-500 ml-2" x-text="turnCount > 0 ? 'Questions: ' + turnCount : 'Connecting...'"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span x-show="isConnecting" class="text-xs text-yellow-400">Connecting...</span>
                        <span x-show="connectionError" class="text-xs text-red-400" x-text="connectionError"></span>
                        <button @click="endCall()" class="text-xs px-3 py-1.5 rounded-lg bg-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-700 transition">
                            End Interview
                        </button>
                    </div>
                </div>

                <!-- Transcript Area -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth" id="transcript-container" x-ref="transcript">
                    <div x-show="transcript.length === 0 && !isAiSpeaking && !isProcessing" class="h-full flex items-center justify-center text-zinc-500 italic">
                        Connecting...
                    </div>

                    <template x-for="(msg, idx) in transcript" :key="idx">
                        <div>
                            <template x-if="msg.role === 'interviewer'">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500/30">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <p class="text-emerald-400 text-xs font-semibold mb-1 uppercase tracking-wider">Interviewer</p>
                                        <div class="text-white text-lg leading-relaxed" x-text="msg.content"></div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="msg.role === 'candidate'">
                                <div class="flex items-start gap-4 flex-row-reverse">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center border border-zinc-700">
                                        <svg class="w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <div class="flex-1 pt-1 text-right">
                                        <p class="text-zinc-500 text-xs font-semibold mb-1 uppercase tracking-wider">You</p>
                                        <div class="text-zinc-300 text-lg leading-relaxed inline-block bg-zinc-800/50 rounded-2xl rounded-tr-sm px-5 py-3" x-text="msg.content"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Agent thinking indicator -->
                    <div x-show="isProcessing" class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500/30">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                        </div>
                        <div class="flex-1 pt-1">
                            <p class="text-emerald-400 text-xs font-semibold mb-1 uppercase tracking-wider">Interviewer</p>
                            <div class="text-zinc-500 italic">Thinking...</div>
                        </div>
                    </div>
                </div>

                <!-- Live Call Footer -->
                <div class="p-6 border-t border-white/5 bg-zinc-900/80 rounded-b-2xl shrink-0">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="text-sm font-medium flex items-center gap-2 h-6" :class="statusColor">
                            <span x-text="statusMessage"></span>
                            <div x-show="isAiSpeaking" class="flex items-center gap-1 h-4">
                                <div class="w-1 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 0ms; height: 100%"></div>
                                <div class="w-1 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 150ms; height: 60%"></div>
                                <div class="w-1 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 300ms; height: 80%"></div>
                                <div class="w-1 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 450ms; height: 40%"></div>
                            </div>
                        </div>

                        <div class="relative">
                            <div
                                class="w-20 h-20 rounded-full flex items-center justify-center transition-all duration-500"
                                :class="isAiSpeaking
                                    ? 'bg-zinc-800 border border-white/10'
                                    : 'bg-emerald-500 shadow-[0_0_40px_rgba(16,185,129,0.3)]'"
                            >
                                <svg x-show="!isAiSpeaking" class="w-8 h-8 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                                <svg x-show="isAiSpeaking" class="w-8 h-8 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414M7.05 4.05A9 9 0 003 12c0 2.485 1.005 4.735 2.636 6.364" /></svg>
                            </div>
                            <div x-show="isListening && !isAiSpeaking" class="absolute inset-0 rounded-full border-2 border-emerald-500 animate-ping opacity-40"></div>
                        </div>

                        <p class="text-xs text-zinc-500" x-text="statusMessage === 'Connecting...' ? 'Setting up voice connection...' : (isAiSpeaking ? 'Speaking...' : 'Listening — just talk naturally')"></p>
                    </div>
                </div>
            </div>
        @endif

        <!-- EVALUATING STATE -->
        @if ($state === 'evaluating')
            <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-16 text-center" wire:init="generateEvaluation">
                <div class="relative flex justify-center items-center h-24 w-24 mx-auto mb-8">
                    <svg class="animate-spin text-emerald-500 w-16 h-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Analyzing Your Performance</h2>
                <p class="text-zinc-400">The AI is reviewing the transcript to provide structured feedback and a final grade.</p>
            </div>
        @endif

        <!-- RESULTS STATE -->
        @if ($state === 'results' && $evaluation)
            <div class="space-y-6">
                <!-- Top Score Card -->
                <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8 shadow-2xl flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-shrink-0 flex flex-col items-center justify-center w-40 h-40 rounded-full border-4 {{ $evaluation['overall_score'] >= 80 ? 'border-emerald-500 text-emerald-400' : ($evaluation['overall_score'] >= 60 ? 'border-yellow-500 text-yellow-400' : 'border-red-500 text-red-400') }} bg-zinc-950">
                        <span class="text-5xl font-bold">{{ $evaluation['overall_score'] }}</span>
                        <span class="text-sm uppercase tracking-widest mt-1 text-zinc-500">Score</span>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/10 text-white mb-4">
                            Grade: {{ $evaluation['grade'] }}
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-3">Executive Summary</h2>
                        <p class="text-zinc-300 text-lg leading-relaxed">{{ $evaluation['summary'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Criteria Breakdown -->
                    <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-2xl">
                        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            Score Breakdown
                        </h3>
                        <div class="space-y-5">
                            @foreach([
                                'Communication Clarity' => 'communication_clarity',
                                'Technical Depth' => 'technical_depth',
                                'Confidence & Composure' => 'confidence_composure',
                                'STAR Method Usage' => 'star_method_usage',
                                'Relevance to Role' => 'relevance_to_role',
                                'Specificity & Examples' => 'specificity_examples'
                            ] as $label => $key)
                                @if(isset($evaluation['criteria'][$key]))
                                    <div>
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-sm font-medium text-zinc-300">{{ $label }}</span>
                                            <span class="text-sm font-bold text-white">{{ $evaluation['criteria'][$key]['score'] }}/10</span>
                                        </div>
                                        <div class="w-full bg-zinc-800 rounded-full h-2 mb-2">
                                            <div class="h-2 rounded-full {{ $evaluation['criteria'][$key]['score'] >= 8 ? 'bg-emerald-500' : ($evaluation['criteria'][$key]['score'] >= 6 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $evaluation['criteria'][$key]['score'] * 10 }}%"></div>
                                        </div>
                                        <p class="text-xs text-zinc-500">{{ $evaluation['criteria'][$key]['reason'] }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Strengths & Improvements -->
                    <div class="space-y-6">
                        <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-2xl border-l-4 border-l-emerald-500">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Top Strengths
                            </h3>
                            <ul class="space-y-3">
                                @foreach($evaluation['strengths'] as $strength)
                                    <li class="flex items-start gap-3 text-zinc-300 text-sm">
                                        <span class="text-emerald-500 mt-1">&#8226;</span>
                                        {{ $strength }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-2xl border-l-4 border-l-amber-500">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                Areas to Improve
                            </h3>
                            <ul class="space-y-3">
                                @foreach($evaluation['improvements'] as $improvement)
                                    <li class="flex items-start gap-3 text-zinc-300 text-sm">
                                        <span class="text-amber-500 mt-1">&#8226;</span>
                                        {{ $improvement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button wire:click="resetSession" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-zinc-900 bg-white hover:bg-zinc-200 focus:outline-none transition-colors">
                        Return to Setup
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

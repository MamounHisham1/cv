<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100" x-data="aiInterviewer()">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.15),_transparent_50%)]"></div>
    <div class="h-1 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700"></div>

    <div class="relative mx-auto max-w-4xl px-4 py-10 md:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">
                AI Powered
            </div>
            <h1 class="mb-3 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                AI <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">Interviewer</span>
            </h1>
            <p class="mx-auto max-w-lg text-base text-zinc-400">
                Practice mock interviews with a voice-based AI recruiter. Get real-time questions tailored to your CV and experience.
            </p>
            <div class="mt-4 flex items-center justify-center gap-3">
                <a href="{{ route('interview.history') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white">
                    <x-ui::icon name="clock" class="h-4 w-4" /> Interview History
                </a>
                @if($state !== 'setup')
                    <button @click="cleanup(); $wire.resetSession()" class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white">
                        <x-ui::icon name="arrow-path" class="h-4 w-4" /> End & Start Over
                    </button>
                @endif
            </div>
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
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Interviewer Voice</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($voices as $model => $voice)
                                <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                    <input type="radio" wire:model.live="selectedVoice" value="{{ $model }}" class="sr-only">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-white">{{ $voice['name'] }}</span>
                                            <span class="mt-1 flex items-center gap-1 text-xs text-zinc-400">
                                                <span>{{ $voice['accent'] }}</span>
                                                <span>·</span>
                                                <span>{{ $voice['gender'] }}</span>
                                            </span>
                                            <span class="mt-0.5 text-xs text-zinc-500">{{ $voice['tone'] }}</span>
                                        </span>
                                    </span>
                                    <svg class="h-5 w-5 text-emerald-500 {{ $selectedVoice === $model ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Interview Focus</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model.live="interviewType" value="behavioral" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-white">Behavioral</span>
                                        <span class="mt-1 flex items-center text-xs text-zinc-400">STAR method & cultural fit</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-emerald-500 {{ $interviewType === 'behavioral' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model.live="interviewType" value="technical" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-white">Technical</span>
                                        <span class="mt-1 flex items-center text-xs text-zinc-400">Hard skills & system design</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-emerald-500 {{ $interviewType === 'technical' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-white/10 bg-zinc-950 p-4 hover:bg-zinc-900 focus:outline-none">
                                <input type="radio" wire:model.live="interviewType" value="mixed" class="sr-only">
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
                            @if(!($this->interviewAccess['allowed'] ?? true))
                                <span class="text-amber-400">Free trial used</span>
                            @else
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    @if($this->interviewAccess['is_free_trial'] ?? true)
                                        Free trial — 5 minutes
                                    @else
                                        Costs {{ config('credits.minimum_charge.ai_interview', 3) }} credits
                                    @endif
                                </span>
                            @endif
                        </div>
                        <button
                            wire:click="startInterview"
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-zinc-900 bg-emerald-500 hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 focus:ring-offset-zinc-950 transition-colors disabled:opacity-50"
                            {{ ($this->cvs->isEmpty() || !($this->interviewAccess['allowed'] ?? true)) ? 'disabled' : '' }}
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                            Start Voice Interview
                        </button>
                    </div>

                    @if(!($this->interviewAccess['allowed'] ?? true))
                        <div class="mt-4 rounded-lg border border-amber-500/30 bg-amber-500/10 p-3 text-sm text-amber-300">
                            <p>{{ $this->interviewAccess['reason'] }}</p>
                            <a href="{{ route('home') }}#pricing" class="mt-1 inline-block font-medium text-amber-200 underline hover:text-amber-100">Upgrade your plan &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- ACTIVE INTERVIEW STATE — Podcast/Waveform UI -->
        @if ($state === 'active')
            <style>
                @keyframes waveform-speaking {
                    0%, 100% { height: 8px; margin-top: -4px; }
                    50% { height: 28px; margin-top: -14px; }
                }
                @keyframes waveform-listening {
                    0%, 100% { height: 4px; margin-top: -2px; }
                    50% { height: 10px; margin-top: -5px; }
                }
                .waveform-speaking {
                    animation: waveform-speaking 0.6s ease-in-out infinite;
                    animation-delay: var(--bar-delay, 0s);
                }
                .waveform-listening {
                    animation: waveform-listening 1.5s ease-in-out infinite;
                    animation-delay: var(--bar-delay, 0s);
                }
            </style>

            <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl flex flex-col h-[70vh]">

                <!-- Top Bar -->
                <div class="px-5 py-3 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </div>
                        <span class="text-sm font-medium text-white">Live Interview</span>
                        <span class="text-xs text-zinc-600">|</span>
                        <span class="text-xs font-mono text-zinc-400" x-text="formattedTime">0:00</span>
                        <span class="text-xs text-zinc-600">|</span>
                        <span class="text-xs text-zinc-500" x-text="turnCount > 0 ? turnCount + ' answered' : ''"></span>
                        @if($isFreeTrial)
                            <span class="text-xs text-zinc-600">|</span>
                            <span class="text-xs font-mono"
                                  :class="freeTrialRemaining <= 60 ? 'text-amber-400' : 'text-emerald-400'"
                                  x-show="!gracePeriodActive"
                                  x-text="formattedFreeTrialTime"></span>
                            <span x-show="gracePeriodActive" class="text-xs text-amber-400">Wrapping up...</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span x-show="isConnecting" class="text-xs text-yellow-400">Connecting...</span>
                        <span x-show="connectionError" class="text-xs text-red-400" x-text="connectionError"></span>
                        <button x-on:click="endCall()" x-bind:disabled="isConnecting" class="text-xs px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition border border-red-500/20">
                            End Interview
                        </button>
                    </div>
                </div>

                <!-- Center: Waveform Visualizer -->
                <div class="flex-1 flex flex-col items-center justify-center gap-8">

                    <!-- Circular Waveform -->
                    <div class="relative w-52 h-52 flex items-center justify-center">
                        <!-- Outer glow -->
                        <div class="absolute inset-0 rounded-full transition-all duration-700"
                             :class="isAiSpeaking ? 'bg-emerald-500/10 shadow-[0_0_80px_rgba(16,185,129,0.15)]' : (isListening ? 'bg-zinc-500/5' : 'bg-transparent')">
                        </div>

                        <!-- Waveform bars ring -->
                        <div class="absolute inset-0">
                            @foreach(range(0, 23) as $i)
                                <div class="absolute left-1/2 top-1/2 w-1.5 -translate-x-1/2"
                                     style="transform: rotate({{ $i * 15 }}deg); transform-origin: center 104px;">
                                    <div class="waveform-bar w-full rounded-full"
                                         style="--bar-delay: {{ $i * 0.07 }}s"
                                         :class="isAiSpeaking
                                             ? 'waveform-speaking bg-emerald-400'
                                             : (isListening
                                                 ? 'waveform-listening bg-zinc-600'
                                                 : 'bg-zinc-700 h-[3px] -mt-[2px]')">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Inner circle -->
                        <div class="relative w-28 h-28 rounded-full flex items-center justify-center transition-all duration-500"
                             :class="isAiSpeaking
                                 ? 'bg-emerald-500/20 border-2 border-emerald-500/40'
                                 : (isProcessing
                                     ? 'bg-yellow-500/10 border-2 border-yellow-500/20'
                                     : (isListening
                                         ? 'bg-zinc-800 border-2 border-zinc-700'
                                         : 'bg-zinc-900 border-2 border-zinc-800'))">

                            <!-- Listening icon -->
                            <svg x-show="isListening && !isAiSpeaking && !isProcessing" class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>

                            <!-- AI speaking icon -->
                            <svg x-show="isAiSpeaking" class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414M7.05 4.05A9 9 0 003 12c0 2.485 1.005 4.735 2.636 6.364" /></svg>

                            <!-- Thinking spinner -->
                            <svg x-show="isProcessing" class="w-10 h-10 text-yellow-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>

                            <!-- Connecting -->
                            <svg x-show="isConnecting" class="w-10 h-10 text-zinc-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.858 15.355-5.858 21.213 0" /></svg>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-sm font-medium transition-colors duration-300" :class="statusColor" x-text="statusMessage"></span>
                        <span class="text-xs text-zinc-600" x-text="isAiSpeaking ? 'AI is responding...' : (isProcessing ? 'Processing...' : (isListening ? 'Speak naturally — the AI is listening' : ''))"></span>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="px-5 py-4 flex items-center justify-center shrink-0">
                    <span x-show="isListening && !isAiSpeaking" class="text-xs text-zinc-600 flex items-center gap-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Mic active
                    </span>
                </div>
            </div>
        @endif

        <!-- EVALUATING STATE -->
        @if ($state === 'evaluating')
            @if($evalErrorMessage)
                <!-- Error state -->
                <div class="bg-zinc-900/50 backdrop-blur-xl border border-red-500/20 rounded-2xl shadow-2xl p-16 text-center">
                    <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-red-500/10">
                        <svg class="w-10 h-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Evaluation Failed</h2>
                    <p class="text-zinc-400 mb-8">{{ $evalErrorMessage }}</p>
                    <button wire:click="retryEvaluation" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-zinc-900 bg-emerald-500 hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 focus:ring-offset-zinc-950 transition-colors">
                        Retry Evaluation
                    </button>
                </div>
            @else
                <!-- Processing state with polling -->
                <div class="bg-zinc-900/50 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-16 text-center"
                     @if($shouldPoll)
                         wire:poll.5000ms.keep-alive="checkEvaluationStatus"
                     @endif>
                    <div class="relative flex justify-center items-center h-24 w-24 mx-auto mb-8">
                        <svg class="animate-spin text-emerald-500 w-16 h-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Analyzing Your Performance</h2>
                    <p class="text-zinc-400">The AI is reviewing the transcript to provide structured feedback and a final grade. You can navigate away and come back later.</p>
                </div>
            @endif
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
</div>

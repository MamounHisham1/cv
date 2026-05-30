document.addEventListener('alpine:init', () => {
    Alpine.data('aiInterviewer', () => ({
        dgApiKey: null,
        voiceSocket: null,
        mediaStream: null,
        audioContext: null,
        workletNode: null,
        playbackCtx: null,
        isListening: false,
        isAiSpeaking: false,
        isProcessing: false,
        isConnecting: false,
        connectionError: '',
        transcript: [],
        turnCount: 0,
        interviewConcluding: false,
        nextPlayTime: 0,
        activeSources: [],
        maxTurns: 12,
        elapsedSeconds: 0,
        timerInterval: null,

        get formattedTime() {
            const m = Math.floor(this.elapsedSeconds / 60);
            const s = this.elapsedSeconds % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },

        get statusMessage() {
            if (this.isConnecting) return 'Connecting...';
            if (this.connectionError) return 'Error';
            if (this.isAiSpeaking) return 'Speaking...';
            if (this.isProcessing) return 'Thinking...';
            if (this.isListening) return 'Listening';
            return 'Ready';
        },

        get statusColor() {
            if (this.connectionError) return 'text-red-400';
            if (this.isAiSpeaking) return 'text-emerald-400';
            if (this.isProcessing) return 'text-yellow-400';
            if (this.isListening) return 'text-emerald-400';
            return 'text-zinc-500';
        },

        async init() {
            console.log('[Interview] Alpine aiInterviewer initialized');

            const result = await this.$wire.getDeepgramKey();
            if (result.key) {
                this.dgApiKey = result.key;
            } else {
                console.error('Deepgram init failed:', result.error);
            }

            // Watch for state changes from Livewire
            this.$wire.$watch('state', (newState) => {
                if (newState === 'active') {
                    const systemPrompt = this.$wire.systemPrompt;
                    const interviewType = this.$wire.interviewType;
                    const greeting = this.$wire.greeting;
                    const jobDesc = this.$wire.jobDescription;
                    const voiceModel = this.$wire.selectedVoice;
                    console.log('Interview started, voice:', voiceModel);
                    this.connectVoiceAgent(systemPrompt, interviewType, greeting, voiceModel);
                }
            });
        },

        async connectVoiceAgent(systemPrompt, interviewType, greeting, voiceModel) {
            this.isConnecting = true;
            this.connectionError = '';

            try {
                this.mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.audioContext = new AudioContext({ sampleRate: 24000 });

                await this.audioContext.audioWorklet.addModule('/audio-worklet-processor.js');
                const source = this.audioContext.createMediaStreamSource(this.mediaStream);
                this.workletNode = new AudioWorkletNode(this.audioContext, 'pcm-processor');
                source.connect(this.workletNode);

                this.voiceSocket = new WebSocket(
                    'wss://agent.deepgram.com/v1/agent/converse',
                    ['token', this.dgApiKey]
                );
                this.voiceSocket.binaryType = 'arraybuffer';

                this.voiceSocket.onopen = () => {
                    this.voiceSocket.send(JSON.stringify({
                        type: 'Settings',
                        audio: {
                            input: { encoding: 'linear16', sample_rate: 24000 },
                            output: { encoding: 'linear16', sample_rate: 24000, container: 'none' }
                        },
                        agent: {
                            listen: {
                                provider: { type: 'deepgram', model: 'nova-3', language: 'en', smart_format: true }
                            },
                            think: {
                                provider: { type: 'google', model: 'gemini-3.5-flash' },
                                prompt: systemPrompt
                            },
                            speak: {
                                provider: { type: 'deepgram', model: voiceModel || 'aura-2-orion-en' }
                            },
                            greeting: greeting || "Hello! I'm your AI interviewer today. Let's get started."
                        }
                    }));

                    this.isConnecting = false;
                    this.isListening = true;
                    this.elapsedSeconds = 0;
                    this.timerInterval = setInterval(() => this.elapsedSeconds++, 1000);
                };

                this.voiceSocket.onmessage = (event) => {
                    if (event.data instanceof ArrayBuffer) {
                        this.handleAudioChunk(event.data);
                    } else {
                        try {
                            const msg = JSON.parse(event.data);
                            this.handleAgentMessage(msg);
                        } catch (e) {}
                    }
                };

                this.voiceSocket.onerror = () => {
                    this.isConnecting = false;
                    this.connectionError = 'Connection error';
                };

                this.voiceSocket.onclose = () => {
                    this.isListening = false;
                    this.isConnecting = false;
                };

                this.workletNode.port.onmessage = (event) => {
                    if (this.isListening && this.voiceSocket && this.voiceSocket.readyState === WebSocket.OPEN) {
                        this.voiceSocket.send(event.data);
                    }
                };

            } catch (e) {
                console.error('Voice Agent setup failed:', e);
                this.isConnecting = false;
                this.connectionError = e.message || 'Failed to start';
            }
        },

        handleAgentMessage(msg) {
            switch (msg.type) {
                case 'Welcome':
                    console.log('Voice Agent connected:', msg.request_id);
                    break;
                case 'SettingsApplied':
                    console.log('Settings applied');
                    break;
                case 'ConversationText':
                    this.handleConversationText(msg);
                    break;
                case 'AgentThinking':
                    this.isProcessing = true;
                    break;
                case 'UserStartedSpeaking':
                    this.isProcessing = false;
                    this.stopPlayback();
                    break;
                case 'AgentStartedSpeaking':
                    this.isProcessing = false;
                    this.isAiSpeaking = true;
                    break;
                case 'AgentAudioDone':
                    if (this.interviewConcluding) {
                        // Wait for all streamed audio to finish playing before ending
                        const remainingMs = this.playbackCtx
                            ? Math.max(0, (this.nextPlayTime - this.playbackCtx.currentTime) * 1000)
                            : 0;
                        setTimeout(() => {
                            this.isAiSpeaking = false;
                            this.nextPlayTime = 0;
                            this.finalizeInterview();
                        }, remainingMs + 300);
                    } else {
                        this.isAiSpeaking = false;
                        this.nextPlayTime = 0;
                    }
                    break;
                case 'Error':
                    console.error('Voice Agent error:', msg.code, msg.description);
                    this.connectionError = msg.description || 'Agent error';
                    break;
            }
        },

        handleConversationText(msg) {
            const role = msg.role === 'user' ? 'candidate' : 'interviewer';
            const content = msg.content || '';

            if (!content.trim()) return;

            console.log('[Interview] Text:', role, '|', content);
            this.transcript.push({ role, content });

            // Build full accumulated text for this role to detect phrases split across chunks
            const fullText = this.transcript
                .filter(t => t.role === role)
                .map(t => t.content)
                .join(' ')
                .toLowerCase();

            if (role === 'candidate') {
                this.turnCount++;
            }

            // Check concluding phrases BEFORE calling Livewire (Livewire re-render kills this scope)
            if (role === 'interviewer' && !this.interviewConcluding) {
                if (fullText.includes('this concludes our interview') || fullText.includes('concludes our interview') || fullText.includes('end of the interview') || fullText.includes('wrap up') || fullText.includes('thank you for your time') || fullText.includes('that completes')) {
                    console.log('[Interview] Concluding phrase detected in full transcript');
                    this.interviewConcluding = true;
                    setTimeout(() => {
                        if (this.interviewConcluding) {
                            console.log('[Interview] Safety timeout — forcing end');
                            this.finalizeInterview();
                        }
                    }, 8000);
                }
            }

            if (role === 'candidate') {
                if (fullText.includes('end the interview') || fullText.includes('stop the interview') || fullText.includes('that\'s all') || fullText.includes('i\'m done') || fullText.includes('let\'s end') || fullText.includes('finish the interview') || fullText.includes('we can stop') || fullText.includes('that\'s it')) {
                    console.log('[Interview] Candidate end phrase detected:', content);
                    this.interviewConcluding = true;
                    setTimeout(() => this.finalizeInterview(), 2000);
                }
            }

            this.scrollTranscript();
            // Messages are saved in bulk when endInterview() is called, not per-message,
            // because Livewire re-renders destroy the Alpine scope mid-interview.
        },

        handleAudioChunk(data) {
            const pcm = new Int16Array(data);
            const float32 = new Float32Array(pcm.length);
            for (let i = 0; i < pcm.length; i++) {
                float32[i] = pcm[i] / 32768;
            }

            if (!this.playbackCtx || this.playbackCtx.state === 'closed') {
                this.playbackCtx = new AudioContext({ sampleRate: 24000 });
                this.nextPlayTime = 0;
            }

            const audioBuffer = this.playbackCtx.createBuffer(1, float32.length, 24000);
            audioBuffer.getChannelData(0).set(float32);

            const source = this.playbackCtx.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(this.playbackCtx.destination);

            // Schedule playback: if the context time is ahead of our queue, append seamlessly
            const now = this.playbackCtx.currentTime;
            if (this.nextPlayTime < now) {
                this.nextPlayTime = now;
            }
            source.start(this.nextPlayTime);
            this.nextPlayTime += audioBuffer.duration;

            this.activeSources.push(source);
            source.onended = () => {
                const idx = this.activeSources.indexOf(source);
                if (idx > -1) this.activeSources.splice(idx, 1);
            };
        },

        stopPlayback() {
            for (const source of this.activeSources) {
                try { source.stop(); } catch (e) {}
            }
            this.activeSources = [];
            this.nextPlayTime = 0;
            this.isAiSpeaking = false;
        },

        async finalizeInterview() {
            console.log('[Interview] finalizeInterview triggered');
            this.interviewConcluding = false;
            this.isListening = false;
            this.isAiSpeaking = false;

            console.log('[Interview] Calling $wire.endInterview()...');
            try {
                this.$wire.endInterview(this.transcript);
                console.log('[Interview] $wire.endInterview() sent successfully');
            } catch (e) {
                console.error('[Interview] endInterview call failed:', e);
            }

            this.cleanup();
        },

        async endCall() {
            console.log('[Interview] endCall button clicked');
            this.stopPlayback();
            this.isListening = false;
            this.isAiSpeaking = false;
            this.isProcessing = false;
            this.isConnecting = false;
            this.interviewConcluding = false;

            console.log('[Interview] Calling $wire.endInterview()...');
            try {
                this.$wire.endInterview(this.transcript);
                console.log('[Interview] $wire.endInterview() sent successfully');
            } catch (e) {
                console.error('[Interview] endInterview call failed:', e);
            }

            this.cleanup();
        },

        cleanup() {
            console.log('[Interview] Cleanup started');
            if (this.timerInterval) { clearInterval(this.timerInterval); this.timerInterval = null; }

            if (this.voiceSocket) {
                this.voiceSocket.onclose = null;
                try { this.voiceSocket.close(); } catch (e) {}
                this.voiceSocket = null;
            }
            if (this.workletNode) { try { this.workletNode.disconnect(); } catch (e) {} this.workletNode = null; }
            if (this.audioContext) { try { this.audioContext.close(); } catch (e) {} this.audioContext = null; }
            if (this.mediaStream) { this.mediaStream.getTracks().forEach(t => t.stop()); this.mediaStream = null; }
            if (this.playbackCtx) { try { this.playbackCtx.close(); } catch (e) {} this.playbackCtx = null; }
            console.log('[Interview] Cleanup done');
        },
    }));
});

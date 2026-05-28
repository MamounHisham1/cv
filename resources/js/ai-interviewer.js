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
        maxTurns: 12,

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
                    const jobDesc = this.$wire.jobDescription;
                    console.log('Interview started, JD:', jobDesc ? jobDesc.substring(0, 100) + '...' : 'No JD provided');
                    this.connectVoiceAgent(systemPrompt, interviewType);
                }
            });
        },

        async connectVoiceAgent(systemPrompt, interviewType) {
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
                                provider: { type: 'deepgram', model: 'aura-2-orion-en' }
                            },
                            greeting: "Hello! I'm your AI interviewer today. Let's get started — could you tell me about yourself and your background?"
                        }
                    }));

                    this.isConnecting = false;
                    this.isListening = true;
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

            this.transcript.push({ role, content });
            this.scrollTranscript();
            this.$wire.saveMessage(role, content);

            if (role === 'candidate') {
                this.turnCount++;
            }

            if (role === 'interviewer' && content.toLowerCase().includes('this concludes our interview')) {
                this.interviewConcluding = true;
            }
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
        },

        async finalizeInterview() {
            if (!this.interviewConcluding) return;

            this.interviewConcluding = false;
            this.isListening = false;
            this.isAiSpeaking = false;
            this.disconnectVoiceAgent();

            await new Promise(r => setTimeout(r, 500));
            this.$wire.endInterview();
        },

        async endCall() {
            this.disconnectVoiceAgent();
            this.$wire.endInterview();
        },

        disconnectVoiceAgent() {
            this.isListening = false;

            if (this.voiceSocket) { try { this.voiceSocket.close(); } catch (e) {} this.voiceSocket = null; }
            if (this.workletNode) { try { this.workletNode.disconnect(); } catch (e) {} this.workletNode = null; }
            if (this.audioContext) { try { this.audioContext.close(); } catch (e) {} this.audioContext = null; }
            if (this.mediaStream) { this.mediaStream.getTracks().forEach(t => t.stop()); this.mediaStream = null; }
            if (this.playbackCtx) { try { this.playbackCtx.close(); } catch (e) {} this.playbackCtx = null; }
        },

        scrollTranscript() {
            this.$nextTick(() => {
                const el = this.$refs.transcript;
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
    }));
});

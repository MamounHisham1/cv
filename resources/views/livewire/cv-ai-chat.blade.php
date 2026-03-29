@php
    $quickPromptClasses = 'cursor-pointer border border-white/10 bg-white/5 text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
@endphp

<div class="flex h-full flex-col bg-transparent" x-data x-init="
    $wire.on('message-added', () => {
        setTimeout(() => {
            const el = document.getElementById('chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        }, 100);
    });
">
    <div class="border-b border-white/10 bg-white/5 p-4 backdrop-blur-sm">
        <div class="flex flex-wrap gap-2" x-bind:class="{ 'pointer-events-none opacity-50': $wire.isLoading }">
            <x-ui::badge wire:click="quickPrompt('improve_summary')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="sparkles" class="w-3 h-3 mr-1" />
                Improve
            </x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('keywords')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="key" class="w-3 h-3 mr-1" />
                Keywords
            </x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('ats_check')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="check-circle" class="w-3 h-3 mr-1" />
                ATS Check
            </x-ui::badge>
        </div>
    </div>

    <div class="flex-1 space-y-4 overflow-y-auto p-4" id="chat-messages">
        @forelse($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $message['role'] === 'user' ? 'message-bubble user border border-emerald-400/20 shadow-lg shadow-emerald-500/10' : 'message-bubble assistant shadow-xl shadow-black/15' }}">
                    @if($message['role'] === 'assistant')
                        <div class="mb-2 flex items-center gap-2 border-b border-white/10 pb-2">
                            <x-ui::icon name="sparkles" class="w-4 h-4 text-emerald-500" />
                            <span class="text-xs font-medium text-emerald-300">AI Assistant</span>
                        </div>
                    @endif
                    <div class="text-sm whitespace-pre-wrap leading-relaxed prose prose-sm dark:prose-invert max-w-none">{!! Illuminate\Support\Str::markdown($message['content']) !!}</div>
                    <div class="mt-2 border-t border-white/10 pt-2 text-xs {{ $message['role'] === 'user' ? 'text-emerald-100' : 'text-zinc-500' }}">
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('g:i A') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                        <x-ui::icon name="sparkles" class="w-8 h-8 text-emerald-300" />
                    </div>
                    <x-ui::heading size="md" class="mb-2 text-white">How can I help?</x-ui::heading>
                    <p class="mb-4 max-w-xs text-sm text-zinc-400">
                        Ask me anything about your CV, ATS optimization, or get suggestions to improve your content.
                    </p>
                </div>
            </div>
        @endforelse

        @if($isLoading)
            <div class="flex justify-start">
                <div class="message-bubble assistant">
                    <div class="flex items-center gap-2">
                        <x-ui::icon name="sparkles" class="w-4 h-4 text-emerald-500" />
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <form
        wire:submit="sendMessage"
        x-on:submit.prevent="
            if ($wire.isLoading) return;
            const input = $el.querySelector('textarea');
            const msg = input.value.trim();
            if (!msg) return;
            input.value = '';
            $wire.sendMessage();
            setTimeout(() => $wire.fetchAiResponse(msg), 150);
        "
        class="border-t border-white/10 bg-zinc-950/80 p-4 backdrop-blur-xl"
    >
        <div class="flex gap-2 items-end">
            <x-ui::textarea
                wire:model="userMessage"
                placeholder="Ask me anything about your CV..."
                rows="2"
                class="{{ $fieldClasses }} flex-1 resize-none"
                x-bind:disabled="$wire.isLoading"
                x-on:keydown.enter.prevent="
                    if (!$wire.isLoading) $el.closest('form').dispatchEvent(new Event('submit'));
                "
            />
            <x-ui::button
                type="submit"
                variant="primary"
                size="sm"
                x-bind:disabled="$wire.isLoading"
                class="{{ $primaryButtonClasses }}"
            >
                <x-ui::icon name="send" class="w-4 h-4" />
            </x-ui::button>
        </div>
        <div class="flex items-center justify-between mt-2">
            <x-ui::button variant="ghost" size="sm" wire:click="clearChat" icon="trash-2" class="{{ $ghostButtonClasses }} hover:text-red-200">
                Clear Chat
            </x-ui::button>
            <p class="text-xs text-zinc-500">
                Powered by AI
            </p>
        </div>
    </form>
</div>

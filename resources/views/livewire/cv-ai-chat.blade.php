<div class="flex flex-col h-full" x-data x-init="
    $wire.on('message-added', () => {
        setTimeout(() => {
            const el = document.getElementById('chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        }, 100);
    });
">
    <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
        <div class="flex flex-wrap gap-2" x-bind:class="{ 'pointer-events-none opacity-50': $wire.isLoading }">
            <x-ui::badge wire:click="quickPrompt('improve_summary')" variant="outline" class="cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200">
                <x-ui::icon name="sparkles" class="w-3 h-3 mr-1" />
                Improve
            </x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('keywords')" variant="outline" class="cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200">
                <x-ui::icon name="key" class="w-3 h-3 mr-1" />
                Keywords
            </x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('ats_check')" variant="outline" class="cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200">
                <x-ui::icon name="check-circle" class="w-3 h-3 mr-1" />
                ATS Check
            </x-ui::badge>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto space-y-4 p-4" id="chat-messages">
        @forelse($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $message['role'] === 'user' ? 'message-bubble user' : 'message-bubble assistant' }}">
                    @if($message['role'] === 'assistant')
                        <div class="flex items-center gap-2 mb-2 pb-2 border-b border-zinc-200 dark:border-zinc-700">
                            <x-ui::icon name="sparkles" class="w-4 h-4 text-emerald-500" />
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">AI Assistant</span>
                        </div>
                    @endif
                    <div class="text-sm whitespace-pre-wrap leading-relaxed prose prose-sm dark:prose-invert max-w-none">{!! Illuminate\Support\Str::markdown($message['content']) !!}</div>
                    <div class="text-xs mt-2 pt-2 border-t border-zinc-200/50 {{ $message['role'] === 'user' ? 'text-emerald-100' : 'text-zinc-500' }}">
                        {{ \Carbon\Carbon::parse($message['timestamp'])->format('g:i A') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/30 mx-auto mb-4 flex items-center justify-center">
                        <x-ui::icon name="sparkles" class="w-8 h-8 text-emerald-600" />
                    </div>
                    <x-ui::heading size="md" class="mb-2">How can I help?</x-ui::heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 max-w-xs">
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
        class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900"
    >
        <div class="flex gap-2 items-end">
            <x-ui::textarea
                wire:model="userMessage"
                placeholder="Ask me anything about your CV..."
                rows="2"
                class="resize-none flex-1"
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
            >
                <x-ui::icon name="send" class="w-4 h-4" />
            </x-ui::button>
        </div>
        <div class="flex items-center justify-between mt-2">
            <x-ui::button variant="ghost" size="sm" wire:click="clearChat" icon="trash-2" class="text-zinc-500 hover:text-red-600">
                Clear Chat
            </x-ui::button>
            <p class="text-xs text-zinc-500">
                Powered by AI
            </p>
        </div>
    </form>
</div>

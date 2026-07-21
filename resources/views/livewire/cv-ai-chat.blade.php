@php
    $quickPromptClasses = 'cursor-pointer border border-white/10 bg-white/5 text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-300 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
@endphp

<div class="flex h-full flex-col bg-transparent">
    <div class="border-b border-white/10 bg-white/5 p-3 backdrop-blur-sm">
        <div class="flex flex-wrap gap-2" x-bind:class="{ 'pointer-events-none opacity-50': $wire.isLoading }">
            <x-ui::badge wire:click="quickPrompt('improve_summary')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="sparkles" class="w-3 h-3 mr-1" />{{ __("Improve") }}</x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('keywords')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="key" class="w-3 h-3 mr-1" />{{ __("Keywords") }}</x-ui::badge>
            <x-ui::badge wire:click="quickPrompt('ats_check')" variant="outline" class="{{ $quickPromptClasses }}">
                <x-ui::icon name="check-circle" class="w-3 h-3 mr-1" />{{ __("ATS Check") }}</x-ui::badge>
        </div>
    </div>

    <div
        class="relative min-h-0 flex-1"
        x-data="{
            isNearBottom: true,
            showScrollBtn: false,
            _observer: null,

            init() {
                const c = this.$refs.chatMessages;
                if (!c) return;

                this._observer = new MutationObserver(() => {
                    this.$nextTick(() => {
                        if (this.isNearBottom) {
                            this.scrollToBottom(false);
                        }
                    });
                });
                this._observer.observe(c, { childList: true, subtree: true });
                this.$nextTick(() => this.scrollToBottom(false));
            },

            checkScroll() {
                const c = this.$refs.chatMessages;
                if (!c) return;
                this.isNearBottom = c.scrollHeight - c.scrollTop - c.clientHeight < 120;
                this.showScrollBtn = !this.isNearBottom;
            },

            scrollToBottom(smooth) {
                const c = this.$refs.chatMessages;
                if (!c) return;
                c.scrollTo({ top: c.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
                this.showScrollBtn = false;
            }
        }"
    >
        <div class="h-full space-y-2 overflow-y-auto overscroll-contain p-3" id="chat-messages" x-ref="chatMessages" x-on:scroll.throttle="checkScroll()">
            @forelse($messages as $index => $message)
                <div wire:key="msg-{{ $index }}-{{ substr(md5((string) ($message['timestamp'] ?? $index)), 0, 8) }}" class="flex min-w-0 {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="min-w-0 {{ $message['role'] === 'user' ? 'message-bubble user border border-emerald-400/20 shadow-lg shadow-emerald-500/10' : 'message-bubble assistant shadow-xl shadow-black/15' }}">
                        @if($message['role'] === 'assistant')
                            <div class="mb-1 flex items-center gap-1.5 border-b border-white/10 pb-1">
                                <x-ui::icon name="sparkles" class="w-3 h-3 text-emerald-500" />
                                <span class="text-[11px] font-medium text-emerald-300">{{ __("AI Assistant") }}</span>
                            </div>
                        @endif
                        <div class="text-sm leading-relaxed prose prose-sm dark:prose-invert max-w-none">{!! Illuminate\Support\Str::markdown(mb_convert_encoding((string) $message['content'], 'UTF-8', 'UTF-8')) !!}</div>
                        <div class="mt-1 border-t border-white/10 pt-1 text-[11px] {{ $message['role'] === 'user' ? 'text-emerald-100' : 'text-zinc-500' }}">
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
                        <x-ui::heading size="md" class="mb-2 text-white">{{ __("How can I help?") }}</x-ui::heading>
                        <p class="mb-4 max-w-xs text-sm text-zinc-400">{{ __("Ask me anything about your CV, ATS optimization, or get suggestions to improve your content.") }}</p>
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

        <button
            x-show="showScrollBtn"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            x-on:click="scrollToBottom(true)"
            class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 rounded-full bg-zinc-800/90 px-3 py-1.5 text-xs font-medium text-emerald-400 shadow-lg ring-1 ring-white/10 backdrop-blur-sm hover:bg-zinc-700/90 cursor-pointer transition-colors"
        >
            <x-ui::icon name="chevron-down" class="w-3 h-3" />{{ __("New messages") }}</button>
    </div>

    @if(!empty($pendingClarifications))
        @php
            $clarifyInputClasses = 'w-full rounded-md border border-emerald-400/20 bg-zinc-900/60 px-3 py-2 text-sm text-zinc-100 placeholder:text-zinc-600 shadow-inner shadow-black/20 backdrop-blur-sm focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none';
            $totalQuestions = count($pendingClarifications);
            // Clamp the index defensively in case state got out of sync.
            $currentIdx = min(max($currentClarificationIndex, 0), $totalQuestions - 1);
            $question = $pendingClarifications[$currentIdx];
            $isLast = $currentIdx === $totalQuestions - 1;
            $answeredCount = collect($clarificationAnswers)
                ->filter(fn ($a) => trim((string) $a) !== '')
                ->count();
        @endphp
        <div
            class="shrink-0 border-t border-emerald-400/20 bg-emerald-500/5 p-3 backdrop-blur-xl"
            x-data x-ref="clarificationsPanel"
        >
            <div class="mb-2 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300">
                        <x-ui::icon name="help" class="w-3.5 h-3.5" />
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-emerald-300">{{ __("Quick questions") }}</span>
                </div>
                <span class="text-[11px] text-zinc-500">
                    {{ $currentIdx + 1 }} / {{ $totalQuestions }}
                    @if($answeredCount > 0)
                        · {{ $answeredCount }} answered
                    @endif
                </span>
            </div>

            {{-- Progress bar --}}
            <div class="mb-3 h-1 w-full overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full bg-emerald-500 transition-all duration-300" style="width: {{ round(($currentIdx + 1) / $totalQuestions * 100) }}%"></div>
            </div>

            <form wire:submit="nextClarification" x-data x-ref="clarificationsForm">
                <div class="rounded-lg border border-white/10 bg-white/5 p-3">
                    <label for="clarify_{{ $question['id'] }}" class="block text-sm font-medium text-zinc-100">
                        {{ $question['question'] }}
                    </label>
                    @if(!empty($question['why']))
                        <p class="mt-0.5 text-[11px] text-zinc-500">
                            <span class="text-zinc-400">{{ __("Why:") }}</span> {{ $question['why'] }}
                        </p>
                    @endif
                    @if(!empty($question['example']))
                        <p class="mt-0.5 text-[11px] text-zinc-500">
                            <span class="text-zinc-400">{{ __("Example:") }}</span> {{ $question['example'] }}
                        </p>
                    @endif
                    <input
                        id="clarify_{{ $question['id'] }}"
                        type="text"
                        wire:model="clarificationAnswers.{{ $question['id'] }}"
                        placeholder="Type your answer…"
                        class="{{ $clarifyInputClasses }} mt-2"
                        x-init="$nextTick(() => $el.focus())"
                        x-on:keydown.enter.prevent="$el.closest('form').dispatchEvent(new Event('submit'))"
                    />
                </div>

                <div class="mt-3 flex items-center justify-between gap-2">
                    <button
                        type="button"
                        wire:click="previousClarification"
                        wire:loading.attr="disabled"
                        @if($currentIdx === 0) disabled @endif
                        class="flex items-center gap-1 text-xs text-zinc-400 transition-colors hover:text-zinc-200 disabled:opacity-30 disabled:hover:text-zinc-400"
                    >
                        <x-ui::icon name="arrow-left" class="w-3.5 h-3.5" />{{ __("Back") }}</button>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="skipClarifications"
                            wire:loading.attr="disabled"
                            class="text-xs text-zinc-500 transition-colors hover:text-zinc-300"
                        >{{ __("Skip all") }}</button>
                        <x-ui::button
                            type="submit"
                            variant="primary"
                            size="sm"
                            wire:loading.attr="disabled"
                            class="{{ $primaryButtonClasses }}"
                        >
                            @if($isLast)
                                <x-ui::icon name="check" class="w-4 h-4 mr-1" />
                                Finish
                            @else
                                Next
                                <x-ui::icon name="arrow-right" class="w-4 h-4 ml-1" />
                            @endif
                        </x-ui::button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(!empty($proposedChanges))
        @php
            $formatter = new \App\Support\CvFieldDiffFormatter();
            $pendingChangeCount = count($proposedChanges);
        @endphp
        <div
            class="shrink-0 border-t border-amber-400/20 bg-amber-500/5 p-3 backdrop-blur-xl"
            x-ref="proposedChangesPanel"
        >
            <div class="mb-3 flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500/20 text-amber-300">
                    <x-ui::icon name="pencil" class="w-3.5 h-3.5" />
                </span>
                <span class="text-xs font-semibold uppercase tracking-wide text-amber-300">
                    {{ $pendingChangeCount }} proposed change{{ $pendingChangeCount === 1 ? '' : 's' }} — review before applying
                </span>
            </div>

            <p class="mb-3 text-[11px] text-zinc-400">{{ __("Nothing has been saved to your CV yet. Uncheck any change you don't want, then apply.") }}</p>

            <div class="max-h-64 space-y-2 overflow-y-auto pr-1">
                @foreach($proposedChanges as $change)
                    @php
                        $rejected = in_array($change['id'], $rejectedChangeIds, true);
                        // Full class strings (Tailwind purges dynamic class names).
                        $badgeClasses = match($change['action']) {
                            'create' => 'bg-emerald-500/15 text-emerald-300',
                            'delete' => 'bg-red-500/15 text-red-300',
                            default => 'bg-amber-500/15 text-amber-300',
                        };
                        $rows = $formatter->rows($change);
                    @endphp
                    <label
                        class="block cursor-pointer rounded-lg border {{ $rejected ? 'border-white/5 opacity-50' : 'border-white/10' }} bg-white/5 p-3 transition-opacity hover:opacity-90"
                    >
                        <div class="flex items-start gap-2">
                            <input
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 shrink-0 rounded border-white/20 bg-zinc-900 text-amber-500 focus:ring-amber-500/30"
                                x-on:change="$wire.toggleChange('{{ $change['id'] }}')"
                                {{ $rejected ? '' : 'checked' }}
                            />
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                    <span class="inline-flex rounded {{ $badgeClasses }} px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide">
                                        {{ $change['action'] }}
                                    </span>
                                    <span class="text-sm font-medium text-zinc-100">{{ $change['label'] }}</span>
                                </div>
                                <p class="mt-0.5 text-[11px] text-zinc-500">{{ $change['summary'] }}</p>

                                @if(!empty($rows))
                                    <dl class="mt-2 space-y-1">
                                        @foreach($rows as $row)
                                            <div class="flex flex-col gap-0.5 text-xs sm:flex-row sm:items-center sm:gap-2">
                                                <dt class="w-28 shrink-0 text-zinc-500">{{ $row['field'] }}</dt>
                                                <dd class="min-w-0 flex-1 break-words">
                                                    @if($change['action'] === 'create' || $row['before'] === '' || $row['before'] === '—')
                                                        <span class="text-emerald-300">{{ $row['after'] }}</span>
                                                    @else
                                                        <span class="text-zinc-500 line-through">{{ $row['before'] }}</span>
                                                        <span class="mx-1 text-zinc-600">→</span>
                                                        <span class="text-amber-200">{{ $row['after'] }}</span>
                                                    @endif
                                                </dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-3 flex items-center justify-between gap-2">
                <button
                    type="button"
                    wire:click="rejectAllChanges"
                    wire:loading.attr="disabled"
                    class="text-xs text-zinc-500 transition-colors hover:text-red-300 disabled:opacity-50"
                >{{ __("Reject all") }}</button>
                <x-ui::button
                    type="button"
                    variant="primary"
                    size="sm"
                    wire:click="approveChanges"
                    wire:loading.attr="disabled"
                    class="{{ $primaryButtonClasses }}"
                >
                    <x-ui::icon name="check" class="w-4 h-4 mr-1" />{{ __("Apply changes") }}</x-ui::button>
            </div>
        </div>
    @endif

    @if(!empty($lastTurnVersionId) && empty($proposedChanges))
        <div class="shrink-0 border-t border-white/10 bg-white/5 p-2 backdrop-blur-xl">
            <button
                type="button"
                wire:click="undoLastTurn"
                wire:loading.attr="disabled"
                class="flex w-full items-center justify-center gap-1.5 text-xs text-zinc-400 transition-colors hover:text-amber-300 disabled:opacity-50"
            >
                <x-ui::icon name="arrow-path" class="w-3.5 h-3.5" />{{ __("Undo last AI changes") }}</button>
        </div>
    @endif

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
        x-show="!$wire.pendingClarifications && !$wire.proposedChanges"
        class="shrink-0 border-t border-white/10 bg-zinc-950/80 p-3 backdrop-blur-xl"
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
            <x-ui::button variant="ghost" size="sm" wire:click="clearChat" icon="trash-2" class="{{ $ghostButtonClasses }} hover:text-red-200">{{ __("Clear Chat") }}</x-ui::button>
            <p class="text-xs text-zinc-500">{{ __("Powered by AI") }}</p>
        </div>
    </form>
</div>

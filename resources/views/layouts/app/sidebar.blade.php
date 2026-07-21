<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="@dir" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        @if (request()->routeIs('cv.builder', 'cv.edit', 'cv.evaluator', 'cover-letters.index', 'job.match', 'ai.interview', 'interview.history', 'drafts', 'referrals', 'credits.history', 'upgrade', 'profile.edit', 'security.edit', 'evaluations.history'))
            <x-cv-builder-nav />
        @else
            <x-landing-navbar />
        @endif

        {{ $slot }}

        <livewire:push-subscription-manager />

        {{-- Toast notification container --}}
        <div
            x-data="{
                toasts: [],
                addToast(event) {
                    const { message, type } = event.detail;
                    const id = Date.now();
                    this.toasts.push({ id, message, type: type || 'success' });
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 3000);
                }
            }"
            @notify.window="addToast($event)"
            class="fixed bottom-4 right-4 z-[100] flex flex-col gap-3 pointer-events-none"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="pointer-events-auto flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-medium shadow-lg backdrop-blur-xl"
                    :class="{
                        'bg-emerald-500/90 text-white': toast.type === 'success',
                        'bg-red-500/90 text-white': toast.type === 'error',
                        'bg-amber-500/90 text-white': toast.type === 'warning',
                        'bg-zinc-600/90 text-white': !toast.type || toast.type === 'info',
                    }"
                >
                    <span x-text="toast.message"></span>
                </div>
            </template>
        </div>

        {{-- Global custom confirm modal (replaces native browser wire:confirm) --}}
        <div
            x-data="{
                open: false,
                title: '',
                message: '',
                confirmLabel: 'Confirm',
                cancelLabel: 'Cancel',
                danger: false,
                component: null,
                method: null,
                params: null,
                ask(e) {
                    const d = e.detail;
                    this.title = d.title || 'Are you sure?';
                    this.message = d.message || '';
                    this.confirmLabel = d.confirmLabel || 'Confirm';
                    this.cancelLabel = d.cancelLabel || 'Cancel';
                    this.danger = d.danger !== false;
                    this.component = d.component || null;
                    this.method = d.method || null;
                    this.params = d.params || null;
                    this.open = true;
                },
                confirm() {
                    if (this.component && this.method) {
                        Livewire.find(this.component).call(this.method, ...(this.params || []));
                    }
                    this.open = false;
                }
            }"
            @confirm-action.window="ask($event)"
            x-cloak
        >
            <script>
                // Global helper: any button can call window.confirmAction({ ... })
                // to open the modal. It auto-detects the enclosing Livewire
                // component so the confirmed action runs in the right scope.
                window.confirmAction = function (opts) {
                    const el = opts.source instanceof Element ? opts.source : (opts._source || null);
                    let component = opts.component || null;
                    if (!component && el) {
                        const node = el.closest('[wire\\:id]');
                        if (node) component = node.getAttribute('wire:id');
                    }
                    window.dispatchEvent(new CustomEvent('confirm-action', {
                        detail: {
                            title: opts.title,
                            message: opts.message,
                            confirmLabel: opts.confirmLabel,
                            cancelLabel: opts.cancelLabel,
                            danger: opts.danger,
                            component: component,
                            method: opts.method,
                            params: opts.params,
                        }
                    }));
                };
            </script>
            <div
                x-show="open"
                x-transition.opacity
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-[110] flex items-center justify-center bg-black/60 p-4"
                style="display: none;"
            >
                <div
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="w-full max-w-md rounded-2xl border border-white/10 bg-zinc-900 p-6 shadow-2xl"
                    @click.outside="open = false"
                >
                    <h3 class="mb-2 text-lg font-semibold text-white" x-text="title"></h3>
                    <p class="mb-6 text-sm text-zinc-400" x-text="message"></p>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="open = false"
                                class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm text-zinc-300 hover:bg-white/10"
                                x-text="cancelLabel"></button>
                        <button type="button" @click="confirm()"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-white"
                                :class="danger ? 'bg-red-600 hover:bg-red-500' : 'bg-emerald-600 hover:bg-emerald-500'"
                                x-text="confirmLabel"></button>
                    </div>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>

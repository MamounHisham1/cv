<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        @if (request()->routeIs('cv.builder', 'cv.edit', 'cv.evaluator', 'drafts', 'referrals', 'credits.history', 'profile.edit', 'security.edit', 'evaluations.history'))
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
    </body>
</html>

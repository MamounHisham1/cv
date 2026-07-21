{{-- Placeholder shown while the lazy CvAiChat component mounts on first open. --}}
<div class="flex h-full flex-col bg-transparent">
    {{-- Quick-prompt strip placeholder --}}
    <div class="flex gap-2 border-b border-white/10 bg-white/5 p-3 backdrop-blur-sm">
        <div class="h-7 w-24 animate-pulse rounded-full border border-white/10 bg-zinc-800/60"></div>
        <div class="h-7 w-24 animate-pulse rounded-full border border-white/10 bg-zinc-800/60"></div>
        <div class="h-7 w-24 animate-pulse rounded-full border border-white/10 bg-zinc-800/60"></div>
    </div>

    {{-- Messages area placeholder --}}
    <div class="min-h-0 flex-1 space-y-3 overflow-hidden p-3">
        <div class="flex justify-start">
            <div class="w-3/4 animate-pulse rounded-2xl border border-white/10 bg-zinc-900/60 p-3">
                <div class="mb-2 h-3 w-20 rounded bg-zinc-800/70"></div>
                <div class="space-y-1.5">
                    <div class="h-3 w-full rounded bg-zinc-800/60"></div>
                    <div class="h-3 w-5/6 rounded bg-zinc-800/60"></div>
                    <div class="h-3 w-2/3 rounded bg-zinc-800/60"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <div class="w-1/2 animate-pulse rounded-2xl border border-emerald-400/20 bg-emerald-500/10 p-3">
                <div class="space-y-1.5">
                    <div class="h-3 w-full rounded bg-emerald-900/40"></div>
                    <div class="h-3 w-3/4 rounded bg-emerald-900/40"></div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-zinc-500">
            <x-ui::icon name="sparkles" class="w-4 h-4 text-emerald-500" />
            <span>{{ __("Loading conversation…") }}</span>
        </div>
    </div>

    {{-- Input placeholder --}}
    <div class="shrink-0 border-t border-white/10 bg-zinc-950/80 p-3 backdrop-blur-xl">
        <div class="h-16 w-full animate-pulse rounded-lg border border-white/10 bg-zinc-900/60"></div>
    </div>
</div>

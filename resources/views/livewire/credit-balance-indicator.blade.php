<a href="{{ route('upgrade') }}" wire:navigate class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 text-xs font-semibold text-emerald-400 backdrop-blur-xl transition-all hover:bg-emerald-500/20 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-500/10">
    <x-credit-coin size="sm" />
    <span class="tabular-nums">{{ number_format($balance) }}</span>
</a>

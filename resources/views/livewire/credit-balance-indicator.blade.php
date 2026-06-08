<a href="{{ route('upgrade') }}" wire:navigate class="inline-flex items-center gap-1 transition-opacity hover:opacity-80">
    <x-credit-coin size="lg" class="text-zinc-400" />
    <span class="flex h-4 min-w-4 items-center justify-center rounded-full bg-emerald-500 px-1 text-[10px] font-bold text-white">
        {{ number_format($balance) }}
    </span>
</a>

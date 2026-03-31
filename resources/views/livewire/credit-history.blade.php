<div class="mx-auto max-w-3xl space-y-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Credit History</h1>
            <p class="mt-1 text-sm text-zinc-400">Track all your credit transactions.</p>
        </div>
        <a href="{{ route('referrals') }}" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-zinc-300 transition-colors hover:bg-white/10 hover:text-white">
            <x-ui::icon name="gift" class="h-4 w-4" />
            Earn Credits
        </a>
    </div>

    <div class="flex gap-2">
        @foreach (['all' => 'All', 'earned' => 'Earned', 'spent' => 'Spent'] as $key => $label)
            <button
                wire:click="setFilter('{{ $key }}')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition-colors
                    {{ $filter === $key ? 'bg-emerald-600 text-white' : 'border border-white/10 text-zinc-400 hover:bg-white/5 hover:text-white' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($this->transactions->isEmpty())
        <div class="rounded-xl border border-white/10 bg-white/5 p-12 text-center backdrop-blur-xl">
            <x-credit-coin size="lg" class="mx-auto mb-3 opacity-30" />
            <p class="text-sm text-zinc-500">No transactions yet.</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach ($this->transactions as $transaction)
                <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full {{ $transaction->amount > 0 ? 'bg-emerald-600/10' : 'bg-red-600/10' }}">
                            @if ($transaction->amount > 0)
                                <x-ui::icon name="arrow-down" class="h-4 w-4 text-emerald-400" />
                            @else
                                <x-ui::icon name="arrow-up" class="h-4 w-4 text-red-400" />
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-200">{{ str_replace('_', ' ', Str::title($transaction->type)) }}</p>
                            <p class="text-xs text-zinc-500">{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold tabular-nums {{ $transaction->amount > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center pt-4">
            {{ $this->transactions->links() }}
        </div>
    @endif
</div>

<div class="mx-auto max-w-3xl space-y-6 p-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Invite Friends, Earn Credits</h1>
        <p class="mt-1 text-sm text-zinc-400">Share your referral link and earn 10 credits for each friend who signs up.</p>
    </div>

    <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <h2 class="text-sm font-medium text-zinc-400">Your Referral Link</h2>
        <div class="mt-3 flex items-center gap-3">
            <div class="min-w-0 flex-1 rounded-lg border border-white/10 bg-zinc-900 px-4 py-3 font-mono text-sm text-zinc-200">
                {{ $this->referralLink }}
            </div>
            <button
                type="button"
                x-data
                x-on:click="
                    const link = '{{ $this->referralLink }}';
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(link);
                    } else {
                        const ta = document.createElement('textarea');
                        ta.value = link;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                    $wire.copyReferralLink();
                "
                class="shrink-0 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
            >
                <x-ui::icon name="copy" class="w-4 h-4" />
                Copy
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
            <div class="flex items-center gap-2 text-zinc-400">
                <x-credit-coin size="sm" />
                <span class="text-sm">Balance</span>
            </div>
            <p class="mt-2 text-2xl font-bold text-white">{{ number_format($this->currentBalance) }}</p>
        </div>

        <div class="rounded-xl border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
            <div class="flex items-center gap-2 text-zinc-400">
                <x-ui::icon name="users" class="h-4 w-4" />
                <span class="text-sm">Total Referrals</span>
            </div>
            <p class="mt-2 text-2xl font-bold text-white">{{ $this->totalReferrals }}</p>
        </div>

        <div class="rounded-xl border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
            <div class="flex items-center gap-2 text-zinc-400">
                <x-ui::icon name="gift" class="h-4 w-4" />
                <span class="text-sm">Credits Earned</span>
            </div>
            <p class="mt-2 text-2xl font-bold text-emerald-400">+{{ number_format($this->creditsEarnedFromReferrals) }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <h2 class="text-sm font-medium text-zinc-400">Recent Referrals</h2>

        @if ($this->recentReferrals->isEmpty())
            <p class="mt-4 text-sm text-zinc-500">No referrals yet. Share your link to start earning credits!</p>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($this->recentReferrals as $referral)
                    <div class="flex items-center justify-between rounded-lg border border-white/5 bg-zinc-900/50 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600/20 text-xs font-bold text-emerald-400">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($referral->referred->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-200">{{ $referral->referred->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-zinc-500">{{ $referral->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-600/10 px-2.5 py-1 text-xs font-medium text-emerald-400">
                            +10
                            <x-credit-coin size="xs" />
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

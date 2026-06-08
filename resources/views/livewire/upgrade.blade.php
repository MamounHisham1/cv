<div class="min-h-screen bg-zinc-950">
    <div class="mx-auto max-w-5xl px-6 py-12 lg:px-8">
        {{-- Header --}}
        <div class="mb-12 text-center">
            <h1 class="text-3xl font-bold text-white sm:text-4xl">Upgrade Your Plan</h1>
            <p class="mt-3 text-base text-zinc-400">Get more credits to build CVs, practice interviews, and unlock AI features.</p>
            @if ($currentPlan !== 'free')
                <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-1.5 text-sm font-medium text-emerald-300">
                    <div class="h-2 w-2 rounded-full bg-emerald-400"></div>
                    Current plan: {{ ucfirst($currentPlan) }}
                </div>
            @endif
        </div>

        {{-- Pending Payment Banner --}}
        @if ($pendingPaymentId)
            <div class="mb-10 rounded-xl border border-amber-500/20 bg-amber-500/5 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500/10">
                            <svg class="h-4 w-4 animate-spin text-amber-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-amber-200">Waiting for payment</p>
                            <p class="mt-0.5 text-xs text-zinc-400">
                                Send <span class="font-mono text-zinc-300">{{ $pendingPaymentNumber }}</span> via Vodafone Cash to confirm.
                                We'll detect it automatically.
                            </p>
                        </div>
                    </div>
                    <button wire:click="cancelPending" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-medium text-zinc-400 transition-colors hover:bg-white/5 hover:text-white">
                        Cancel
                    </button>
                </div>
                <div x-data="{ poll: null }"
                    x-init="poll = setInterval(() => $wire.checkPaymentStatus(), 5000)"
                    x-on:poll-stop.window="clearInterval(poll)"
                    wire:poll.5s="checkPaymentStatus">
                </div>
            </div>
        @endif

        {{-- Plans Section --}}
        <div class="mb-16">
            <h2 class="mb-6 text-lg font-semibold text-white">Plans</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($plans as $key => $plan)
                    @php
                        $isCurrent = $currentPlan === $key;
                        $hasPending = $pendingPaymentId !== null;
                    @endphp
                    <div class="relative rounded-xl border {{ $key === 'enterprise' ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-white/10 bg-white/5' }} p-6 backdrop-blur-xl transition-colors hover:border-white/20">
                        <div class="mb-4 flex items-baseline justify-between">
                            <h3 class="text-lg font-semibold text-white">{{ $plan['name'] }}</h3>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-white">{{ number_format($plan['price_egp']) }}</span>
                                <span class="text-sm text-zinc-500">EGP/mo</span>
                            </div>
                        </div>
                        <p class="mb-5 text-sm text-zinc-400">{{ $plan['description'] }}</p>
                        <div class="mb-5 flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-white">{{ $plan['credits'] }}</span>
                            <span class="text-sm text-zinc-500">credits/month</span>
                        </div>
                        <button
                            wire:click="selectPlan('{{ $key }}')"
                            @disabled($isCurrent || $hasPending)
                            class="w-full rounded-lg py-2.5 text-sm font-semibold transition-colors
                                {{ $isCurrent
                                    ? 'cursor-default border border-white/10 text-zinc-500'
                                    : ($key === 'enterprise'
                                        ? 'bg-emerald-600 text-white hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed'
                                        : 'bg-white/10 text-white hover:bg-white/15 disabled:opacity-50 disabled:cursor-not-allowed')
                                }}"
                        >
                            @if ($isCurrent)
                                Current Plan
                            @else
                                Upgrade to {{ $plan['name'] }}
                            @endif
                        </button>
                        @if ($key === 'pro')
                            <div class="mt-3 text-center text-xs text-zinc-500">3 EGP per credit</div>
                        @elseif ($key === 'enterprise')
                            <div class="mt-3 text-center text-xs text-emerald-400">2 EGP per credit</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Credit Topups Section --}}
        <div>
            <h2 class="mb-2 text-lg font-semibold text-white">Credit Top-ups</h2>
            <p class="mb-6 text-sm text-zinc-500">One-time purchase. Credits are added instantly. No plan change required.</p>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($topups as $key => $topup)
                    @php $hasPending = $pendingPaymentId !== null; @endphp
                    <div class="relative rounded-xl border {{ $topup['badge'] === 'Best Value' ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-white/10 bg-white/5' }} p-6 backdrop-blur-xl transition-colors hover:border-white/20">
                        @if ($topup['badge'])
                            <div class="absolute -top-2.5 left-1/2 -translate-x-1/2">
                                <span class="inline-block rounded-full border px-3 py-0.5 text-xs font-medium
                                    {{ $topup['badge'] === 'Best Value'
                                        ? 'border-emerald-500/30 bg-emerald-600 text-white'
                                        : 'border-zinc-600 bg-zinc-700 text-zinc-300' }}">
                                    {{ $topup['badge'] }}
                                </span>
                            </div>
                        @endif
                        <div class="mb-1 text-sm font-medium text-zinc-400">{{ $topup['name'] }}</div>
                        <div class="mb-1 flex items-baseline gap-1.5">
                            <span class="text-3xl font-bold text-white">{{ $topup['credits'] }}</span>
                            <span class="text-sm text-zinc-500">credits</span>
                        </div>
                        <div class="mb-5 text-sm text-zinc-400">
                            {{ number_format($topup['price_egp']) }} EGP
                            <span class="text-zinc-600 mx-1">&middot;</span>
                            <span class="text-zinc-500">{{ number_format($topup['price_egp'] / $topup['credits'], 1) }} EGP/credit</span>
                        </div>
                        <button
                            wire:click="selectTopup('{{ $key }}')"
                            @disabled($hasPending)
                            class="w-full rounded-lg bg-white/10 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-white/15 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Buy Credits
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Phone Number Modal --}}
    @if ($showPhoneModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data x-init="$watch('$wire.showPhoneModal', v => { if(!v) return })">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative w-full max-w-md rounded-xl border border-white/10 bg-zinc-900 p-6 shadow-2xl"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <button wire:click="closeModal" class="absolute right-4 top-4 rounded-lg p-1 text-zinc-500 transition-colors hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <h3 class="mb-1 text-lg font-semibold text-white">Confirm Payment</h3>
                <p class="mb-5 text-sm text-zinc-400">Enter your Vodafone Cash phone number.</p>

                @if ($selectedPlan)
                    @php $item = $plans[$selectedPlan]; @endphp
                    <div class="mb-4 rounded-lg border border-white/10 bg-white/5 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-zinc-300">{{ $item['name'] }} Plan</span>
                            <span class="text-sm font-semibold text-white">{{ number_format($item['price_egp']) }} EGP</span>
                        </div>
                        <div class="mt-1 text-xs text-zinc-500">{{ $item['credits'] }} credits/month</div>
                    </div>
                @elseif ($selectedTopup)
                    @php $item = $topups[$selectedTopup]; @endphp
                    <div class="mb-4 rounded-lg border border-white/10 bg-white/5 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-zinc-300">{{ $item['name'] }} Pack</span>
                            <span class="text-sm font-semibold text-white">{{ number_format($item['price_egp']) }} EGP</span>
                        </div>
                        <div class="mt-1 text-xs text-zinc-500">{{ $item['credits'] }} credits</div>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-zinc-300">Vodafone Cash Number</label>
                    <input
                        type="tel"
                        id="phone"
                        wire:model="phone"
                        placeholder="01012345678"
                        maxlength="11"
                        class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder-zinc-600 outline-none transition-colors focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30"
                    />
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    @if ($error)
                        <p class="mt-1.5 text-xs text-red-400">{{ $error }}</p>
                    @endif
                </div>

                <button
                    wire:click="confirmPurchase"
                    wire:loading.attr="disabled"
                    class="w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-500 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="confirmPurchase">Send Payment Request</span>
                    <span wire:loading wire:target="confirmPurchase">Creating payment...</span>
                </button>

                <p class="mt-3 text-center text-xs text-zinc-500">
                    You'll receive an SMS from Vodafone Cash to confirm the transfer.
                </p>
            </div>
        </div>
    @endif
</div>

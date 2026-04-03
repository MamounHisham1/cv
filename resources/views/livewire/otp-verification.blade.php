@php
    $fieldClasses = 'h-12 w-12 rounded-lg border border-white/10 bg-zinc-900/50 text-center text-lg font-semibold text-zinc-100 shadow-inner shadow-black/10 backdrop-blur-sm transition-colors placeholder:text-zinc-500 focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0';
@endphp

<div class="flex flex-col gap-8 text-zinc-200">
    {{-- Header --}}
    <div class="text-center animate-slide-up">
        <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
            Email Verification
        </div>

        <h1 class="mb-2 text-3xl font-bold text-white">Verify Your Email</h1>
        <p class="text-sm text-zinc-400">
            We've sent a 6-digit code to<br>
            <span class="font-medium text-zinc-200">{{ $email }}</span>
        </p>
    </div>

    {{-- OTP Form --}}
    <form wire:submit="verify" class="flex flex-col gap-6 animate-slide-up delay-100">
        @csrf

        <div class="flex flex-col items-center gap-4">
            <div
                x-data="{
                    length: 6,
                    fields: ['', '', '', '', '', ''],
                    updateOtp() {
                        const otpValue = this.fields.join('');
                        Livewire.find(this.$root.closest('[wire\\:id]').getAttribute('wire:id')).set('otp', otpValue);
                    },
                    focus(index) {
                        const ref = this.$refs['input' + index];
                        if (ref) {
                            ref.focus();
                            ref.select();
                        }
                    },
                    onInput(index, event) {
                        const value = event.target.value.replace(/[^0-9]/g, '');
                        const newValue = value.slice(-1);
                        this.fields[index] = newValue;
                        this.updateOtp();
                        if (newValue && index < this.length - 1) {
                            this.$nextTick(() => this.focus(index + 1));
                        }
                    },
                    onKeydown(index, event) {
                        if (event.key === 'Backspace') {
                            if (!this.fields[index] && index > 0) {
                                event.preventDefault();
                                this.fields[index - 1] = '';
                                this.updateOtp();
                                this.$nextTick(() => this.focus(index - 1));
                            }
                        } else if (event.key === 'ArrowLeft' && index > 0) {
                            event.preventDefault();
                            this.focus(index - 1);
                        } else if (event.key === 'ArrowRight' && index < this.length - 1) {
                            event.preventDefault();
                            this.focus(index + 1);
                        }
                    },
                    onPaste(index, event) {
                        event.preventDefault();
                        const paste = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                        if (!paste) return;
                        for (let i = 0; i < paste.length && index + i < this.length; i++) {
                            this.fields[index + i] = paste[i];
                        }
                        this.updateOtp();
                        const lastFilledIndex = Math.min(index + paste.length - 1, this.length - 1);
                        this.$nextTick(() => this.focus(lastFilledIndex));
                    }
                }"
                class="flex items-center gap-2"
            >
                <input type="text" wire:model.live="otp" x-ref="otpInput" class="hidden" />
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        autocomplete="one-time-code"
                        x-model="fields[{{ $i }}]"
                        x-on:input="onInput({{ $i }}, $event)"
                        x-on:keydown="onKeydown({{ $i }}, $event)"
                        x-on:paste="onPaste({{ $i }}, $event)"
                        x-ref="input{{ $i }}"
                        class="{{ $fieldClasses }}"
                    />
                @endfor
            </div>

            @error('otp')
                <p class="text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <x-ui::button
            variant="primary"
            type="submit"
            class="w-full border border-white/10 bg-gradient-to-r from-emerald-500 to-emerald-600 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/40"
        >
            <span wire:loading.remove wire:target="verify">Verify & Continue</span>
            <span wire:loading wire:target="verify">Verifying...</span>
        </x-ui::button>
    </form>

    {{-- Resend --}}
    <div class="text-center animate-fade-in delay-200">
        <p class="text-sm text-zinc-400">
            Didn't receive the code?
        </p>

        @if($resendCooldown > 0)
            <p class="mt-1 text-sm text-zinc-500">
                Resend in {{ $resendCooldown }}s
            </p>
            <div wire:poll.1s="decrementCooldown"></div>
        @else
            <button
                wire:click="sendOtp"
                wire:loading.attr="disabled"
                class="mt-1 text-sm font-semibold text-emerald-300 hover:text-emerald-200 hover:underline disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="sendOtp">Resend Code</span>
                <span wire:loading wire:target="sendOtp">Sending...</span>
            </button>
        @endif
    </div>

    {{-- Back to login --}}
    <div class="text-center">
        <button
            wire:click="cancel"
            class="text-sm text-zinc-500 hover:text-zinc-300"
        >
            ← Back to login
        </button>
    </div>
</div>

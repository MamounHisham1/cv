<x-layouts::auth :title="__('Register')">
    @php
        $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
        $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
        $secondaryButtonClasses = 'w-full border border-white/10 bg-white/5 py-3 text-zinc-100 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
    @endphp

    <div class="flex flex-col gap-8 text-zinc-200">
        {{-- Header with Animation --}}
        <div class="text-center animate-slide-up">
            <div class="mb-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
                Design 4 Experience
            </div>

            <h1 class="mb-2 text-3xl font-bold text-white">Create Your Account</h1>
            <p class="text-sm text-zinc-400">Start building your AI-powered CV today</p>
        </div>

        <x-auth-session-status class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-center text-emerald-200" :status="session('status')" />

        {{-- Registration Form --}}
        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6 animate-slide-up delay-100">
            @csrf

            <div class="space-y-5">
                <x-ui::input
                    name="name"
                    :label="__('Full Name')"
                    :value="old('name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    icon="user"
                    :error="$errors->first('name')"
                    class="{{ $fieldClasses }} {{ $errors->has('name') ? $errorFieldClasses : '' }}"
                />

                <x-ui::input
                    name="email"
                    :label="__('Email Address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    icon="envelope"
                    :error="$errors->first('email')"
                    class="{{ $fieldClasses }} {{ $errors->has('email') ? $errorFieldClasses : '' }}"
                />

                <x-ui::input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    icon="lock-closed"
                    :error="$errors->first('password')"
                    class="{{ $fieldClasses }} {{ $errors->has('password') ? $errorFieldClasses : '' }}"
                />

                <x-ui::input
                    name="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    icon="lock-closed"
                    :error="$errors->first('password_confirmation')"
                    class="{{ $fieldClasses }} {{ $errors->has('password_confirmation') ? $errorFieldClasses : '' }}"
                />
            </div>

            {{-- Terms and Conditions --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="terms" required class="mt-0.5 h-4 w-4 rounded border-white/15 bg-white/5 text-emerald-500 accent-emerald-500 focus:ring-emerald-500/20">
                    <span class="text-sm text-zinc-400">
                        I agree to the <x-ui::link href="#" class="font-medium text-emerald-300 hover:text-emerald-200 hover:underline">Terms of Service</x-ui::link> and <x-ui::link href="#" class="font-medium text-emerald-300 hover:text-emerald-200 hover:underline">Privacy Policy</x-ui::link>
                    </span>
                </label>
            </div>

            <x-ui::button variant="primary" type="submit" class="w-full border border-white/10 bg-gradient-to-r from-emerald-500 to-emerald-600 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/40" data-test="register-user-button">
                {{ __('Create Account') }}
            </x-ui::button>
        </form>

        {{-- Divider --}}
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-zinc-950/80 px-4 text-zinc-500">Or sign up with</span>
            </div>
        </div>

        {{-- Social Registration Buttons --}}
        <div class="grid grid-cols-2 gap-4 animate-slide-up delay-200">
            <x-ui::button variant="outline" class="{{ $secondaryButtonClasses }}">
                <x-ui::icon name="globe" size="md" class="mr-2" />
                Google
            </x-ui::button>
            <x-ui::button variant="outline" class="{{ $secondaryButtonClasses }}">
                <x-ui::icon name="code-2" size="md" class="mr-2" />
                GitHub
            </x-ui::button>
        </div>

        <div class="animate-fade-in text-center text-sm text-zinc-400 delay-300">
            <span>{{ __('Already have an account?') }}</span>
            <x-ui::link :href="route('login')" wire:navigate class="ml-1 font-semibold text-emerald-300 hover:text-emerald-200 hover:underline">
                {{ __('Log in') }}
            </x-ui::link>
        </div>
    </div>
</x-layouts::auth>

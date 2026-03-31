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
        <div class="animate-slide-up delay-200">
            <x-ui::link :href="route('auth.google.redirect')" class="{{ $secondaryButtonClasses }} inline-flex items-center justify-center rounded-lg">
                <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign up with Google
            </x-ui::link>
        </div>

        <div class="animate-fade-in text-center text-sm text-zinc-400 delay-300">
            <span>{{ __('Already have an account?') }}</span>
            <x-ui::link :href="route('login')" wire:navigate class="ml-1 font-semibold text-emerald-300 hover:text-emerald-200 hover:underline">
                {{ __('Log in') }}
            </x-ui::link>
        </div>
    </div>
</x-layouts::auth>

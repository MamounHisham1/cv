<x-layouts::auth :title="__('Log in')">
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

            <h1 class="mb-2 text-3xl font-bold text-white">Welcome Back</h1>
            <p class="text-sm text-zinc-400">Sign in to continue building your professional CV</p>
        </div>

        <x-auth-session-status class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-center text-emerald-200" :status="session('status')" />

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6 animate-slide-up delay-100">
            @csrf

            <div class="space-y-5">
                <x-ui::input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    icon="envelope"
                    :error="$errors->first('email')"
                    class="{{ $fieldClasses }} {{ $errors->has('email') ? $errorFieldClasses : '' }}"
                />

                <div class="relative">
                    <x-ui::input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        icon="lock-closed"
                        :error="$errors->first('password')"
                        class="{{ $fieldClasses }} {{ $errors->has('password') ? $errorFieldClasses : '' }}"
                    />

                    @if (Route::has('password.request'))
                        <x-ui::link class="absolute end-0 top-0 text-sm font-medium text-emerald-300 hover:text-emerald-200 hover:underline" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot password?') }}
                        </x-ui::link>
                    @endif
                </div>
            </div>

            <div class="text-zinc-300">
                <x-ui::checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" class="border-white/15 bg-white/5 text-emerald-500 accent-emerald-500 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0" />
            </div>

            <x-ui::button variant="primary" type="submit" class="w-full border border-white/10 bg-gradient-to-r from-emerald-500 to-emerald-600 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/40" data-test="login-button">
                {{ __('Sign In') }}
            </x-ui::button>
        </form>

        {{-- Divider --}}
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-zinc-950/80 px-4 text-zinc-500">Or continue with</span>
            </div>
        </div>

        {{-- Social Login Buttons --}}
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

        @if (Route::has('register'))
            <div class="animate-fade-in text-center text-sm text-zinc-400 delay-300">
                <span>{{ __('Don\'t have an account?') }}</span>
                <x-ui::link :href="route('register')" wire:navigate class="ml-1 font-semibold text-emerald-300 hover:text-emerald-200 hover:underline">
                    {{ __('Sign up for free') }}
                </x-ui::link>
            </div>
        @endif
    </div>
</x-layouts::auth>

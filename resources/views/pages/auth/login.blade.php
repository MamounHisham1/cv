<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-8">
        {{-- Header with Animation --}}
        <div class="text-center animate-slide-up">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-xl shadow-emerald-500/30 mb-6 hover:scale-110 transition-transform duration-300">
                <x-ui::icon name="arrow-left-on-rectangle" size="xl" class="text-white" />
            </div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Welcome Back</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Sign in to continue building your professional CV</p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

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
                    placeholder="email@example.com"
                    icon="envelope"
                />

                <div class="relative">
                    <x-ui::input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                        icon="lock-closed"
                    />

                    @if (Route::has('password.request'))
                        <x-ui::link class="absolute top-0 text-sm end-0 font-medium text-emerald-600 dark:text-emerald-400 hover:underline" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot password?') }}
                        </x-ui::link>
                    @endif
                </div>
            </div>

            <x-ui::checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <x-ui::button variant="primary" type="submit" class="w-full py-3 text-base font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-300" data-test="login-button">
                {{ __('Sign In') }}
            </x-ui::button>
        </form>

        {{-- Divider --}}
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-700"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white dark:bg-zinc-950 px-4 text-zinc-500 dark:text-zinc-400">Or continue with</span>
            </div>
        </div>

        {{-- Social Login Buttons --}}
        <div class="grid grid-cols-2 gap-4 animate-slide-up delay-200">
            <x-ui::button variant="outline" class="w-full py-3 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-all duration-300">
                <x-ui::icon name="globe" size="md" class="mr-2" />
                Google
            </x-ui::button>
            <x-ui::button variant="outline" class="w-full py-3 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-all duration-300">
                <x-ui::icon name="code-2" size="md" class="mr-2" />
                GitHub
            </x-ui::button>
        </div>

        @if (Route::has('register'))
            <div class="text-center text-sm text-zinc-600 dark:text-zinc-400 animate-fade-in delay-300">
                <span>{{ __('Don\'t have an account?') }}</span>
                <x-ui::link :href="route('register')" wire:navigate class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline ml-1">
                    {{ __('Sign up for free') }}
                </x-ui::link>
            </div>
        @endif
    </div>
</x-layouts::auth>

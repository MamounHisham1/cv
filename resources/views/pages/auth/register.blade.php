<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-8">
        {{-- Header with Animation --}}
        <div class="text-center animate-slide-up">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-xl shadow-blue-500/30 mb-6 hover:scale-110 transition-transform duration-300">
                <x-ui::icon name="user-plus" size="xl" class="text-white" />
            </div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Your Account</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Start building your AI-powered CV today</p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

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
                    :placeholder="__('John Doe')"
                    icon="user"
                />

                <x-ui::input
                    name="email"
                    :label="__('Email Address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                    icon="envelope"
                />

                <x-ui::input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    viewable
                    icon="lock-closed"
                />

                <x-ui::input
                    name="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    viewable
                    icon="lock-closed"
                />
            </div>

            {{-- Terms and Conditions --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="terms" required class="w-4 h-4 mt-0.5 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">
                        I agree to the <x-ui::link href="#" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Terms of Service</x-ui::link> and <x-ui::link href="#" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Privacy Policy</x-ui::link>
                    </span>
                </label>
            </div>

            <x-ui::button variant="primary" type="submit" class="w-full py-3 text-base font-semibold bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300" data-test="register-user-button">
                {{ __('Create Account') }}
            </x-ui::button>
        </form>

        {{-- Divider --}}
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-700"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white dark:bg-zinc-950 px-4 text-zinc-500 dark:text-zinc-400">Or sign up with</span>
            </div>
        </div>

        {{-- Social Registration Buttons --}}
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

        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400 animate-fade-in delay-300">
            <span>{{ __('Already have an account?') }}</span>
            <x-ui::link :href="route('login')" wire:navigate class="font-semibold text-blue-600 dark:text-blue-400 hover:underline ml-1">
                {{ __('Log in') }}
            </x-ui::link>
        </div>
    </div>
</x-layouts::auth>

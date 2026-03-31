<div class="relative min-h-screen overflow-hidden bg-zinc-950 text-zinc-100">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.12),_transparent_50%)]"></div>

    <div class="relative flex items-start gap-6 p-6 md:p-10 max-md:flex-col">
        <div class="w-full shrink-0 pb-4 md:w-[220px]">
            <nav class="overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/80 p-2 shadow-2xl shadow-black/20 backdrop-blur-xl" aria-label="{{ __('Settings') }}">
                <div class="space-y-1">
                    <x-ui::navlist.item :href="route('profile.edit')" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-400 transition-all duration-200 hover:bg-white/5 hover:text-white">{{ __('Profile') }}</x-ui::navlist.item>
                    <x-ui::navlist.item :href="route('security.edit')" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-400 transition-all duration-200 hover:bg-white/5 hover:text-white">{{ __('Security') }}</x-ui::navlist.item>
                    <x-ui::navlist.item :href="route('appearance.edit')" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-400 transition-all duration-200 hover:bg-white/5 hover:text-white">{{ __('Appearance') }}</x-ui::navlist.item>
                </div>
            </nav>
        </div>

        <x-ui::separator class="md:hidden border-white/10" />

        <div class="flex-1 self-stretch max-md:pt-6">
            <x-ui::heading class="text-lg font-bold text-white">{{ $heading ?? '' }}</x-ui::heading>
            <x-ui::text size="lg" muted class="!text-zinc-400">{{ $subheading ?? '' }}</x-ui::text>

            <div class="mt-6 w-full max-w-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

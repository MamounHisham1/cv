<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Appearance settings')] class extends Component
{
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-ui::heading class="sr-only">{{ __('Appearance settings') }}</x-ui::heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
            <x-ui::radio-group x-data variant="segmented" x-model="$flux.appearance">
                <x-ui::radio value="light" icon="sun">{{ __('Light') }}</x-ui::radio>
                <x-ui::radio value="dark" icon="moon">{{ __('Dark') }}</x-ui::radio>
                <x-ui::radio value="system" icon="monitor">{{ __('System') }}</x-ui::radio>
            </x-ui::radio-group>
        </div>
    </x-pages::settings.layout>
</section>

<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="mt-10">
    <div class="overflow-hidden rounded-3xl border border-red-400/10 bg-zinc-950/80 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
        <div class="mb-5 flex items-start gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-red-400/20 bg-red-500/10">
                <x-ui::icon name="alert-triangle" class="h-5 w-5 text-red-400" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">{{ __('Delete account') }}</h3>
                <p class="mt-1 text-sm text-zinc-400">{{ __('Delete your account and all of its resources') }}</p>
            </div>
        </div>

        <button @click="$dispatch('open-dialog', 'confirm-user-deletion')" data-test="delete-user-button"
            class="inline-flex items-center gap-2 rounded-full border border-red-400/20 bg-gradient-to-r from-red-500 to-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-red-400 hover:to-red-500 hover:shadow-xl hover:shadow-red-500/30 disabled:pointer-events-none disabled:opacity-50">
            {{ __('Delete account') }}
        </button>

        <livewire:pages::settings.delete-user-modal />
    </div>
</section>

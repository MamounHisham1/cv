@if ($sent)
    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-6 text-center">
        <x-ui::icon name="check-circle" class="size-10 text-emerald-600 dark:text-emerald-400 mx-auto mb-3" variant="solid" />
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Message Sent</h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Thank you for reaching out. We will get back to you within 24 hours.</p>
        <x-ui::button variant="ghost" size="sm" wire:click="$set('sent', false)" class="mt-4">
            Send Another Message
        </x-ui::button>
    </div>
@else
    @php
        $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
        $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
    @endphp

    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <x-ui::input
                    wire:model="name"
                    type="text"
                    label="Name"
                    placeholder="Your full name"
                    :error="$errors->first('name')"
                    class="{{ $fieldClasses }} {{ $errors->has('name') ? $errorFieldClasses : '' }}"
                />
                @error('name')
                    <x-ui::error :message="$message" class="mt-2 text-red-300" />
                @enderror
            </div>

            <div>
                <x-ui::input
                    wire:model="email"
                    type="email"
                    label="Email"
                    placeholder="you@example.com"
                    :error="$errors->first('email')"
                    class="{{ $fieldClasses }} {{ $errors->has('email') ? $errorFieldClasses : '' }}"
                />
                @error('email')
                    <x-ui::error :message="$message" class="mt-2 text-red-300" />
                @enderror
            </div>
        </div>

        <div>
            <x-ui::input
                wire:model="subject"
                type="text"
                label="Subject"
                placeholder="How can we help?"
                :error="$errors->first('subject')"
                class="{{ $fieldClasses }} {{ $errors->has('subject') ? $errorFieldClasses : '' }}"
            />
            @error('subject')
                <x-ui::error :message="$message" class="mt-2 text-red-300" />
            @enderror
        </div>

        <div>
            <x-ui::textarea
                wire:model="message"
                label="Message"
                placeholder="Tell us more about your question or request..."
                rows="5"
                :error="$errors->first('message')"
                class="{{ $fieldClasses }} {{ $errors->has('message') ? $errorFieldClasses : '' }}"
            />
            @error('message')
                <x-ui::error :message="$message" class="mt-2 text-red-300" />
            @enderror
        </div>

        <x-ui::button type="submit" variant="primary" icon="send" class="w-full sm:w-auto" wire:loading.attr="disabled">
            Send Message
        </x-ui::button>
    </form>
@endif

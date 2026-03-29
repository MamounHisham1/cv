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
    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <x-ui::input wire:model="name" type="text" label="Name" placeholder="Your full name" />
                @error('name')
                    <x-ui::error :message="$message" />
                @enderror
            </div>

            <div>
                <x-ui::input wire:model="email" type="email" label="Email" placeholder="you@example.com" />
                @error('email')
                    <x-ui::error :message="$message" />
                @enderror
            </div>
        </div>

        <div>
            <x-ui::input wire:model="subject" type="text" label="Subject" placeholder="How can we help?" />
            @error('subject')
                <x-ui::error :message="$message" />
            @enderror
        </div>

        <div>
            <x-ui::textarea wire:model="message" label="Message" placeholder="Tell us more about your question or request..." rows="5" />
            @error('message')
                <x-ui::error :message="$message" />
            @enderror
        </div>

        <x-ui::button type="submit" variant="primary" icon="send" class="w-full sm:w-auto" wire:loading.attr="disabled">
            Send Message
        </x-ui::button>
    </form>
@endif

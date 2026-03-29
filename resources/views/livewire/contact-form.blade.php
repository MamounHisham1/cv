@if ($sent)
    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-6 text-center">
        <flux:icon name="check-circle" class="size-10 text-emerald-600 dark:text-emerald-400 mx-auto mb-3" variant="solid" />
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Message Sent</h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Thank you for reaching out. We will get back to you within 24 hours.</p>
        <flux:button variant="ghost" size="sm" wire:click="$set('sent', false)" class="mt-4">
            Send Another Message
        </flux:button>
    </div>
@else
    <form wire:submit="submit" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" placeholder="Your full name" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" placeholder="you@example.com" />
                <flux:error name="email" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Subject</flux:label>
            <flux:input wire:model="subject" type="text" placeholder="How can we help?" />
            <flux:error name="subject" />
        </flux:field>

        <flux:field>
            <flux:label>Message</flux:label>
            <flux:textarea wire:model="message" placeholder="Tell us more about your question or request..." rows="5" />
            <flux:error name="message" />
        </flux:field>

        <flux:button type="submit" variant="primary" icon="paper-airplane" class="w-full sm:w-auto" wire:loading.attr="disabled">
            Send Message
        </flux:button>
    </form>
@endif

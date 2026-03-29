@props([])

<div class="flex items-center justify-end border-t border-border px-4 py-3 md:hidden">
    <button
        type="button"
        @click="$dispatch('toggle-sidebar')"
        class="inline-flex items-center justify-center rounded-lg p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors"
    >
        <x-heroicon-c-bars-2 class="size-4" />
    </button>
</div>

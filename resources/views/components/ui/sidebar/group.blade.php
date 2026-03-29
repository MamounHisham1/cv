@props([
    'heading' => null,
])

<div class="px-3 py-2">
    @if($heading)
        <h3 class="mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
            {{ $heading }}
        </h3>
    @endif
    <div class="flex flex-col gap-1">
        {{ $slot }}
    </div>
</div>

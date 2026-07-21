@php
    $template = 'cv.templates.' . ($cv->template_id ?? 'professional-classic');
@endphp
<div class="flex h-full flex-col">
    <div class="flex items-center justify-between border-b border-white/10 px-4 py-2.5">
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-zinc-500">
            <x-ui::icon name="eye" class="h-4 w-4" />{{ __("Live preview") }}</div>
        <a href="{{ route('cv.preview', $cv) }}" target="_blank"
           class="text-xs text-zinc-400 hover:text-white">{{ __("Open in new tab →") }}</a>
    </div>

    {{-- Light, scrollable document surface. The CV templates are designed
         for a white background; this isolates them from the dark shell. --}}
    <div class="min-h-0 flex-1 overflow-auto bg-gray-200/40 p-3">
        <div class="mx-auto bg-white shadow-lg" style="width: 210mm; max-width: 100%;">
            @if(view()->exists($template))
                @include($template, ['cv' => $cv])
            @else
                @include('cv.templates.professional-classic', ['cv' => $cv])
            @endif
        </div>
    </div>
</div>

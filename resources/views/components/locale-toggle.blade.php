@php
    use App\Http\Middleware\SetAppLocale;

    $current = app()->getLocale();
    $locales = SetAppLocale::SUPPORTED_LOCALES;
    // Toggle target: jump to the other available locale.
    $target = $current === 'ar' ? 'en' : 'ar';
    $isRtl = ($locales[$current]['dir'] ?? 'ltr') === 'rtl';
@endphp

<a
    href="{{ route('locale.switch', $target) }}"
    class="inline-flex items-center gap-2 rounded-md px-2.5 py-1.5 text-sm font-medium text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-white/10 dark:hover:text-white {{ $attributes->get('class', '') }}"
    title="{{ $target === 'ar' ? 'العربية' : 'English' }}"
>
    <x-ui::icon name="languages" :size="$attributes->get('icon-size', 'md')" />
    <span>{{ $target === 'ar' ? 'العربية' : 'English' }}</span>
</a>

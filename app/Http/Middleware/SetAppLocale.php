<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Supported locales keyed by their ISO code.
     *
     * @var array<string, array{name: string, dir: string}>
     */
    public const SUPPORTED_LOCALES = [
        'en' => ['name' => 'English', 'dir' => 'ltr'],
        'ar' => ['name' => 'العربية', 'dir' => 'rtl'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);

        return $next($request);
    }

    /**
     * Resolve the active locale from (in order) session, user preference,
     * browser header, then fall back to the app default.
     */
    private function resolveLocale(Request $request): string
    {
        $default = config('app.locale', 'en');

        // 1. Explicit session value (set by the language toggle)
        if (Session::has('locale') && $this->isSupported((string) Session::get('locale'))) {
            return (string) Session::get('locale');
        }

        // 2. Persisted user preference
        $user = $request->user();
        if ($user && filled($user->locale) && $this->isSupported((string) $user->locale)) {
            return (string) $user->locale;
        }

        // 3. Browser Accept-Language hint
        $preferred = $request->getPreferredLanguage(array_keys(self::SUPPORTED_LOCALES));
        if ($preferred && $this->isSupported($preferred)) {
            return $preferred;
        }

        return $default;
    }

    private function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::SUPPORTED_LOCALES);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetAppLocale;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, SetAppLocale::SUPPORTED_LOCALES), 404);

        Session::put('locale', $locale);

        /** @var User|null $user */
        $user = $request->user();
        if ($user) {
            $user->forceFill(['locale' => $locale])->save();
        }

        return redirect()->back();
    }
}

<?php

use App\Http\Middleware\SetAppLocale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);

describe('supported locales', function () {
    it('declares english and arabic with their text directions', function () {
        expect(SetAppLocale::SUPPORTED_LOCALES)
            ->toHaveKey('en')
            ->toHaveKey('ar')
            ->and(SetAppLocale::SUPPORTED_LOCALES['en']['dir'])->toBe('ltr')
            ->and(SetAppLocale::SUPPORTED_LOCALES['ar']['dir'])->toBe('rtl');
    });
});

describe('locale switch route', function () {
    it('sets the session locale and redirects back', function () {
        $this->get(route('locale.switch', 'ar'))
            ->assertRedirect();

        expect(session('locale'))->toBe('ar');
    });

    it('aborts for unsupported locales', function () {
        $this->get(route('locale.switch', 'fr'))->assertNotFound();
    });

    it('persists the locale on the authenticated user', function () {
        $user = User::factory()->create(['locale' => null]);

        $this->actingAs($user)
            ->get(route('locale.switch', 'ar'))
            ->assertRedirect();

        expect($user->fresh()->locale)->toBe('ar');
    });
});

describe('locale middleware resolution', function () {
    it('falls back to the app default when nothing else is set', function () {
        $response = $this->withSession([])->get('/');

        // Landing page should still render in the default (English) locale.
        $response->assertOk();
        expect(app()->getLocale())->toBe(config('app.locale'));
    });

    it('honours an explicit session locale', function () {
        Session::flush();
        $this->withSession(['locale' => 'ar'])->get('/');

        expect(app()->getLocale())->toBe('ar');
    });
});

describe('translation files', function () {
    it('has the same key set across en.json and ar.json', function () {
        $en = json_decode(file_get_contents(base_path('lang/en.json')), true);
        $ar = json_decode(file_get_contents(base_path('lang/ar.json')), true);

        $missingFromAr = array_diff_key($en, $ar);
        $missingFromEn = array_diff_key($ar, $en);

        expect($missingFromAr)->toBeEmpty('Keys present in en.json but missing from ar.json')
            ->and($missingFromEn)->toBeEmpty('Keys present in ar.json but missing from en.json');
    });

    it('translates a representative key in arabic', function () {
        app()->setLocale('ar');
        expect(__('Save'))->toBe('حفظ')
            ->and(__('Search'))->toBe('بحث');

        app()->setLocale('en');
        expect(__('Save'))->toBe('Save');
    });
});

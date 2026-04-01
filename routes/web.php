<?php

use App\Http\Controllers\Auth\ImpersonateController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\CreditHistory;
use App\Livewire\CvBuilder;
use App\Livewire\CvEvaluator;
use App\Livewire\EvaluationHistory;
use App\Livewire\ReferralDashboard;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing.design4')->name('home');
Route::view('/cookie-policy', 'pages.cookie-policy')->name('cookie.policy');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy.policy');
Route::view('/terms-of-service', 'pages.terms-of-service')->name('terms.of-service');
Route::view('/faq', 'pages.faq')->name('faq');
Route::redirect('/dashboard', '/drafts');

// Override Fortify's registration store route to not log user in
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('register.store');

// Google OAuth routes
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/otp/verify', function () {
    // Only allow access if there's a pending registration in session
    if (! session()->has('pending_registration')) {
        return redirect()->route('login');
    }

    return view('auth.otp-verify');
})->name('otp.verify');

Route::middleware(['auth', 'impersonate'])->group(function () {
    Route::get('/impersonate/{user}', [ImpersonateController::class, 'start'])
        ->name('impersonate.start');
    Route::post('/impersonate/stop', [ImpersonateController::class, 'stop'])
        ->name('impersonate.stop');
});

Route::middleware(['auth', 'verified', 'otp.verified'])->group(function () {
    Route::get('/drafts', function () {
        $user = auth()->user();

        if (! $user->cvs()->exists()) {
            return redirect()->route('cv.builder', ['onboarding' => 1]);
        }

        $cvs = $user->cvs()
            ->with(['experiences', 'skills', 'certifications'])
            ->latest()
            ->get();

        return view('welcome-cvs', ['cvs' => $cvs]);
    })->name('drafts');

    Route::get('/builder', CvBuilder::class)->name('cv.builder');
    Route::get('/builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/evaluator', CvEvaluator::class)->name('cv.evaluator');
    Route::get('/evaluations/history', EvaluationHistory::class)->name('evaluations.history');
    Route::get('/referrals', ReferralDashboard::class)->name('referrals');
    Route::get('/credits', CreditHistory::class)->name('credits.history');
    Route::get('/preview/{cv}', function (Cv $cv) {
        if ($cv->user_id !== auth()->id()) {
            abort(403);
        }

        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects', 'languages']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');

    Route::get('/notifications/preferences', fn () => view('livewire.notification-preferences'))->name('notifications.preferences');
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    })->name('notifications.mark-all-read');
    Route::post('/push/subscribe', function (Request $request) {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        auth()->user()->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $request->input('endpoint')],
            [
                'content_encoding' => 'aesgcm',
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
            ]
        );

        return response()->json(['success' => true]);
    })->name('push.subscribe');
});

require __DIR__.'/settings.php';

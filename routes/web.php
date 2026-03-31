<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\CreditHistory;
use App\Livewire\CvBuilder;
use App\Livewire\CvEvaluator;
use App\Livewire\ReferralDashboard;
use App\Models\Cv;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing.design4')->name('home');
Route::redirect('/dashboard', '/builder');

// Google OAuth routes
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/otp/verify', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return view('auth.otp-verify');
})->name('otp.verify');

Route::middleware(['auth', 'verified', 'otp.verified'])->group(function () {
    Route::get('/drafts', function () {
        $user = auth()->user();
        $cvs = $user->cvs()
            ->with(['experiences', 'skills', 'certifications'])
            ->latest()
            ->get();

        return view('welcome-cvs', ['cvs' => $cvs]);
    })->name('drafts');

    Route::get('/builder', CvBuilder::class)->name('cv.builder');
    Route::get('/builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/evaluator', CvEvaluator::class)->name('cv.evaluator');
    Route::get('/referrals', ReferralDashboard::class)->name('referrals');
    Route::get('/credits', CreditHistory::class)->name('credits.history');
    Route::get('/preview/{cv}', function (Cv $cv) {
        if ($cv->user_id !== auth()->id()) {
            abort(403);
        }

        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects', 'languages']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');
});

require __DIR__.'/settings.php';

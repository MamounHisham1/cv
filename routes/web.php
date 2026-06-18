<?php

use App\Http\Controllers\Auth\ImpersonateController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CvExportController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\VfcashWebhookController;
use App\Livewire\AiInterviewer;
use App\Livewire\CreditHistory;
use App\Livewire\CvBuilder;
use App\Livewire\CvEvaluator;
use App\Livewire\Drafts;
use App\Livewire\EvaluationHistory;
use App\Livewire\InterviewHistory;
use App\Livewire\ReferralDashboard;
use App\Livewire\Upgrade;
use App\Models\Cv;
use App\Models\User;
use App\Services\CvExportService;
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

// Public share route — no auth. Resolves a CV by its opaque share token;
// CVs without a token are unreachable here.
Route::get('/r/{token}', function (string $token) {
    $cv = Cv::where('share_token', $token)->firstOrFail();
    $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects', 'languages']);

    return view('cv.shared', ['cv' => $cv]);
})->name('cv.share')->where('token', '[A-Za-z0-9]{32}');

// Public export via share token — lets visitors download a shared CV
// without authenticating. Owner is the CV; auth bypassed by token.
Route::get('/r/{token}/export/{format}', function (string $token, string $format) {
    abort_unless(in_array($format, ['pdf', 'docx'], true), 404);
    $cv = Cv::where('share_token', $token)->firstOrFail();

    $service = app(CvExportService::class);
    $content = $format === 'pdf' ? $service->toPdf($cv) : $service->toDocx($cv);

    return response()->streamDownload(
        function () use ($content): void {
            echo $content;
        },
        $service->filename($cv, $format),
        ['Content-Type' => $format === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
    );
})->name('cv.export.public')
    ->where('token', '[A-Za-z0-9]{32}')
    ->where('format', 'pdf|docx');

Route::post('/webhooks/vfcash', VfcashWebhookController::class)->name('webhooks.vfcash');

Route::post('/webhooks/telegram', TelegramWebhookController::class)->name('webhooks.telegram');

Route::middleware(['auth', 'impersonate'])->group(function () {
    Route::get('/impersonate/{user}', [ImpersonateController::class, 'start'])
        ->name('impersonate.start');
    Route::post('/impersonate/stop', [ImpersonateController::class, 'stop'])
        ->name('impersonate.stop');
});

Route::middleware(['auth', 'verified', 'otp.verified'])->group(function () {
    Route::get('/drafts', Drafts::class)->name('drafts');

    Route::get('/builder', CvBuilder::class)->name('cv.builder');
    Route::get('/builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/evaluator/{cv?}', CvEvaluator::class)->name('cv.evaluator');
    Route::get('/interview', AiInterviewer::class)->name('ai.interview');
    Route::get('/interview/history', InterviewHistory::class)->name('interview.history');
    Route::get('/evaluations/history', EvaluationHistory::class)->name('evaluations.history');
    Route::get('/referrals', ReferralDashboard::class)->name('referrals');
    Route::get('/credits', CreditHistory::class)->name('credits.history');
    Route::get('/upgrade', Upgrade::class)->name('upgrade');
    Route::get('/preview/{cv}', function (Cv $cv) {
        if ($cv->user_id !== auth()->id()) {
            abort(403);
        }

        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects', 'languages']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');

    Route::get('/cv/{cv}/export/{format}', CvExportController::class)
        ->where('format', 'pdf|docx')
        ->name('cv.export');

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

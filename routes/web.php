<?php

use App\Livewire\CvBuilder;
use App\Livewire\CvEvaluator;
use App\Models\Cv;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing.design4')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/drafts', function () {
        $user = auth()->user();
        $cvs = $user->cvs()
            ->with(['experiences', 'skills', 'certifications'])
            ->latest()
            ->get();

        return view('welcome-cvs', ['cvs' => $cvs]);
    })->name('drafts');

    Route::get('/cv-builder', CvBuilder::class)->name('cv.builder');
    Route::get('/cv-builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/cv-evaluator', CvEvaluator::class)->name('cv.evaluator');
    Route::get('/cv/{cv}/preview', function (Cv $cv) {
        if ($cv->user_id !== auth()->id()) {
            abort(403);
        }

        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects', 'languages']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');
});

require __DIR__.'/settings.php';

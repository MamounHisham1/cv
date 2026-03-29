<?php

use App\Livewire\CvBuilder;
use App\Models\Cv;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/contact', 'pages.contact')->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cv-builder', CvBuilder::class)->name('cv.builder');
    Route::get('/cv-builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/cv/{cv}/preview', function (Cv $cv) {
        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');
});

require __DIR__.'/settings.php';

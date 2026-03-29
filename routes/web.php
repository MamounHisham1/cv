<?php

use App\Livewire\CvBuilder;
use App\Models\Cv;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/cv-builder')->name('home');
Route::view('/1', 'landing.design1')->name('landing.1');
Route::view('/2', 'landing.design2')->name('landing.2');
Route::view('/3', 'landing.design3')->name('landing.3');
Route::view('/4', 'landing.design4')->name('landing.4');
Route::view('/5', 'landing.design5')->name('landing.5');
Route::view('/about', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/contact', 'pages.contact')->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/cv-builder', CvBuilder::class)->name('cv.builder');
    Route::get('/cv-builder/{cv}', CvBuilder::class)->name('cv.edit');
    Route::get('/cv/{cv}/preview', function (Cv $cv) {
        $cv->load(['educations', 'experiences', 'skills', 'certifications', 'projects']);

        return view('cv.preview', ['cv' => $cv]);
    })->name('cv.preview');
});

require __DIR__.'/settings.php';

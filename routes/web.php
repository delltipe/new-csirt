<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\WarningPostController;
use App\Http\Controllers\LawRulePostController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\ContactController;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/profile', 'profile')->name('profile');

// Public Info Routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/infographics', [InfographicController::class, 'index'])->name('infographics.index');
Route::get('/infographics/{infographic}', [InfographicController::class, 'show'])->name('infographics.show');

Route::get('/warnings', [WarningPostController::class, 'index'])->name('warnings.index');
Route::get('/warnings/{warning}', [WarningPostController::class, 'show'])->name('warnings.show');

Route::get('/laws', [LawRulePostController::class, 'index'])->name('laws.index');
Route::get('/laws/{post}', [LawRulePostController::class, 'show'])->name('laws.show');

Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{guide}', [GuideController::class, 'show'])->name('guides.show');

// ---------------------------------------------------------------
// Incident Report Routes
// ---------------------------------------------------------------
Route::prefix('report-incident')->group(function () {

    // NEW: Single-page JS wizard — this is what the navbar button points to
    Route::get('/', [IncidentReportController::class, 'create'])->name('incidents.create.step1');
    Route::post('/store', [IncidentReportController::class, 'store'])->name('incidents.store');
    Route::get('/thank-you', [IncidentReportController::class, 'thankYou'])->name('incidents.thank-you');

    // KEPT: Old separate-step routes — still work as fallback
    Route::get('/step-1', [IncidentReportController::class, 'createStep1'])->name('incidents.create.step1.old');
    Route::post('/step-1', [IncidentReportController::class, 'postStep1'])->name('incidents.create.step1.post');

    Route::get('/step-2', [IncidentReportController::class, 'createStep2'])->name('incidents.create.step2');
    Route::post('/step-2', [IncidentReportController::class, 'postStep2'])->name('incidents.create.step2.post');

    Route::get('/step-3', [IncidentReportController::class, 'createStep3'])->name('incidents.create.step3');
});

// ---------------------------------------------------------------
// Contact Routes
// ---------------------------------------------------------------
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thank-you/contact', [ContactController::class, 'thankYou'])->name('contact.thank-you');

// Authentication Routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
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

// Public Info Routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

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

// Form Routes
Route::get('/report-incident', [IncidentReportController::class, 'create'])->name('incidents.create');
Route::post('/report-incident', [IncidentReportController::class, 'store'])->name('incidents.store');
Route::get('/thank-you/incident', [IncidentReportController::class, 'thankYou'])->name('incidents.thank-you');

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

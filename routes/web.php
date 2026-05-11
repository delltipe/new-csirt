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
use App\Http\Controllers\AdminController;

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

// Admin login routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin dashboard (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // More admin routes for CRUD will go here

    // News CRUD
    Route::get('/admin/news', [AdminController::class, 'newsList'])->name('admin.news.list');
    Route::post('/admin/news', [AdminController::class, 'newsStore'])->name('admin.news.store');
    Route::get('/admin/news/{id}/edit', [AdminController::class, 'newsEdit'])->name('admin.news.edit');
    Route::post('/admin/news/{id}/update', [AdminController::class, 'newsUpdate'])->name('admin.news.update');
    Route::post('/admin/news/{id}/delete', [AdminController::class, 'newsDelete'])->name('admin.news.delete');

    // Events CRUD
    Route::get('/admin/events', [AdminController::class, 'eventsList'])->name('admin.events.list');
    Route::post('/admin/events', [AdminController::class, 'eventStore'])->name('admin.event.store');
    Route::get('/admin/events/{id}/edit', [AdminController::class, 'eventEdit'])->name('admin.event.edit');
    Route::post('/admin/events/{id}/update', [AdminController::class, 'eventUpdate'])->name('admin.event.update');
    Route::post('/admin/events/{id}/delete', [AdminController::class, 'eventDelete'])->name('admin.event.delete');

    // Warnings CRUD
    Route::get('/admin/warnings', [AdminController::class, 'warningsList'])->name('admin.warnings.list');
    Route::post('/admin/warnings', [AdminController::class, 'warningStore'])->name('admin.warning.store');
    Route::get('/admin/warnings/{id}/edit', [AdminController::class, 'warningEdit'])->name('admin.warning.edit');
    Route::post('/admin/warnings/{id}/update', [AdminController::class, 'warningUpdate'])->name('admin.warning.update');
    Route::post('/admin/warnings/{id}/delete', [AdminController::class, 'warningDelete'])->name('admin.warning.delete');

    // Laws CRUD
    Route::get('/admin/laws', [AdminController::class, 'lawsList'])->name('admin.laws.list');
    Route::post('/admin/laws', [AdminController::class, 'lawStore'])->name('admin.law.store');
    Route::get('/admin/laws/{id}/edit', [AdminController::class, 'lawEdit'])->name('admin.law.edit');
    Route::post('/admin/laws/{id}/update', [AdminController::class, 'lawUpdate'])->name('admin.law.update');
    Route::post('/admin/laws/{id}/delete', [AdminController::class, 'lawDelete'])->name('admin.law.delete');

    // Guides CRUD
    Route::get('/admin/guides', [AdminController::class, 'guidesList'])->name('admin.guides.list');
    Route::post('/admin/guides', [AdminController::class, 'guideStore'])->name('admin.guide.store');
    Route::get('/admin/guides/{id}/edit', [AdminController::class, 'guideEdit'])->name('admin.guide.edit');
    Route::post('/admin/guides/{id}/update', [AdminController::class, 'guideUpdate'])->name('admin.guide.update');
    Route::post('/admin/guides/{id}/delete', [AdminController::class, 'guideDelete'])->name('admin.guide.delete');

    // Infographics CRUD
    Route::get('/admin/infographics', [AdminController::class, 'infographicsList'])->name('admin.infographics.list');
    Route::post('/admin/infographics', [AdminController::class, 'infographicStore'])->name('admin.infographic.store');
    Route::get('/admin/infographics/{id}/edit', [AdminController::class, 'infographicEdit'])->name('admin.infographic.edit');
    Route::post('/admin/infographics/{id}/update', [AdminController::class, 'infographicUpdate'])->name('admin.infographic.update');
    Route::post('/admin/infographics/{id}/delete', [AdminController::class, 'infographicDelete'])->name('admin.infographic.delete');
});
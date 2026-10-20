<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\WarningPostController;
use App\Http\Controllers\LawRulePostController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BugHunterController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\IncidentTrackingController;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/profile', 'profile')->name('profile');

// Official CSIRT reference documents and archived statistics
Route::view('/rfc2350', 'rfc2350')->name('rfc2350');
Route::get('/publickey', function () {
    return response()->download(
        public_path('downloads/JakartaProv-CSIRT-csirt@jakarta.go.id-0x7B1A4B82D1C4F8A4-public.asc'),
        'JakartaProv-CSIRT-csirt@jakarta.go.id-0x7B1A4B82D1C4F8A4-public.asc',
        ['Content-Type' => 'application/pgp-keys']
    );
})->name('publickey');
Route::get('/statistics', function (\Illuminate\Http\Request $request) {
    $page = $request->integer('page', 1);

    abort_unless(in_array($page, [1, 2], true), 404);

    return view('statistics', compact('page'));
})->name('statistics');

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

// Demo — hero contrast mitigations (temporary, local only)
Route::get('/demo/hero-contrast', fn() => view('demo.hero-contrast'))->name('demo.hero-contrast');

// Search
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

// CAPTCHA (math, self-hosted, no external API) — shared by incident + contact forms
Route::get('/captcha/refresh', [CaptchaController::class, 'refresh'])->name('captcha.refresh');

// ---------------------------------------------------------------
// Public Authentication Routes — Disabled for Read-Only Preview
// ---------------------------------------------------------------
Route::get('/register', fn() => abort(403, 'Pendaftaran akun dinonaktifkan pada versi pratinjau publik.'))->name('register');
Route::post('/register', fn() => abort(403, 'Pendaftaran akun dinonaktifkan pada versi pratinjau publik.'))->name('register.submit');
Route::get('/login', fn() => abort(403, 'Autentikasi dinonaktifkan pada versi pratinjau publik.'))->name('login');
Route::post('/login', fn() => abort(403, 'Autentikasi dinonaktifkan pada versi pratinjau publik.'))->name('login.submit');
Route::post('/logout', fn() => abort(403, 'Autentikasi dinonaktifkan pada versi pratinjau publik.'))->name('logout');

// ---------------------------------------------------------------
// Bug Hunter (Reporter) Routes — Disabled for Read-Only Preview
// ---------------------------------------------------------------
Route::get('/bug-hunter', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.dashboard');
Route::get('/bug-hunter/laporan', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.tac');
Route::post('/bug-hunter/laporan/agree', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.agree');
Route::get('/bug-hunter/laporan/baru', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.create');
Route::post('/bug-hunter/laporan/simpan', fn() => abort(403, 'Pengunggahan berkas dan pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.store');
Route::get('/bug-hunter/laporan/selesai', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.thank-you');
Route::get('/bug-hunter/laporan/{id}', fn() => abort(403, 'Pelaporan insiden dinonaktifkan pada versi pratinjau publik.'))->name('bug-hunter.show');

// Public Incident Ticket Tracking — Disabled to prevent probing
Route::get('/lacak-laporan', fn() => abort(403, 'Pelacakan tiket insiden dinonaktifkan pada versi pratinjau publik.'))->name('ticket.track');

// ---------------------------------------------------------------
// Contact Routes
// ---------------------------------------------------------------
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', fn() => back()->withInput()->withErrors(['pesan' => 'Pengiriman pesan kontak dinonaktifkan pada versi pratinjau publik.']))->name('contact.store');
Route::get('/thank-you/contact', fn() => redirect()->route('contact.create'))->name('contact.thank-you');

// Dashboard Route (placeholder)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Admin login and CRUD routes — Completely disabled (404) for Read-Only Preview
Route::get('/admin/login', fn() => abort(404))->name('admin.login');
Route::post('/admin/login', fn() => abort(404))->name('admin.login.submit');
Route::post('/admin/logout', fn() => abort(404))->name('admin.logout');
Route::any('/admin/{any?}', fn() => abort(404))->where('any', '.*')->name('admin.dashboard');

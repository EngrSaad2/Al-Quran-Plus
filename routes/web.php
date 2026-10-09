<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/surah/{id}', [HomeController::class, 'surah'])->name('public.surah');

// Dedicated SEO Landing Pages
Route::get('/quran/bangla-translation', [HomeController::class, 'banglaTranslation'])->name('public.quran.bangla');
Route::get('/quran/english-translation', [HomeController::class, 'englishTranslation'])->name('public.quran.english');
Route::get('/quran/tafsir/bangla', [HomeController::class, 'tafsirBangla'])->name('public.quran.tafsir');
Route::get('/quran/audio', [HomeController::class, 'audio'])->name('public.quran.audio');
Route::get('/dua', [HomeController::class, 'dua'])->name('public.dua');
Route::get('/prayer-times', [HomeController::class, 'prayerTimes'])->name('public.prayer-times');
Route::get('/daily-ayah', [HomeController::class, 'dailyAyah'])->name('public.daily-ayah');

// XML Sitemap & Robots
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('public.sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('public.robots');

// Utility Routes
Route::get('/bookmarks', [HomeController::class, 'bookmarks'])->name('public.bookmarks');
Route::get('/search', [HomeController::class, 'search'])->name('public.search');
Route::get('/juz/{id}', [HomeController::class, 'juz'])->name('public.juz');
Route::get('/about', [HomeController::class, 'about'])->name('public.about');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('public.privacy');
Route::get('/contact', [HomeController::class, 'contact'])->name('public.contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('public.contact.submit');

// Named route 'login' alias for Laravel default Auth middleware
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Notifications CRUD
        Route::resource('notifications', NotificationController::class);
        Route::post('/notifications/{id}/duplicate', [NotificationController::class, 'duplicate'])->name('notifications.duplicate');
        Route::post('/notifications/{id}/send-push', [NotificationController::class, 'sendPush'])->name('notifications.send-push');
    });
});

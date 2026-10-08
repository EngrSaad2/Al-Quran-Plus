<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/surah/{id}', [HomeController::class, 'surah'])->name('public.surah');
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

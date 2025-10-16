<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthPageController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Tanpa login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'guestDashboard'])->name('home');
    Route::view('/pricing', 'guest.pricing')->name('pricing');
    Route::view('/about', 'guest.about')->name('about');

    // Sign Up
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Reset Password
    Route::prefix('password')->group(function () {
        Route::get('/forgot', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Hanya login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard utama

    // Halaman tambahan
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/plan', [PageController::class, 'plan'])->name('plan');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');

    // Upload video
    Route::prefix('videos')->group(function () {
        Route::get('/upload', [UploadController::class, 'index'])->name('videos.index');
        Route::post('/upload', [UploadController::class, 'store'])->name('videos.store');
        Route::get('/test-mail/{id}', [UploadController::class, 'testEmail'])->name('videos.test.mail');
    });

    Route::prefix('analytics')->middleware('auth')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/{id}', [AnalyticsController::class, 'show'])->name('analytics.show');
    });
    
    Route::get('/plan', [AuthPageController::class, 'plan'])->name('plan');
    Route::post('/plan/change', [AuthPageController::class, 'changePlan'])->name('plan.change');


    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
| Jika route tidak ditemukan, arahkan berdasarkan status login.
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('analytics');
    }
    return redirect()->route('home');
});

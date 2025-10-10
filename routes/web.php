<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route publik, autentikasi, dan dashboard user.
|
*/

// 🟢 Public routes
Route::view('/', 'guest.dashboard')->name('home');
Route::view('/pricing', 'guest.pricing')->name('pricing');
Route::view('/about', 'guest.about')->name('about');

// 🟡 Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');



    // 🔹 Halaman input email untuk lupa password
    Route::get('/forgot-password', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');

    // 🔹 Halaman reset password (pakai token dari email)
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

});

// 🔵 Dashboard (authenticated routes)
Route::middleware('auth')->group(function () {

    // Dashboard utama
    Route::get('/dashboard', fn() => view('analytics'))->name('dashboard');

    // Halaman user
    Route::get('/analytics', fn() => view('analytics'))->name('analytics');
    Route::get('/plan', fn() => view('plan'))->name('plan');
    Route::get('/profile', fn() => view('profile'))->name('profile');

    // Upload video — gunakan nama unik untuk menghindari konflik
    Route::get('/video-uploads', [UploadController::class, 'index'])->name('videos.index');
    Route::post('/video-uploads', [UploadController::class, 'store'])->name('videos.store');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// 🚨 Fallback route
Route::fallback(function () {
    return redirect('/dashboard');
});

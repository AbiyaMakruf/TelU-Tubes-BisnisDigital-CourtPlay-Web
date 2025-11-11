<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SocialController;




Route::get('/news',        [PostController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [PostController::class, 'show'])->name('news.show');
Route::prefix('user')->group(function () {
    Route::get('/{username}', [PublicProfileController::class, 'show'])->name('user.profile');
});
// Route::get('/{username}', [PublicProfileController::class, 'show'])->name('public.profile');
// Route::post('/payment-complete', [PaymentController::class, 'handleCallbackSuccess'])->name('payment.callback');

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
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard utama

    // Halaman tambahan
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/plan', [PageController::class, 'plan'])->name('plan');
    // Route::get('/profile', [PageController::class, 'profile'])->name('profile');

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

    Route::get('/plan', [PlanController::class, 'plan'])->name('plan');
    Route::post('/plan/change', [PlanController::class, 'changePlan'])->name('plan.change');

    Route::prefix('profile')->middleware('auth')->group(function () {
        Route::get('/', [ProfileController::class, 'profile'])->name('profile');
        Route::post('/', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture');
        Route::delete('/picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
    });

    // Social Controller
    Route::get('/social', [SocialController::class, 'index'])->name('social');
    Route::post('/user/{username}/toggleFollow', [SocialController::class, 'toggleFollow'])->name('user.toggleFollow');
    Route::post('/user/{username}/follow', [SocialController::class, 'follow'])->name('user.follow');



    // === di bawah semua route ===
    Route::post('/payment/create', [PaymentController::class, 'createTransaction'])->name('payment.create');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
  Route::get('/', [AdminController::class,'dashboard'])->name('dashboard');

  // Users
  Route::get('/users', [AdminController::class,'usersIndex'])->name('users.index');
  Route::patch('/users/{user}/role', [AdminController::class,'usersUpdateRole'])->name('users.role');
  Route::delete('/users/{user}', [AdminController::class,'usersDestroy'])->name('users.destroy');

  // Projects
  Route::get('/projects', [AdminController::class,'projectsIndex'])->name('projects.index');
  Route::delete('/projects/{project}', [AdminController::class,'projectsDestroy'])->name('projects.destroy');

  // Posts (News)
  Route::get('/posts', [AdminController::class,'postsIndex'])->name('posts.index');
  Route::get('/posts/create', [AdminController::class,'postsCreate'])->name('posts.create');
  Route::post('/posts', [AdminController::class,'postsStore'])->name('posts.store');
  Route::get('/posts/{post}/edit', [AdminController::class,'postsEdit'])->name('posts.edit');
  Route::put('/posts/{post}', [AdminController::class,'postsUpdate'])->name('posts.update');
  Route::delete('/posts/{post}', [AdminController::class,'postsDestroy'])->name('posts.destroy');
  Route::patch('/posts/{post}/toggle', [AdminController::class,'postsToggle'])->name('posts.toggle');



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

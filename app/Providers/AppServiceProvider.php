<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Izinkan hanya user dengan email tertentu
        Gate::define('viewApiDocs', function (?User $user) {
            // Jika belum login, $user = null
            if (! $user) {
                return false;
            }

            // Ganti daftar email sesuai kebutuhanmu
            return in_array($user->role, [
                'admin',
            ]);
        });

        // (opsional) batasi hanya route api/ yang dimasukkan ke dokumentasi
        Scramble::configure()
            ->routes(fn (Route $route) => Str::startsWith($route->uri, 'api/'));
    }
}

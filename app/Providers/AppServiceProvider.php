<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Role admin dan perangkat desa memiliki semua izin akses panel
        Gate::before(function ($user, $ability) {
            if ($user && $user->hasAnyRole(['Super Admin', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara', 'Admin Desa'])) {
                return true;
            }
            return null;
        });
    }
}

<?php

namespace App\Providers;

use App\Enums\AppRoles;
use Illuminate\Support\Facades\Broadcast;
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
        // Super admins bypass all authorization checks.
        Gate::before(function ($user, string $ability) {
            return $user->hasRole(AppRoles::SUPER_ADMIN->value) ? true : null;
        });

        // Broadcasting auth endpoint (/broadcasting/auth) — authenticated via Sanctum.
        Broadcast::routes(['middleware' => ['auth:sanctum']]);
        require base_path('routes/channels.php');
    }
}

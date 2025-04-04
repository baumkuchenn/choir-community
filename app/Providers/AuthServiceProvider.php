<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('akses-admin', function ($user) {
            return $user->members()->where('admin', 'ya')->exists();
        });

        Gate::define('akses-event', function ($user) {
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_event;
        });

        Gate::define('akses-member', function ($user) {
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_member;
        });

        Gate::define('akses-roles', function ($user) {
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_roles;
        });

        Gate::define('akses-eticket', function ($user) {
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_eticket;
        });
    }
}

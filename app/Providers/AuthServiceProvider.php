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
            if (!$user->members || $user->members->isEmpty()) {
                return false;
            }
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_event;
        });

        Gate::define('akses-event-panitia', function ($user) {
            return $user->panitias()
                ->whereHas('jabatan', function ($query) {
                    $query->where('akses_event', '1');
                })
                ->exists();
        });

        Gate::define('akses-eticket', function ($user) {
            if (!$user->members || $user->members->isEmpty()) {
                return false;
            }
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_eticket;
        });

        Gate::define('akses-eticket-panitia', function ($user) {
            return $user->panitias()
                ->whereHas('jabatan', function ($query) {
                    $query->where('akses_eticket', '1');
                })
                ->exists();
        });

        Gate::define('akses-eticket-semua', function ($user) {
            return Gate::forUser($user)->allows('akses-eticket') ||
                Gate::forUser($user)->allows('akses-eticket-panitia');
        });

        Gate::define('akses-member', function ($user) {
            if (!$user->members || $user->members->isEmpty()) {
                return false;
            }
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_member;
        });

        Gate::define('akses-roles', function ($user) {
            if (!$user->members || $user->members->isEmpty()) {
                return false;
            }
            return $user->members()->where('admin', 'ya')->exists() ||
                optional($user->members->first()->position)->akses_roles;
        });
    }
}

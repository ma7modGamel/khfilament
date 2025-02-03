<?php

namespace App\Providers;

use App\Filament\MainPanal\Pages\GlobalLogout;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /*
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, GlobalLogout::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        Gate::define('use-translation-manager', function (?User $user) {
            return true;
            // return $user !== null && ($user->hasRole('admin') || $user->hasRole('Super Admin'));
        });
    }
}
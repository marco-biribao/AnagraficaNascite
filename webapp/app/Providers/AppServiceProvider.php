<?php

namespace App\Providers;

use App\Contracts\GeneratoreDocumentoPdf;
use App\Services\Pdf\DompdfGeneratoreDocumento;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeneratoreDocumentoPdf::class, DompdfGeneratoreDocumento::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('gestire-sistema', fn ($user) => $user->hasRole('amministratore'));
        Gate::define('gestire-dichiarazioni', fn ($user) => $user->hasRole('amministratore') || $user->hasRole('operatore'));
    }
}

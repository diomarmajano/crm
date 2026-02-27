<?php

namespace App\Providers;

use App\Http\Middleware\SetTenantDatabase;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Obliga a Livewire a recordar y ejecutar este middleware en
        // CADA petición AJAX que haga dentro del sistema.
        Livewire::addPersistentMiddleware([
            SetTenantDatabase::class,
        ]);
    }
}

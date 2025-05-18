<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de aplicación.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Realiza cualquier acción de arranque.
     *
     * @return void
     */
    public function boot()
    {
        // Aquí registramos el Gate
        Gate::define('manage-resources', function (User $user) {
            return $user->role === 'admin';  // Solo admin puede gestionar recursos
        });
    }
}


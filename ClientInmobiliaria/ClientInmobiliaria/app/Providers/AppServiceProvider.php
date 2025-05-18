<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


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

     public function boot()
     {
         // Compartir usuario autenticado con todas las vistas
         View::composer('*', function ($view) {
             $view->with('authUser', Session::get('user'));
         });
     
         // Establecer el idioma en español para Carbon
         Carbon::setLocale('es');
     }
}

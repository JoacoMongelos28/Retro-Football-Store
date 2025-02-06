<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Log;

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
        View::composer('*', function ($view) {
            $usuario = session('usuarioId');
            $homeController = new HomeController();
            $datosCarrito = $homeController->obtenerDatosDelCarrito();
            $view->with($datosCarrito)->with('usuario', $usuario);
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        // Register custom resource registrar
        app('router')->macro('customResource', function ($name, $controller, $options = []) {
            $registrar = new \App\Routing\ResourceRegistrar(app('router'));
            $registrar->registerCustomResource($name, $controller, $options);
        });
         // Register custom resource registrar files
        app('router')->macro('customResourceFiles', function ($name, $controller, $options = []) {
            $registrarFiles = new \App\Routing\ResourceRegistrarFiles(app('router'));
            $registrarFiles->registerCustomResource($name, $controller, $options);
        });
    }
}

<?php

namespace Gurudin\Admin;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Gurudin\Admin\Middleware\AdminAuthPermission;

class AdminServicProvider extends ServiceProvider
{
    /**
     * Admin namespace.
     *
     * @var string
     */
    protected $namespace = 'Gurudin\Admin';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** Add middleware */
        Route::aliasMiddleware('admin', AdminAuthPermission::class);

        /** Add route address */
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/admin.php');

        /** Add views */
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        /** Add migrations */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        /** Add translations */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'admin');

        /** Publishes static resources */
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/gurudin'),
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        ], 'gurudin-admin');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

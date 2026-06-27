<?php

namespace FinTrack\FinLib\Providers;

use FinTrack\FinLib\FinLib;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FinLibServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/fin-lib.php', 'fin-lib');

        $this->app->singleton('fin-lib', fn() => new FinLib());
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'fin-lib');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/langs', 'fin-lib');

        Route::middleware('api')
            ->prefix('fl-api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
            });

        Route::middleware('web')
            ->prefix('fl')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
            });


        $this->publishes([
            __DIR__ . '/../../config/fin-lib.php' => config_path('fin-lib.php'),
        ], 'fin-lib-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'fin-lib-migrations');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/fin-lib'),
        ], 'fin-lib-views');

        $this->publishes([
            __DIR__ . '/../../resources/langs' => lang_path('vendor/fin-lib'),
        ], 'fin-lib-lang');
    }
}

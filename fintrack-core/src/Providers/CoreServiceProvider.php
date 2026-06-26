<?php

namespace FinTrack\Core\Providers;

use FinTrack\Core\Core;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/core.php', 'fintrack-core');

        $this->app->singleton('fintrack-core', fn() => new Core());
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'fintrack-core');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/langs', 'fintrack-core');

        $routesPath = __DIR__ . '/../../routes';
        if (is_dir($routesPath)) {
            foreach (glob("{$routesPath}/*.php") as $route) {
                $this->loadRoutesFrom($route);
            }
        }

        $this->publishes([
            __DIR__ . '/../../config/core.php' => config_path('fintrack-core.php'),
        ], 'fintrack-core-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'fintrack-core-migrations');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/fintrack-core'),
        ], 'fintrack-core-views');

        $this->publishes([
            __DIR__ . '/../../resources/langs' => lang_path('vendor/fintrack-core'),
        ], 'fintrack-core-lang');
    }
}

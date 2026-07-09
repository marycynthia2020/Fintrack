<?php

namespace FinTrack\FinLib\Providers;

use FinTrack\Core\Listeners\RecordAuditLog;
use FinTrack\Core\Listeners\RecordLedgerEntry;
use FinTrack\Core\Listeners\UpdateLedgerEntry;
use FinTrack\Core\Listeners\DeleteLedgerEntry;
use FinTrack\FinLib\Listeners\SendIncomeNotification;
use FinTrack\FinLib\Listeners\SendExpenseNotification;
use FinTrack\FinLib\Events\AccountCreated;
use FinTrack\FinLib\Events\AccountDeleted;
use FinTrack\FinLib\Events\AccountUpdated;
use FinTrack\FinLib\Events\AuditLogCreated;
use FinTrack\FinLib\Events\ExpenseCreated;
use FinTrack\FinLib\Events\ExpenseDeleted;
use FinTrack\FinLib\Events\ExpenseUpdated;
use FinTrack\FinLib\Events\IncomeCreated;
use FinTrack\FinLib\Events\IncomeDeleted;
use FinTrack\FinLib\Events\IncomeUpdated;
use FinTrack\FinLib\Events\LedgerCreated;
use FinTrack\FinLib\FinLib;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FinLibServiceProvider extends ServiceProvider
{
    /**
     * Maps each fin-lib event to the fintrack-core listeners that handle it.
     */
    protected array $listen = [
        IncomeCreated::class => [RecordLedgerEntry::class, RecordAuditLog::class, SendIncomeNotification::class],
        IncomeUpdated::class => [UpdateLedgerEntry::class, RecordAuditLog::class, SendIncomeNotification::class],
        IncomeDeleted::class => [DeleteLedgerEntry::class, RecordAuditLog::class, SendIncomeNotification::class],

        ExpenseCreated::class => [RecordLedgerEntry::class, RecordAuditLog::class, SendExpenseNotification::class],
        ExpenseUpdated::class => [UpdateLedgerEntry::class, RecordAuditLog::class, SendExpenseNotification::class],
        ExpenseDeleted::class => [DeleteLedgerEntry::class, RecordAuditLog::class, SendExpenseNotification::class],

        AccountCreated::class => [RecordAuditLog::class],
        AccountUpdated::class => [RecordAuditLog::class],
        AccountDeleted::class => [RecordAuditLog::class],

        LedgerCreated::class => [RecordAuditLog::class],
        AuditLogCreated::class => [],
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/fin-lib.php', 'fin-lib');

        $this->app->singleton('fin-lib', fn() => new FinLib());
    }

    public function boot(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }

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

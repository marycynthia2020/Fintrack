<?php

use Illuminate\Support\Facades\Route;
use FinTrack\FinLib\Controllers\Api\IncomeController;
use FinTrack\FinLib\Controllers\Api\ExpenseController;
use FinTrack\FinLib\Controllers\Api\AccountController;
use FinTrack\FinLib\Controllers\Api\DashboardController;
use FinTrack\FinLib\Controllers\Api\LedgerController;


// protected routes
Route::middleware(['auth:sanctum'])
->group(function () {
    // pls note: you can; add protected routes here
    Route::get('incomes/categories', [IncomeController::class, 'categories'])->name('incomes-categories');
    Route::apiResource('incomes', IncomeController::class);
    Route::get('expenses/categories', [ExpenseController::class, 'categories'])->name('expenses-categories');
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('accounts/balance', [AccountController::class, 'balance'])->name('accounts-balance');
    Route::get('dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard-summary');
    Route::get('transactions', [LedgerController::class, 'index'])->name('transactions');
});


// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});
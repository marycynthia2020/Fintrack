<?php

use Illuminate\Support\Facades\Route;
use FinTrack\FinLib\Controllers\IncomeController;
use FinTrack\FinLib\Controllers\ExpenseController;
use FinTrack\FinLib\Controllers\AccountController;
use FinTrack\FinLib\Controllers\DashboardController;
use FinTrack\FinLib\Controllers\LedgerController;


// protected routes
Route::middleware(['auth:sanctum'])
->group(function () {
    // pls note: you can; add protected routes here
    Route::apiResource('incomes', IncomeController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('accounts/balance', [AccountController::class, 'balance'])->name('accounts.balance');
    Route::get('dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('transactions', [LedgerController::class, 'index'])->name('transactions');
});


// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});
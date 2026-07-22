<?php

use FinTrack\FinLib\Controllers\Web\IncomeController;
use Illuminate\Support\Facades\Route;

// protected routes
Route::middleware('auth')
->group(function () {
    Route::get('incomes', [IncomeController::class, 'index'])->name('income.index');
});

// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});

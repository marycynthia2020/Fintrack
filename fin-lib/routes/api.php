<?php

use Illuminate\Support\Facades\Route;
use FinTrack\FinLib\Controllers\IncomeController;
use FinTrack\FinLib\Controllers\ExpenseController;


// protected routes
Route::middleware(['auth:sanctum'])
->group(function () {
    // pls note: you can; add protected routes here
    Route::apiResource('incomes', IncomeController::class);
    Route::apiResource('expenses', ExpenseController::class);
    
});


// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});
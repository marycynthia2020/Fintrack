<?php

use FinTrack\FinLib\Controllers\Web\IncomeController;
use Illuminate\Support\Facades\Route;

// protected routes
Route::middleware('auth')
->group(function () {
    Route::get('incomes', [IncomeController::class, 'index'])->name('income.index');
    Route::post('incomes', [IncomeController::class, 'store'])->name('income.store');
    Route::get('incomes/{income}', [IncomeController::class, 'show'])->name('income.show');
    Route::get('incomes/{income}/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::put('income/{income}/update', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('incomes/{income}', [IncomeController::class, 'store'])->name('income.destroy');

});

// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});

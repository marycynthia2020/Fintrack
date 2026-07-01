<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
});


// public routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [SessionController::class, 'store'])->name('login');
});
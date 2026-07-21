<?php

use Fintrack\Core\Controllers\API\Auth\LoginController;
use Fintrack\Core\Controllers\API\Auth\RegisterController;
use FinTrack\Core\Controllers\API\UserApiController;
use Fintrack\Core\Controllers\Web\Auth\WebAuthController;
use Illuminate\Support\Facades\Route;

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserApiController::class, 'index']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/refresh', [LoginController::class, 'refresh'])->name('refresh');
});


// public routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

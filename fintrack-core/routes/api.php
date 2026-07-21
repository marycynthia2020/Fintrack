<?php

use Fintrack\Core\Controllers\API\Auth\LoginController;
use Fintrack\Core\Controllers\API\Auth\RegisterController;
use FinTrack\Core\Controllers\API\UserApiController;
use Fintrack\Core\Controllers\Web\Auth\WebAuthController;
use Illuminate\Support\Facades\Route;

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserApiController::class, 'index']);
    Route::post('/logout', [SessionController::class, 'destroy']);
    Route::post('/refresh', [SessionController::class, 'refresh']);
});


// public routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [SessionController::class, 'store']);
});

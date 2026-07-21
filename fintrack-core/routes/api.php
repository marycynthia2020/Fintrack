<?php

use FinTrack\Core\Controllers\Auth\SessionController;
use FinTrack\Core\Controllers\Auth\RegisterController;
use FinTrack\Core\Controllers\API\UserApiController;
use Illuminate\Support\Facades\Route;

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

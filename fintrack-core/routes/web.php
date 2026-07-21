<?php

use FinTrack\Core\Controllers\API\UserApiController;
use Fintrack\Core\Controllers\Web\Auth\WebAuthController;
use Illuminate\Support\Facades\Route;

// protected routes
Route::middleware('auth')
->group(function () {
    // pls note: you can add protected routes here
});

// public routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [WebAuthController::class, 'register'])->name('register');
    Route::get('/login', [WebAuthController::class, 'login'])->name('login');
    
});
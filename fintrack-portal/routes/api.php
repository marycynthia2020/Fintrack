<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\RegisterController;
use FinTrack\Core\Controllers\API\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserApiController::class, 'index']);
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::post('/refresh', [SessionController::class, 'refresh'])->name('refresh');
});


// public routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [SessionController::class, 'store'])->name('login');
});

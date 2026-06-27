<?php

use Illuminate\Support\Facades\Route;


// protected routes
Route::middleware(['auth:sanctum'])
->group(function () {
    // pls note: you can add protected routes here
});


// public routes
Route::middleware('guest')->group(function () {
    // pls note: you can add public routes here
});
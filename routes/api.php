<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController, GoogleController, RegisterController, DashboardController, CryptoController,
    AIPredictionController
};

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Google Sign-In
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);
    Route::get('/symbols', [CryptoController::class, 'getSymbols']);
    Route::get('/filtered-symbols', [CryptoController::class, 'getFilteredSymbols']);
    Route::get('/symbols/{symbol}', [CryptoController::class, 'getSymbolDetails']);
    Route::get('/predict/{symbol}', [CryptoController::class, 'getPredictionWithConfidence']); //predicting with algorithm
});

use App\Http\Controllers\AIChatController;

Route::middleware(['auth:sanctum', 'throttle:5,1'])->group(function () {
    Route::post('/chat', [AIChatController::class, 'sendMessage']);
});

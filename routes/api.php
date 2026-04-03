<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

// Public routes (no token needed)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);

// Protected routes (token required)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',           [UserController::class, 'logout']);
    Route::get('/profile',           [UserController::class, 'profile']);
    Route::post('/wallets',          [WalletController::class, 'store']);
    Route::get('/wallets/{id}',      [WalletController::class, 'show']);
    Route::post('/transactions',     [TransactionController::class, 'store']);
});
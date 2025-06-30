<?php

// use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\NewPasswordController;
use App\Http\Controllers\api\PasswordResetController;
use App\Http\Controllers\api\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/{provider}/redirect', [AuthController::class, 'socialRedirect'])->name('api.auth.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'socialCallback']);

// Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('api.password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('api.password.email');

// Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('api.password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('api.password.store');

Route::middleware(['auth:sanctum',])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
        ->name('api.verification.verify');
});

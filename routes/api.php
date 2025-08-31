<?php

// use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\ContactController;
use App\Http\Controllers\api\FavoriteController;
use App\Http\Controllers\api\NewPasswordController;
use App\Http\Controllers\api\PasswordResetController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\PropertyController;
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

    Route::post('edit-password', [AuthController::class, 'update_password']);
    // Properties
    Route::get('/properties', [PropertyController::class, 'index']);

    Route::post('/properties', [PropertyController::class, 'store']);

    Route::get('/properties/{property}', [PropertyController::class, 'show']);

    Route::get('/{user}/properties', [PropertyController::class, 'user']);

    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('front.properties.destroy');

    Route::get('/cities', [PropertyController::class, 'cities']);

    Route::get('/zones', [PropertyController::class, 'zones']);
    // End Properties

    // Favorites
    Route::get('/auth/favorites', [FavoriteController::class, 'index']);

    Route::post('/favorites', [FavoriteController::class, 'store']);

    Route::delete('/favorites/{property}', [FavoriteController::class, 'destroy']);
    // End Favorites

    // Profile
    Route::post('/profile', [ProfileController::class, 'store']);
    // End Profile
});

// Contact
Route::post('/contact', [ContactController::class, 'store']);
// End Contact

// Blog
Route::get('blog', [BlogController::class, 'index']);
Route::get('blog/{blog}', [BlogController::class, 'show']);
// End Blog

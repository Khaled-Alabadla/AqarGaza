<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FrontAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiesController as FrontPropertiesController;
use App\Http\Controllers\ZonesController;
use Illuminate\Support\Facades\Route;

// Index
Route::get('/', [HomeController::class, 'index'])->name('home');
// End Index

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('front.contact');
Route::post('/contact', [ContactController::class, 'contact_save']);
// End Contact

// Rules & Conditinos
Route::get('/responsibility', [PolicyController::class, 'responsibility'])->name('front.responsibility');
Route::get('/privacy', [PolicyController::class, 'privacy'])->name('front.privacy');
Route::get('/conditions', [PolicyController::class, 'conditions'])->name('front.conditinos');
// End Rules & Conditinos

// Blog
Route::get('blog', [BlogController::class, 'index'])->name('front.blog.index');
Route::get('blog/{blog}', [BlogController::class, 'show'])->name('front.blog.show');
// End Blog

// About
Route::get('about', AboutController::class)->name('front.about');
// End About

Route::middleware('auth', 'verified')->group(function () {
    // Chat
    Route::get('/conver', [MessengerController::class, 'index'])->name('chat.index');

    Route::get('/convers', [ChatsController::class, 'index'])->name('chats.index');

    Route::post('/convers/initiate/{property}', [ChatsController::class, 'initiateChat'])->name('chats.initiate');

    Route::get('/convers/{chat}/messages', [MessagesController::class, 'index'])->name('messages.index');

    Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');

    Route::post('messages/{id}', [MessagesController::class, 'update'])->name('messages.update');

    Route::delete('messages/{message}/delete', [MessagesController::class, 'destroy'])->name('messages.destroy');

    // Route::options('messages/{id}', function () {
    //     return response()->json([], 200)
    //         ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
    //         ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    //         ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    // });
    Route::options('messages/{message}/delete', function () {
        return response()->json([], 200)
            ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    });

    Route::options('{any}', function () {
        return response()->json([], 200)
            ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    })->where('any', '.*');

    Route::get('convers/{chat}', [ChatsController::class, 'show']);

    Route::delete('convers/{chat}', [ChatsController::class, 'destroy']);

    Route::put('convers/{chat}/read', [ChatsController::class, 'markMessagesAsRead']);
    // End Chat

    // Properties
    Route::get('/properties', [FrontPropertiesController::class, 'index'])->name('front.properties.index');

    Route::get('/properties/create', [FrontPropertiesController::class, 'create'])->name('front.properties.create');

    Route::post('/properties', [FrontPropertiesController::class, 'store'])->name('front.properties.store');

    Route::get('/properties/{property}', [FrontPropertiesController::class, 'show'])->name('front.properties.show');

    Route::get('/{user}/properties', [FrontPropertiesController::class, 'user'])->name('front.properties.user');

    Route::post('properties/{id}/delete', [FrontPropertiesController::class, 'destroy']);

    Route::options('properties/{id}/delete', function () {
        return response()->json([], 200)
            ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    });

    Route::get('properties/{property}/edit', [FrontPropertiesController::class, 'edit'])->name('properties.edit');

    Route::put('/properties/{property}', [FrontPropertiesController::class, 'update'])->name('front.properties.update');
    // End Properties

    // Profile
    Route::get('/edit-profile', [ProfileController::class, 'index'])->name('front.profile');
    Route::post('/edit-profile', [ProfileController::class, 'store']);
    // End Profile

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('front.favorites.index');

    Route::get('/api/favorites', [FavoriteController::class, 'apiIndex']);

    Route::post('/favorites', [FavoriteController::class, 'store']);

    Route::post('/favorites/delete', [FavoriteController::class, 'destroy']);

    Route::options('favorites/delete', function () {
        return response()->json([], 200)
            ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    });

    // End Favorites

    // Edit Password
    Route::get('edit-password', [FrontAuthController::class, 'edit_password'])->name('front.auth.edit_password');
    Route::post('edit-password', [FrontAuthController::class, 'update_password']);
    // End Edit Password

    // Zones
    Route::get('/zones/{cityId}', [ZonesController::class, 'getZonesByCity']);
    // End Zones
});

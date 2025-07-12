<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Dashboard\PropertiesController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FrontAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiesController as FrontPropertiesController;
use Illuminate\Support\Facades\Route;


// Index
Route::get('/', [HomeController::class, 'index'])->name('home');
// End Index

// Chat
Route::get('/chat', [MessengerController::class, 'index'])->name('chat.index');

Route::get('/chats', [ChatsController::class, 'index'])->name('chats.index');

Route::post('/chats/initiate/{property}', [ChatsController::class, 'initiateChat'])->name('chats.initiate');

Route::get('/chats/{chat}/messages', [MessagesController::class, 'index'])->name('messages.index');

Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');

Route::put('messages/{message}', [MessagesController::class, 'update'])->name('messages.update');

Route::delete('messages/{message}', [MessagesController::class, 'destroy'])->name('messages.destroy');

Route::get('chats/{chat}', [ChatsController::class, 'show']);

Route::delete('chats/{chat}', [ChatsController::class, 'destroy']);

Route::put('chats/{chat}/read', [ChatsController::class, 'markMessagesAsRead']);
// End Chat

// Properties
Route::get('/properties', [FrontPropertiesController::class, 'index'])->name('front.properties.index');

Route::get('/properties/create', [FrontPropertiesController::class, 'create'])->name('front.properties.create');

Route::post('/properties', [FrontPropertiesController::class, 'store'])->name('front.properties.store');

Route::get('/properties/{property}', [FrontPropertiesController::class, 'show'])->name('front.properties.show');
// End Properties

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('front.profile');
Route::post('/profile', [ProfileController::class, 'store']);
// End Profile

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('front.contact');
Route::post('/contact', [ContactController::class, 'contact_save']);
// End Contact

// Favorites
Route::get('/favorites', [FavoriteController::class, 'index'])->name('front.favorites.index');
Route::match(['post', 'delete'], '/favorites/{propertyId}', [PropertiesController::class, 'toggleFavorite'])->name('front.favorites.toggle')->middleware('auth');
// End Favorites

// Edit Password
Route::get('edit-password', [FrontAuthController::class, 'edit_password'])->name('front.auth.edit_password');
Route::post('edit-password', [FrontAuthController::class, 'update_password']);
// End Edit Password

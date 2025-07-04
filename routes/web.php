<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\dashboard\AdminsController;
use App\Http\Controllers\Dashboard\PropertiesController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\QueriesController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FrontAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiesController as FrontPropertiesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('dashboard')->middleware(['auth', 'verified', 'admin'])->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.home');
    })->name('index');
    // Users
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/import', function () {
        return view('import');
    });
    // End Users

    // Roles
    Route::resource('roles', RolesController::class)->middleware('password.confirm')->except('show');
    // End Roles

    // Admins
    Route::resource('admins', AdminsController::class)->except(['show']);
    // End Admins


    Route::get('users/trash', [UsersController::class, 'trash'])->name('users.trash');

    Route::delete('users/{user}/force-delete', [UsersController::class, 'force_delete'])->name('users.force_delete');

    Route::get('users/{user}/restore', [UsersController::class, 'restore'])->name('users.restore');

    Route::get('users/roles', [UsersController::class, 'roles'])->middleware('password.confirm')->name('users.roles.index');

    Route::get('users/{user}/roles/edit', [UsersController::class, 'editRoles'])->middleware('password.confirm')->name('users.roles.edit');

    Route::put('users/{user}/roles/update', [UsersController::class, 'updateRoles'])->middleware('password.confirm')->name('users.roles.update');

    Route::resource('users', UsersController::class)->except(['show', 'edit']);

    Route::put('users/{user}/edit-profile-image', [UsersController::class, 'edit_profile'])->name('users.edit_profile_image');

    Route::get('users/edit-profile', [UsersController::class, 'edit'])
        ->name('users.edit');

    // Properties
    Route::get('properties/{user}/details', [PropertiesController::class, 'show_user_properties'])->name('properties.user');

    Route::get('/properties/trash', [PropertiesController::class, 'trash'])->name('properties.trash');

    Route::delete('/properties/{property}/force-delete', [PropertiesController::class, 'force_delete'])->name('properties.force_delete');

    Route::get('/properties/{property}/restore', [PropertiesController::class, 'restore'])->name('properties.restore');

    Route::resource('properties', PropertiesController::class);
    // End Properties

    // Queries
    Route::get('/queries/users', [QueriesController::class, 'users'])->name('queries.users');
    Route::get('/queries/properties', [QueriesController::class, 'properties'])->name('queries.properties');
    // End Queries

    // Settings
    Route::put('users/{user}/edit-profile', [SettingsController::class, 'edit_profile_check'])->name('users.editProfileCheck');

    Route::get('users/reset-password', [SettingsController::class, 'reset_password'])->name('users.reset_password');

    Route::post('users/reset-password', [SettingsController::class, 'reset_password_check'])->name('users.reset_password_check');

    // End Settings

    // Notifications
    Route::patch('/notifications/{notification}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.read.all');
    // End Notifications


    // Layout
    Route::get('{page?}', [AdminController::class, 'redirect'])->name('dashboard');
    // End Layout
});

Route::get('/chat', [MessengerController::class, 'index'])->name('chat.index');

Route::get('/chats', [ChatsController::class, 'index'])->name('chats.index');

Route::get('/chats/{chat}/messages', [MessagesController::class, 'index'])->name('messages.index');

Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');

Route::put('messages/{message}', [MessagesController::class, 'update'])->name('messages.update');

Route::delete('messages/{message}', [MessagesController::class, 'destroy'])->name('messages.destroy');

Route::get('chats/{chat}', [ChatsController::class, 'show']);

Route::delete('chats/{chat}', [ChatsController::class, 'destroy']);

Route::put('chats/{chat}/read', [ChatsController::class, 'markMessagesAsRead']);

// Properties
Route::get('/properties', [FrontPropertiesController::class, 'index'])->name('front.properties.index');

Route::get('/properties/create', [FrontPropertiesController::class, 'create'])->name('front.properties.create');

Route::post('/properties', [FrontPropertiesController::class, 'store'])->name('front.properties.store');

Route::get('/properties/{property}', [FrontPropertiesController::class, 'show'])->name('front.properties.show');

Route::match(['post', 'delete'], '/favorites/{propertyId}', [PropertiesController::class, 'toggleFavorite'])->name('front.favorites.toggle')->middleware('auth');

Route::get('/profile', [ProfileController::class, 'index'])->name('front.profile');

Route::get('/contact', [ContactController::class, 'index'])->name('front.contact');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('front.favorites.index');

Route::get('edit-password', [FrontAuthController::class, 'edit_password'])->name('front.auth.edit_password');

// End Properties

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

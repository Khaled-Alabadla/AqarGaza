<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\ContactMessagesController;
use App\Http\Controllers\Dashboard\PagesController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\WebsiteSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->middleware(['auth', 'verified', 'admin'])->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.home');
    })->name('index');

    // Roles
    Route::resource('roles', RolesController::class)->middleware('password.confirm')->except('show');
    // End Roles

    // Admins
    Route::resource('admins', AdminsController::class)->except(['show']);
    // End Admins

    // Pages
    Route::resource('pages', PagesController::class)->except(['show',]);
    // End Pages

    // Website Settings
    Route::get('settings', [WebsiteSettingsController::class, 'settings'])->name('settings');
    Route::put('settings', [WebsiteSettingsController::class, 'settings_save']);
    Route::get('delete-logo', [WebsiteSettingsController::class, 'delete_logo'])->name('delete_logo');
    // End Website Settings

    // Contact Messages
    Route::get('contact-messages', [ContactMessagesController::class, 'index'])->name('contact_messages.index');
    Route::post('contact-messages/{message}', [ContactMessagesController::class, 'reply'])->name('contact_messages.reply');
    Route::get('contact-messages/{message}', [ContactMessagesController::class, 'show'])->name('contact_messages.show');
    Route::delete('contact-messages/{message}', [ContactMessagesController::class, 'destroy'])->name('contact_messages.destroy');
    // End Contact Messages


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

    // Layout
    Route::get('{page?}', [AdminController::class, 'redirect'])->name('dashboard');
    // End Layout
});

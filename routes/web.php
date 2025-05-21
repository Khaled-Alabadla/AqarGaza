<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\PropertiesController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

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


    Route::delete('/users/{user}/force-delete', [UsersController::class, 'force_delete'])->name('users.force_delete');

    Route::get('/users/{user}/restore', [UsersController::class, 'restore'])->name('users.restore');

    Route::get('users/roles', [UsersController::class, 'roles'])->name('users.roles.index');

    Route::get('users/{user}/roles/edit', [UsersController::class, 'editRoles'])->name('users.roles.edit');

    Route::put('users/{user}/roles/update', [UsersController::class, 'updateRoles'])->name('users.roles.update');

    Route::resource('users', UsersController::class)->except(['show', 'edit']);

    Route::get('users/edit-profile', [UsersController::class, 'edit'])
        ->name('users.edit');

    // Properties
    Route::get('properties/{user}/details', [PropertiesController::class, 'show_user_properties'])->name('properties.user');

    Route::get('/properties/trash', [PropertiesController::class, 'trash'])->name('properties.trash');

    Route::delete('/properties/{property}/force-delete', [PropertiesController::class, 'force_delete'])->name('properties.force_delete');

    Route::get('/properties/{property}/restore', [PropertiesController::class, 'restore'])->name('properties.restore');

    Route::resource('properties', PropertiesController::class);
    // End Properties

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


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

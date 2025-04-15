<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\PropertiesController;
use App\Http\Controllers\Dashboard\DonorsController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\QueriesController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::get('dashboard', [AdminController::class, 'redirect'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard.home');
    // });
    // Users
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/import', function () {
        return view('import');
    });
    // End Users

    // Roles
    Route::resource('roles', RolesController::class)->middleware('auth')->except('show');
    // End Roles

    // Employees
    // Route::get('/users/trash', [UsersController::class, 'trash'])->name('users.trash')->middleware('auth');

    Route::delete('/users/{user}/force-delete', [UsersController::class, 'force_delete'])->name('users.force_delete')->middleware('auth');

    Route::get('/users/{user}/restore', [UsersController::class, 'restore'])->name('users.restore')->middleware('auth');

    Route::get('users/roles', [UsersController::class, 'roles'])->name('users.roles.index')->middleware('auth');

    Route::get('users/{user}/roles/edit', [UsersController::class, 'editRoles'])->name('users.roles.edit')->middleware('auth');

    Route::put('users/{user}/roles/update', [UsersController::class, 'updateRoles'])->name('users.roles.update')->middleware('auth');

    Route::resource('users', UsersController::class)->middleware('auth')->except(['show', 'edit']);

    Route::get('users/edit-profile', [UsersController::class, 'edit'])->middleware('auth')
        ->name('users.edit');

    // End Employees

    // Admins
    // Route::resource('admins', AdminsController::class)->middleware('auth');
    // End Admins

    // Properties
    Route::get('properties/{user}/details', [PropertiesController::class, 'show_user_properties'])->name('properties.user')->middleware('auth');

    Route::get('/properties/trash', [PropertiesController::class, 'trash'])->name('properties.trash')->middleware('auth');

    Route::delete('/properties/{property}/force-delete', [PropertiesController::class, 'force_delete'])->name('properties.force_delete')->middleware('auth');

    Route::get('/properties/{property}/restore', [PropertiesController::class, 'restore'])->name('properties.restore')->middleware('auth');

    Route::resource('properties', PropertiesController::class)->middleware('auth');
    // End Properties

    // Donors
    Route::get('/donors/trash', [DonorsController::class, 'trash'])->name('donors.trash')->middleware('auth');

    Route::get('/donors/{donor}/restore', [DonorsController::class, 'restore'])->name('donors.restore')->middleware('auth');

    Route::delete('/donors/{donor}/force-delete', [DonorsController::class, 'force_delete'])->name('donors.force_delete')->middleware('auth');

    Route::resource('donors', DonorsController::class)->middleware('auth')->except('show');
    // End Donors

    // Queries
    Route::get('/queries/users', [QueriesController::class, 'users'])->name('queries.users')->middleware('auth');

    Route::get('/queries/donors', [QueriesController::class, 'donors'])->name('queries.donors')->middleware('auth');
    // End  Queries

    // Settings
    // Route::get('users/{user}/edit', [SettingsController::class, 'edit_profile'])->name('users.editProfile')->middleware('auth');

    Route::put('users/{user}/edit-profile', [SettingsController::class, 'edit_profile_check'])->name('users.editProfileCheck')->middleware('auth');

    Route::get('users/reset-password', [SettingsController::class, 'reset_password'])->name('users.reset_password')->middleware('auth');

    Route::post('users/reset-password', [SettingsController::class, 'reset_password_check'])->name('users.reset_password_check')->middleware('auth');

    Route::get('users/reset-password-to-user', [SettingsController::class, 'reset_password_to_employee'])->name('users.reset_password_to_employee')->middleware('auth');

    Route::post('users/reset-password-to-user', [SettingsController::class, 'verify_reset_password_to_employee'])->name('users.reset_password_to_employee_verify')->middleware('auth');
    // End Settings

    // Notifications
    Route::patch('/notifications/{notification}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.read.all');
    // End Notifications

    // Attachments
    // Route::get('/attachments/{id}/download', [EmployeeAttachmentsController::class, 'download'])->name('attachments.download')->middleware('auth');

    // Route::get('users/{user}/attachments', [EmployeeAttachmentsController::class, 'index'])->name('user.attachments.index')->middleware('auth');

    // Route::get('users/{user}/attachments/create', [EmployeeAttachmentsController::class, 'create'])->name('user.attachments.create')->middleware('auth');

    // Route::post('/users/{id}/attachments', [EmployeeAttachmentsController::class, 'store'])->name('user.attachments.store')->middleware('auth');

    // Route::get('/attachments/{identity_number}/{file_name}', [EmployeeAttachmentsController::class, 'open_file'])->name('attachments.open')->middleware('auth');



    // Route::delete('/attachments/{id}', [EmployeeAttachmentsController::class, 'destroy'])->name('attachments.destroy')
    //     ->middleware('auth');
    // End Attachments

    // Layour
    Route::get('{page?}', [AdminController::class, 'redirect'])->middleware('auth')->name('dashboard');
    // End Layout
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

<?php

/*
|--------------------------------------------------------------------------
| Kiosk Routes
|--------------------------------------------------------------------------
|
| Here is where you can register kiosk routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Domains\Activity\Http\Controllers\OverviewController;
use App\Domains\Announcements\Http\Controllers\ManagementController;
use App\Domains\Announcements\Http\Controllers\StatusController;
use App\Domains\Api\Http\Controllers\Web\TokenRestoreController;
use App\Domains\Api\Http\Controllers\Web\UserTokensController;
use App\Domains\Roles\Http\Controllers\RoleController;
use App\Domains\Users\Http\Controllers\LockController;
use App\Domains\Users\Http\Controllers\RestoreController;
use App\Domains\Users\Http\Controllers\UsersController;
use App\Http\Controllers\KioskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('spoon.kiosk_prefix'), 'as' => config('spoon.kiosk_prefix') . '.'], static function (): void {
    Route::get('/dashboard', KioskController::class)->name('dashboard');

    // User management routes
    Route::post('/user-create', [UsersController::class, 'store'])->name('users.store');
    Route::get('/user-create', [UsersController::class, 'create'])->name('users.create');
    Route::get('/users/{filter?}', [UsersController::class, 'index'])->name('users.index');
    Route::get('/user/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/user/{user}/edit', [UsersController::class, 'edit'])->name('users.update');
    Route::patch('/user/{user}/edit', [UsersController::class, 'update'])->name('users.update');
    Route::get('/user/{user}/restore', RestoreController::class)->name('users.restore');
    Route::match(['get', 'delete'], '/kiosk/{user}/delete', [UsersController::class, 'destroy'])->name('users.destroy');

    // User lock routes
    Route::get('/user-deactivated', [LockController::class, 'index'])->name('users.lock.error');
    Route::get('/user/{userEntity}/lock', [LockController::class, 'create'])->name('users.lock');
    Route::post('/user/{userEntity}/lock', [LockController::class, 'store'])->name('users.lock');
    Route::get('/user/{userEntity}/unlock', [LockController::class, 'destroy'])->name('users.unlock');

    // Role management routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/create', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::patch('/roles/{role}/edit', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/roles/{role}/remove', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Activity logs routes
    Route::get('/logs/{filter?}', OverviewController::class)->name('activity.index');

    // API personal access tokens routes
    if (config('spoon.modules.api-tokens')) {
        Route::get('/api-management/{filter?}', [UserTokensController::class, 'index'])->name('api-management.index');
        Route::get('/api-token/{trashedToken}/restore', TokenRestoreController::class)->name('api-management.restore');
    }

    if (config('spoon.modules.announcements')) {
        Route::get('/announcements', [ManagementController::class, 'index'])->name('announcements.overview');
        Route::get('/announcement/create', [ManagementController::class, 'create'])->name('announcements.create');
        Route::post('/announcement/create', [ManagementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}', [ManagementController::class, 'show'])->name('announcements.show');
        Route::get('/announcements/{announcement}/enabled', [StatusController::class, 'enable'])->name('announcements.enable');
        Route::get('/announcements/{announcement}/disable', [StatusController::class, 'disable'])->name('announcements.disable');
        Route::get('/announcements/{announcement}/remove', [ManagementController::class, 'destroy'])->name('announcements.delete');
    }
});

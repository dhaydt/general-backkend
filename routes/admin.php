<?php

use App\Http\Controllers\admin\auth\AdminController;
use App\Http\Controllers\admin\auth\LoginAdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.auth.adminLogin');
    });

    // Authentication
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/login', [LoginAdminController::class, 'loginAdmin'])->name('adminLogin');
        Route::post('/login', [LoginAdminController::class, 'submit']);
        Route::get('/logout', [LoginAdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/user_admin', [UserController::class, 'index'])->name('userAdmin');
        Route::post('/add_admin', [AdminController::class, 'index'])->name('userAdminAdd');

        Route::get('/profile', [UserController::class, 'profile'])->name('adminProfile');
        Route::put('/update_adminInfo', [UserController::class, 'adminInfo'])->name('adminInfo');
        Route::put('/update_adminPass', [UserController::class, 'adminPass'])->name('adminPass');
        Route::post('/update_adminPict', [UserController::class, 'adminPict'])->name('adminPict');
    });
});

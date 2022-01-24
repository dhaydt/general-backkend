<?php

use App\Http\Controllers\admin\auth\AdminController;
use App\Http\Controllers\admin\auth\LoginAdminController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\BussinessSettingsController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\SystemController;
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

        // System Route
        Route::get('maintenance-mode', [SystemController::class, 'maintenance_mode'])->name('maintenance-mode');

        Route::get('/profile', [UserController::class, 'profile'])->name('adminProfile');
        Route::put('/update_adminInfo', [UserController::class, 'adminInfo'])->name('adminInfo');
        Route::put('/update_adminPass', [UserController::class, 'adminPass'])->name('adminPass');
        Route::post('/update_adminPict', [UserController::class, 'adminPict'])->name('adminPict');

        Route::get('user_customer', [UserController::class, 'customerList'])->name('userCustomer');
        Route::get('user_customer/{id}', [UserController::class, 'customerView'])->name('userCustomerView');

        // Product
        Route::get('product_list', [ProductController::class, 'index'])->name('listProduct');
        Route::get('product_view/{id}', [ProductController::class, 'view'])->name('viewProduct');
        Route::get('product_add', [ProductController::class, 'add_new'])->name('addProduct');
        Route::post('product_store', [ProductController::class, 'store'])->name('storeProduct');
        Route::post('product_sku', [ProductController::class, 'skuCombination'])->name('sku-combination');
        Route::get('product_edit/{id}', [ProductController::class, 'edit'])->name('editProduct');
        Route::post('product_update/{id}', [ProductController::class, 'update'])->name('updateProduct');
        Route::post('status_update', [ProductController::class, 'status_update'])->name('statusUpdateProduct');
        Route::get('product_delete/{id}', [ProductController::class, 'delete'])->name('delProduct');

        // Service
        Route::get('service_list', [ServiceController::class, 'index'])->name('listService');
        Route::get('service_add', [ServiceController::class, 'add'])->name('addService');
        Route::post('service_store', [ServiceController::class, 'store'])->name('storeService');
        Route::get('service_edit/{id}', [ServiceController::class, 'edit'])->name('editService');
        Route::post('service_update/{id}', [ServiceController::class, 'update'])->name('updateService');
        Route::post('service_update_status', [ServiceController::class, 'updateStatus'])->name('updateServiceStatus');
        Route::get('service_delete/{id}', [ServiceController::class, 'delete'])->name('deleteService');

        // Bussiness settings
        Route::prefix('web-config')->name('web-config.')->group(function () {
            Route::get('/', [BussinessSettingsController::class, 'companyInfo'])->name('index');
            Route::post('/update-colors', [BussinessSettingsController::class, 'update_colors']);
            Route::post('/update-company', [BussinessSettingsController::class, 'updateCompany'])->name('company-update');
            Route::post('/update-company-email', [BussinessSettingsController::class, 'updateCompanyEmail'])->name('company-email-update');
            Route::post('/update-company-phone', [BussinessSettingsController::class, 'updateCompanyPhone'])->name('company-phone-update');
            Route::post('/update-web-logo', [BussinessSettingsController::class, 'uploadWebLogo'])->name('company-web-logo-update');
            Route::post('/update-mobile-logo', [BussinessSettingsController::class, 'uploadMobileLogo'])->name('company-mobile-logo-update');
            Route::post('/update-footer-logo', [BussinessSettingsController::class, 'uploadFooterLogo'])->name('company-footer-logo-update');
            Route::post('/update-fav-icon', [BussinessSettingsController::class, 'uploadFavIcon'])->name('company-fav-icon');
            Route::post('/update-info', [BussinessSettingsController::class, 'updateInfo'])->name('update-info');
        });

        // Banner
        Route::prefix('banner')->name('banner.')->group(function () {
            Route::get('/list', [BannerController::class, 'list'])->name('list');
            Route::post('/add-new', [BannerController::class, 'store'])->name('store');
            Route::post('/delete', [BannerController::class, 'delete'])->name('delete');
            Route::post('/status', [BannerController::class, 'status'])->name('status');
            Route::post('/edit', [BannerController::class, 'edit'])->name('edit');
            Route::post('/update', [BannerController::class, 'update'])->name('update');
        });
    });
});

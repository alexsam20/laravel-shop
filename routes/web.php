<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::match(['get', 'post'],'login', [AdminController::class, 'login'])->name('login');
    Route::group(['middleware' => ['admin']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::match(['get', 'post'],'update-password', [AdminController::class, 'updatePassword'])->name('update-password');
        Route::match(['get', 'post'],'update-details', [AdminController::class, 'updateDetails'])->name('update-details');
        Route::post('check-current-password', [AdminController::class, 'checkCurrentPassword'])->name('check-current-password');
        Route::get('logout', [AdminController::class, 'logout'])->name('logout');

        // Display CMS Pages
        Route::get('cms-pages', [CmsController::class, 'index'])->name('cms-pages');
        Route::post('update-cms-page-status', [CmsController::class, 'update'])->name('update-cms-page-status');
        Route::match(['get', 'post'],'add-edit-cms-page/{id?}', [CmsController::class, 'edit'])->name('add-edit-cms-page');
        Route::get('delete-cms-page/{id?}', [CmsController::class, 'destroy'])->name('delete-cms-pages');

        // Subadmins
        Route::get('subadmins', [AdminController::class, 'subadmins'])->name('subadmins');
        Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus'])->name('update-subadmin-status');
        Route::match(['get', 'post'],'add-edit-subadmin/{id?}', [AdminController::class, 'addEditSubadmin'])->name('add-edit-subadmin');
        Route::get('delete-subadmin/{id?}', [AdminController::class, 'deleteSubadmin'])->name('delete-subadmin');
        Route::match(['get', 'post'],'update-role/{id}', [AdminController::class, 'updateRole'])->name('update-role');

        // Categories
        Route::get('categories', [CategoryController::class, 'categories'])->name('categories');
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus'])->name('update-category-status');
        Route::get('delete-category/{id?}', [CategoryController::class, 'deleteCategory'])->name('delete-category');
        Route::get('delete-category-image/{id?}', [CategoryController::class, 'deleteCategoryImage'])->name('delete-category-image');
        Route::match(['get','post'],'add-edit-category/{id?}', [CategoryController::class, 'addEditCategory'])->name('add-edit-category');

        // Products
        Route::get('products', [ProductsController::class, 'products'])->name('products');
        Route::post('update-product-status', [ProductsController::class, 'updateProductStatus'])->name('update-product-status');
        Route::get('delete-product/{id?}', [ProductsController::class, 'deleteProduct'])->name('delete-product');
    });

});



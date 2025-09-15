<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CmsController;
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
        Route::match(['get', 'post'],'add-edit-cms-page', [CmsController::class, 'edit'])->name('add-edit-cms-page');
    });

});



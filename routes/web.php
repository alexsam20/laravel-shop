<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::match(['get', 'post'],'login', [AdminController::class, 'login'])->name('login');
    Route::group(['middleware' => ['admin']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('update-password', [AdminController::class, 'updatePassword'])->name('update-password');
        Route::post('check-current-password', [AdminController::class, 'checkCurrentPassword'])->name('check-current-password');
        Route::get('logout', [AdminController::class, 'logout'])->name('logout');
    });

});



<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CodeCheckController;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-notifications', function ($user) {
    return $user->isAdmin(); // Define your admin check here
});

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('email/verify', [AuthController::class, 'verifyEmail']);
Route::post('password/email', [ForgotPasswordController::class, '__invoke']);
Route::post('password/code/check', [CodeCheckController::class, '__invoke']);
Route::post('password/reset', [ResetPasswordController::class, '__invoke']);

Route::post('logout', [AuthController::class, 'logout']);

// User routes (CRUD for items)
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('items', [ItemController::class, 'store']);
    Route::get('items', [ItemController::class, 'index']);
    Route::put('items/{id}', [ItemController::class, 'update']);
    Route::delete('items/{id}', [ItemController::class, 'destroy']);
});

// Admin routes
Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::get('admin/items/unapproved', [AdminController::class, 'getUnapprovedItems']);
    Route::put('admin/items/{id}/approve', [AdminController::class, 'approveItem']);
    Route::put('admin/items/{id}/reject', [AdminController::class, 'rejectItem']);
    Route::delete('admin/items/{id}', [AdminController::class, 'deleteItem']);
});

// Fix for password.reset route
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
//Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::middleware('api')->post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('email/verify', [AuthController::class, 'verifyEmail']);
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

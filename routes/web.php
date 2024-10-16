<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('reset-code-view/{code}', [ResetPasswordController::class, 'showResetCodeView']);

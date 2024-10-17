<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

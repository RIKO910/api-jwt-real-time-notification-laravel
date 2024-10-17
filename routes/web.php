<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Events\MyEvent;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/pusher1','pusher1');

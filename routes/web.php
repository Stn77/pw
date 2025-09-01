<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'loginSubmit')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});


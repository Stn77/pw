<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\HomeDashboard;
use App\Livewire\Dashboard\{AkunSiswa, DaftarSiswa, RiwayatAbsen};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login/submit', 'loginSubmit')->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::controller(HomeDashboard::class)->middleware('auth')->group(function () {
    Route::get('/home', 'index')->name('home');
});

Route::get('/scanner', \App\Livewire\Scanner\Scanner::class)->middleware('auth')->middleware('role:scanner|admin')->name('scanner');
// Route::get('/scan', fn() => 'mbut')->middleware('auth')->middleware('role:scanner');

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('profile.index');
    })->name('profile.index');
    Route::get('/password-change', function () {
        return view('profile.password_change');
    })->name('profile.password.change');
});

Route::prefix('data')->middleware('auth')->group(function(){
    Route::get('/students', DaftarSiswa::class)->name('data.students');
    // Route::get('/teachers', DaftarGuru::class)->name('data.teachers');
    Route::get('/students/account', AkunSiswa::class)->name('data.students.account');
    Route::get('/absen', RiwayatAbsen::class)->name('data.absen');
});

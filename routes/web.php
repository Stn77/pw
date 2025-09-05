<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\HomeDashboard;
use App\Http\Controllers\Data\{AkunSiswa, RiwayatAbsen};
use App\Http\Controllers\Scanner\{Scanner};
use Illuminate\Support\Facades\Route;

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

// Route::get('/scanner', \App\Livewire\Scanner\Scanner::class)->middleware('auth')->middleware('role:scanner|admin')->name('scanner');
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
    Route::get('/students/account', [AkunSiswa::class, 'index'])->name('data.students.account');
    Route::get('/absen', [RiwayatAbsen::class, 'index'])->name('data.absen');
});
Route::get('/scanner', [Scanner::class, 'index'])->name('scanner');
Route::post('/scanner/scan', [Scanner::class, 'scan'])->name('scanner.scan');

Route::get('/students/get', [AkunSiswa::class, 'getData'])->name('akun.siswa.get');
Route::get('/absen/get', [RiwayatAbsen::class, 'getData'])->name('data.absen.get');
// Route::post('');

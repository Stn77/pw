<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Dashboard\HomeDashboard;
use App\Http\Controllers\Data\{AkunSiswa, Guru, QrGenerator, RiwayatAbsen};
use App\Http\Controllers\Scanner\{Scanner};
use App\Http\Controllers\TestAlgorithm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestAlgorithm::class, 'index'])->name('test.algorithm');
Route::get('/test-view', function () {
    return view('test-view');
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
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/password-change', function () {
        return view('profile.password_change');
    })->name('profile.password.change');
});

Route::prefix('data')->middleware('auth')->group(function(){
    Route::get('/students/account', [AkunSiswa::class, 'index'])->name('data.students.account');
    Route::get('/absen', [RiwayatAbsen::class, 'index'])->name('data.absen');
});

Route::prefix('data/guru')->middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/', [Guru::class, 'index'])->name('data.guru.index');
    Route::get('/create', [Guru::class, 'create'])->name('data.guru.create');
    Route::get('/edit/{id}', [Guru::class, 'edit'])->name('data.guru.edit');
    Route::post('/store', [Guru::class, 'store'])->name('data.guru.store');
    Route::get('/get', [Guru::class, 'getData'])->name('data.guru.get');
});

Route::middleware('auth')->group(function (){
    Route::get('/scanner', [Scanner::class, 'index'])->name('scanner');
    Route::post('/scanner/scan', [Scanner::class, 'scan'])->name('scanner.scan');
    Route::get('/qrcode', [QrGenerator::class, 'generate'])->name('generate.qr');
});

Route::post('/students/store', [AkunSiswa::class, 'store'])->name('akun.siswa.store');

Route::get('/students/get', [AkunSiswa::class, 'getData'])->name('akun.siswa.get');
Route::get('/absen/get', [RiwayatAbsen::class, 'getData'])->name('data.absen.get');
// Route::post('');

// Route::get('/test-broadcast', fn() => view('test-broadcast'));

// Route::get('/trigger-event', function () {
//     event(new \App\Events\TestingEvent());
//     return 'Event has been sent!';
// });

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

Route::prefix('absen')->middleware('auth')->group(function(){
    Route::get('/', [RiwayatAbsen::class, 'index'])->name('data.absen');
    Route::get('/get', [RiwayatAbsen::class, 'getData'])->name('data.absen.get');
});

Route::prefix('data/guru')->middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/', [Guru::class, 'index'])->name('data.guru.index');
    Route::get('/get', [Guru::class, 'getData'])->name('data.guru.get');
    Route::post('/create', [Guru::class, 'create'])->name('data.guru.create');
    Route::get('/edit/{id}', [Guru::class, 'edit'])->name('data.guru.edit');
    Route::post('/update', [Guru::class, 'update'])->name('data.guru.update');
    Route::delete('/delete/{id}', [Guru::class, 'delete'])->name('data.guru.delete');

    Route::get('/get-data/{id}', [Guru::class, 'getSingleData'])->name('data.guru.sigle');
    Route::post('/store', [Guru::class, 'store'])->name('data.guru.store');
    Route::post('/import', [Guru::class, 'import'])->name('data.guru.import');
    Route::get('/template', [Guru::class, 'getTemplate'])->name('data.guru.template');
    Route::post('/kelas/update', [Guru::class, 'setClass'])->name('data.guru.setClass');
});
Route::get('/kelas/{id}', [Guru::class, 'getClass'])->name('data.guru.class');

Route::prefix('data/siswa')->middleware(['auth', 'role:admin|guru'])->group(function(){
    Route::get('/', [AkunSiswa::class, 'index'])->name('data.siswa.index');
    Route::get('/get', [AkunSiswa::class, 'getData'])->name('akun.siswa.get');
    Route::get('/edit/{id}', [AkunSiswa::class, 'edit'])->name('data.siswa.edit');
    Route::post('/update', [AkunSiswa::class, 'update'])->name('data.siswa.update');
    Route::delete('/delete/{id}',[AkunSiswa::class, 'delete'])->name('data.siswa.delete');

    Route::get('/get-data/{id}', [AkunSiswa::class, 'getSingleData'])->name('data.siswa.sigle');
    Route::post('/store', [AkunSiswa::class, 'store'])->name('akun.siswa.store');
    Route::post('/import', [AkunSiswa::class, 'import'])->name('data.siswa.import');
    Route::get('/template', [AkunSiswa::class, 'getTemplate'])->name('data.siswa.template');
});

Route::middleware('auth')->group(function (){
    Route::get('/scanner', [Scanner::class, 'index'])->name('scanner');
    Route::post('/scanner/scan', [Scanner::class, 'scan'])->name('scanner.scan');
    Route::get('/qrcode', [QrGenerator::class, 'generate'])->name('generate.qr');
});

Route::get('/export/absen/excel', [RiwayatAbsen::class, 'riwayatAbsenExcel'])->name('export.absen.excel');

Route::get('/export/absen/pdf', [RiwayatAbsen::class, 'exportPdf'])->name('export.absen.pdf');


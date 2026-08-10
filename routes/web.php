<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\JadwalPegawaiController;
use App\Http\Controllers\DokumenPegawaiController;
use App\Http\Controllers\PengajuanIzinCutiController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('MainPage');
})->name('MainPage');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//route untuk function kustom dalam controller
Route::put('/pengajuan-izin/{pengajuan}/persetujuan', [PengajuanIzinCutiController::class, 'persetujuan'])->name('pengajuan-izin.persetujuan');

Route::resource('pegawai', UserController::class);
Route::resource('shift', ShiftController::class);
Route::resource('jadwal', JadwalPegawaiController::class);
Route::resource('dokumen', DokumenPegawaiController::class);
Route::resource('pengajuan-izin', PengajuanIzinCutiController::class);

// harus login dulu
Route::middleware(['auth'])->group(function () {
    // Rute yang hanya boleh diakses Manager
    Route::middleware(['manager'])->group(function () {

    });

    Route::middleware(['pegawai'])->group(function () {

    });

});


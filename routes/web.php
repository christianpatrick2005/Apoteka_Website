<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\JadwalPegawaiController;
use App\Http\Controllers\DokumenPegawaiController;
use App\Http\Controllers\PengajuanIzinCutiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('MainPage');
})->name('MainPage');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// HARUS LOGIN DULU
Route::middleware(['auth'])->group(function () {
    
    Route::put('/pengajuan-izin/{pengajuan}/persetujuan-pengganti', [PengajuanIzinCutiController::class, 'persetujuanPengganti'])->name('pengajuan-izin.persetujuan-pengganti');
    Route::get('/pengajuan-izin/{pengajuan}/persetujuan', [PengajuanIzinCutiController::class, 'showPersetujuanForm'])->name('pengajuan-izin.show-persetujuan');
    Route::put('/pengajuan-izin/{pengajuan}/persetujuan', [PengajuanIzinCutiController::class, 'persetujuan'])->name('pengajuan-izin.persetujuan');

    // Rute resource pengajuan izin diletakkan di luar grup manajer/pegawai
    Route::resource('pengajuan-izin', PengajuanIzinCutiController::class)->parameters(['pengajuan-izin' => 'pengajuanIzinCuti']);

    Route::middleware(['manajer'])->group(function () {
        Route::resource('pegawai', UserController::class)->parameters(['pegawai' => 'user']);
        Route::resource('shift', ShiftController::class);
        Route::resource('jadwal', JadwalPegawaiController::class)->parameters(['jadwal' => 'jadwalPegawai']);
        Route::resource('dokumen', DokumenPegawaiController::class)->parameters(['dokumen' => 'dokumenPegawai']);
        Route::get('/laporan/sisa-cuti', [LaporanController::class, 'sisaCuti'])->name('laporan.sisa-cuti');
        Route::get('/laporan/riwayat-cuti', [LaporanController::class, 'riwayatCuti'])->name('laporan.riwayat-cuti');
        Route::post('/jadwal/import', [JadwalPegawaiController::class, 'importExcel'])->name('jadwal.import');
    });


    Route::middleware(['pegawai'])->group(function () {
        // Kosongkan sementara jika belum ada halaman yang murni HANYA untuk pegawai
    });

});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\JadwalPegawaiController;
use App\Http\Controllers\DokumenPegawaiController;
use App\Http\Controllers\PengajuanIzinCutiController;

Route::get('/', function () {
    return view('MainPage');
})->name('MainPage');

Route::get('/login', function () {
    return view('login');
})->name('login');

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
        Route::get('/manage-data/pegawai', function () {
        return view('ManagePegawai');
        })->name('ManagePegawai');

        Route::get('/manage-data/pegawai/form', function () {
            return view('forms.FormPegawai');
        })->name('FormPegawai');

        Route::get('/manage-data/dokumen', function () {
            return view('ManageDokumen');
        })->name('ManageDokumen');

        Route::get('/manage-data/dokumen/form', function () {
            return view('forms.FormDokumen');
        })->name('FormDokumen');

        Route::get('/manage-data/shift', function () {
            return view('ManageShift');
        })->name('ManageShift');

        Route::get('/manage-data/shift/form', function () {
            return view('forms.FormShift');
        })->name('FormShift');
    });

    Route::middleware(['pegawai'])->group(function () {
        Route::get('/Pengajuan-Cuti/form', function () {
            return view('forms.FormCuti');
        })->name('FormCuti');
    });

});

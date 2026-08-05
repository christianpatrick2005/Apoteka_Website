<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PengajuanIzinCutiController;
use App\Http\Controllers\Api\UserController;

// URL: http://apoteka.test/api/pengajuan-izincuti
Route::apiResource('pengajuan-izincuti', PengajuanIzinCutiController::class);

// URL: http://apoteka.test/api/users
Route::apiResource('users',UserController::class);

Route::apiResource('dokumen-pegawai',DokumenPegawaiController::class);

Route::apiResource('shifts',ShiftController::class);

Route::apiResource('jadwal-pegawai',JadwalPegawaiController::class);

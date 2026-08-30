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

Route::post('/update-onesignal-id', function (\Illuminate\Http\Request $request) {
    if (auth()->check()) {
        auth()->user()->update(['onesignal_id' => $request->onesignal_id]);
        return response()->json(['status' => 'success']);
    }
    return response()->json(['status' => 'unauthorized'], 401);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// HARUS LOGIN DULU
Route::middleware(['auth'])->group(function () {
    
    Route::put('/pengajuan-izin/{pengajuan}/persetujuan-pengganti', [PengajuanIzinCutiController::class, 'persetujuanPengganti'])->name('pengajuan-izin.persetujuan-pengganti');
    Route::get('/pengajuan-izin/{pengajuan}/persetujuan', [PengajuanIzinCutiController::class, 'showPersetujuanForm'])->name('pengajuan-izin.show-persetujuan');
    Route::put('/pengajuan-izin/{pengajuan}/persetujuan', [PengajuanIzinCutiController::class, 'persetujuan'])->name('pengajuan-izin.persetujuan');

    // Rute resource pengajuan izin diletakkan di luar grup manajer/pegawai
    Route::resource('pengajuan-izin', PengajuanIzinCutiController::class)->parameters(['pengajuan-izin' => 'pengajuanIzinCuti']);

    Route::middleware(['pegawai'])->group(function () {
        // Pegawai bisa melihat profil, jadwal, dan sisa cutinya sendiri
        Route::get('/profil-saya', [UserController::class, 'profilSaya'])->name('pegawai.profil-saya');
    });
    
    Route::middleware(['manajer'])->group(function () {
        Route::delete('/jadwal/destroy-all/{user_id}', [JadwalPegawaiController::class, 'destroyAll'])->name('jadwal.destroyAll');
        Route::get('/jadwal-pegawai-template/download', [JadwalPegawaiController::class, 'downloadTemplateJadwal'])->name('jadwal-pegawai.template.download');
        Route::resource('pegawai', UserController::class)->parameters(['pegawai' => 'user']);
        Route::resource('shift', ShiftController::class);
        Route::resource('jadwal', JadwalPegawaiController::class)->parameters(['jadwal' => 'jadwalPegawai']);
        Route::resource('dokumen', DokumenPegawaiController::class)->parameters(['dokumen' => 'dokumenPegawai']);
        Route::get('/laporan/sisa-cuti', [LaporanController::class, 'sisaCuti'])->name('laporan.sisa-cuti');
        Route::get('/laporan/riwayat-cuti', [LaporanController::class, 'riwayatCuti'])->name('laporan.riwayat-cuti');
        Route::get('/laporan/riwayat-cuti/pdf',[LaporanController::class, 'riwayatCutiPdf'])->name('laporan.riwayat-cuti.pdf');
        Route::get('/laporan/sisa-cuti/pdf',[LaporanController::class, 'sisaCutiPdf'])->name('laporan.sisa-cuti.pdf');

        Route::post('/jadwal/import', [JadwalPegawaiController::class, 'importExcel'])->name('jadwal.import');
    });

});
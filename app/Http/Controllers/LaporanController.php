<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanIzinCuti;
use Illuminate\Http\Request;

class LaporanController
{
    public function sisaCuti()
    {
        // Hanya ambil data akun yang jabatannya 'pegawai'
        // Kita tidak butuh jadwal/dokumen di sini, jadi query-nya sangat ringan
        $dataPegawai = User::where('role', 'pegawai')
            ->orderBy('name', 'asc')
            ->get();

        return view('Laporan.LaporanSisaCuti', compact('dataPegawai'));
    }

    // FUNCTION BARU UNTUK HISTORY/RIWAYAT
    public function riwayatCuti(Request $request)
    {
        // Ambil semua data riwayat, urutkan dari yang terbaru
        // Gunakan with() agar query database tidak lambat (mencegah N+1 issue)
        $query = PengajuanIzinCuti::with(['user', 'userPengganti'])->latest('tanggal_pengajuan');

        // (Opsional) Jika Anda ingin menambahkan fitur filter bulan/tahun nanti, 
        // logikanya bisa ditambahkan di sini.

        $riwayat = $query->get();

        return view('Laporan.LaporanRiwayatCuti', compact('riwayat'));
    }
}

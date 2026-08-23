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

        // 1. Filter Berdasarkan Nama Pegawai
        if ($request->filled('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                // Menggunakan 'like' agar pencarian tidak harus sama persis (bisa sebagian huruf)
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // 2. Filter Berdasarkan Kategori (izin / cuti)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Berdasarkan Periode Waktu (Tanggal Pengajuan)
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_pengajuan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } elseif ($request->filled('tanggal_mulai')) {
            // Jika hanya tanggal mulai yang diisi
            $query->where('tanggal_pengajuan', '>=', $request->tanggal_mulai);
        } elseif ($request->filled('tanggal_selesai')) {
            // Jika hanya tanggal selesai yang diisi
            $query->where('tanggal_pengajuan', '<=', $request->tanggal_selesai);
        }

        // Eksekusi query
        $riwayat = $query->get();

        return view('Laporan.LaporanRiwayatCuti', compact('riwayat'));
    }
}

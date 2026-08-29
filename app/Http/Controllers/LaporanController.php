<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanIzinCuti;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function riwayatCutiPdf(Request $request)
    {
        $query = PengajuanIzinCuti::with([
            'user',
            'userPengganti'
        ])->latest('tanggal_pengajuan');
        // FILTER NAMA
        if ($request->filled('nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where(
                    'name',
                    'like',
                    '%' . $request->nama . '%'
                );
            });
        }

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where(
                'kategori',
                $request->kategori
            );
        }

        // FILTER TANGGAL
        if (
            $request->filled('tanggal_mulai') &&
            $request->filled('tanggal_selesai')
        ) {
            $query->whereBetween(
                'tanggal_pengajuan',
                [
                    $request->tanggal_mulai,
                    $request->tanggal_selesai
                ]
            );
        }
        elseif ($request->filled('tanggal_mulai')) {
            $query->where(
                'tanggal_pengajuan',
                '>=',
                $request->tanggal_mulai
            );
        }
        elseif ($request->filled('tanggal_selesai')) {
            $query->where(
                'tanggal_pengajuan',
                '<=',
                $request->tanggal_selesai
            );
        }

        $riwayat = $query->get();

        // BUAT PDF
        $pdf = Pdf::loadView(
            'Laporan.LaporanRiwayatCutiPdf',
            compact('riwayat')
        );

        // A4 LANDSCAPE
        $pdf->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->stream(
            'laporan-riwayat-cuti.pdf'
        );
    }

    public function sisaCutiPdf(Request $request)
    {
        // Ambil data pegawai dengan EAGER LOADING untuk efisiensi
        $dataPegawai = User::where('role', 'pegawai')
            ->orderBy('name', 'asc')
            ->get();

        $pdf = Pdf::loadView('Laporan.LaporanSisaCutiPdf', compact('dataPegawai'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-sisa-cuti.pdf');
    }
}

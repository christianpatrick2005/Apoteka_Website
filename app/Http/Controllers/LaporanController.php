<?php

namespace App\Http\Controllers;

use App\Models\User;
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
}

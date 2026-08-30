<?php

namespace App\Exports;

use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Export;

class TemplateJadwalExport implements WithMultipleSheets, Export
{
    protected Carbon $startDate;
    protected Carbon $endDate;

    public function __construct($startDate, $endDate)
    {
        // Terima string ('2026-09-01') maupun instance Carbon
        $this->startDate = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $this->endDate   = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);
    }

    public function sheets(): array
    {
        // Data pegawai (sesuaikan query dgn kebutuhan Anda)
        $pegawai = User::whereIn('role', ['pegawai', 'manajer'])->get();

        // Data shift dari database. Sengaja tidak pakai select() kolom spesifik
        // supaya tidak error jika tabel 'shifts' Anda belum punya kolom
        // 'deskripsi' / 'toleransi' (model Shift saat ini hanya fillable utk
        // nama_shift, jam_masuk, jam_keluar). Sheet akan menampilkan kosong/0
        // untuk kolom yg tidak ada, lihat catatan di DaftarShiftSheet & ShiftOptionsSheet.
        $shifts = Shift::all();

        return [
            // Sheet 1: Template jadwal utama (yg diisi user)
            new JadwalShiftKaryawanSheet($pegawai, $shifts, $this->startDate, $this->endDate),

            // Sheet 2: Daftar Shift, referensi visual bagi user
            new DaftarShiftSheet($shifts, $this->startDate, $this->endDate, $pegawai->count()),

            // Sheet 3 (hidden): sumber data validation dropdown
            new ShiftOptionsSheet($shifts),
        ];
    }
}

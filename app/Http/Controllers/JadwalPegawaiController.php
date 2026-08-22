<?php

namespace App\Http\Controllers;

use App\Models\JadwalPegawai;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;

class JadwalPegawaiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = JadwalPegawai::with(['user','shift'])->get();
        return view('ManageShift', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('forms.FormShift');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari mobile
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        JadwalPegawai::create($request->only(['user_id','shift_id','tanggal']));

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalPegawai $jadwalPegawai)
    {
        $jadwalPegawai->load('user','shift');

        return view('ManageShift', compact('jadwalPegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalPegawai $jadwalPegawai)
    {
        return view('forms.FormShift', compact('jadwalPegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalPegawai $jadwalPegawai)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'shift_id' => 'sometimes|exists:shifts,id',
            'tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        $jadwalPegawai->update($request->only(['user_id','shift_id','tanggal']));

        return back()->with('success', 'Data jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPegawai $jadwalPegawai)
    {
        // Hapus data dari MySQL
        $jadwalPegawai->delete();

        return back()->with('success', 'Data dan file jadwal pegawai berhasil dihapus secara permanen');
    }

    public function importExcel(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // 2. Baca file Excel menggunakan Spatie
            // $rows = SimpleExcelReader::create($request->file('file_excel')->path())->getRows();
            
            // 2. Baca file Excel menggunakan Spatie (dengan memberitahu ekstensi aslinya)
            $file = $request->file('file_excel');
            $rows = SimpleExcelReader::create($file->path(), $file->getClientOriginalExtension())->getRows();

            // 3. Looping setiap baris di Excel
            $rows->each(function(array $row) {
                // Sekarang kita mengecek berdasarkan kolom 'nama_pegawai' dan nama shift
                if (empty($row['nama_pegawai']) || empty($row['nama_shift'])) return;

                // Cari data pegawai dan shift berdasarkan namanya
                $pegawai = User::where('name', $row['nama_pegawai'])->first();
                $shift = Shift::where('nama_shift', $row['nama_shift'])->first();

                // Jika nama tidak ditemukan di database, lewati baris ini agar tidak error
                if (!$pegawai || !$shift) return;

                // Spatie sangat pintar: jika sel Excel diformat sebagai tanggal, 
                // ia otomatis merubahnya menjadi objek DateTime. Jika tidak, kita parse manual.
                $tanggal = $row['tanggal'] instanceof \DateTimeInterface 
                    ? $row['tanggal']->format('Y-m-d') 
                    : Carbon::parse($row['tanggal'])->format('Y-m-d');

                //4. Masukkan ke database
                \App\Models\JadwalPegawai::create([
                    'user_id'  => $pegawai->id, // Ambil ID dari hasil pencarian nama
                    'shift_id' => $shift->id,
                    'tanggal'  => $tanggal,
                ]);
            });

            return back()->with('success', 'Jadwal shift sebulan berhasil ditambahkan');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

}

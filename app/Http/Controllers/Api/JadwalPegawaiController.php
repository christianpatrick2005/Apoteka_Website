<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalPegawai;
use Illuminate\Support\Facades\Storage;

class JadwalPegawaiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database MySQL (termasuk relasinya)
        $data = JadwalPegawai::with(['user','shift'])->get();

        // 2. Kembalikan respons dalam struktur JSON yang rapi
        return response()->json([
            'status' => 'success',
            'pesan' => 'Data jadwal pegawai berhasil dimuat',
            'data' => $data
        ], 200);
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
            'tanggal_kerja' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = JadwalPegawai::create($request->only(['user_id','shift_id','tanggal_kerja']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data jadwal berhasil ditambahkan',
            'data'   => $jadwal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jadwal = JadwalPegawai::with(['user','shift'])->find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data jadwal tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Detail dokumen berhasil dimuat',
            'data'   => $jadwal
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jadwal = JadwalPegawai::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data jadwal tidak ditemukan'
            ], 404);
        }

        // Catatan: Di API Mobile, untuk upload file saat update, 
        // pastikan aplikasi mobile mengirim dengan method POST dan parameter _method=PUT
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'shift_id' => 'sometimes|exists:shifts,id',
            'tanggal_kerja' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal->update($request->only(['user_id','shift_id','tanggal_kerja']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data jadwal berhasil diperbarui',
            'data'   => $jadwal
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = JadwalPegawai::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data jadwal tidak ditemukan'
            ], 404);
        }

        // Hapus data dari MySQL
        $jadwal->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data dan file jadwal pegawai berhasil dihapus secara permanen'
        ], 200);
    }
}

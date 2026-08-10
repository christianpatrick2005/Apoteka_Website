<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ShiftController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database MySQL (termasuk relasinya)
        $data = Shift::with(['jadwalPegawai'])->get();

        // 2. Kembalikan respons dalam struktur JSON yang rapi
        return response()->json([
            'status' => 'success',
            'pesan' => 'Data shift berhasil dimuat',
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
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_keluar' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke database (Langsung ambil data yang dibutuhkan)
        $shift = Shift::create($request->only(['nama_shift', 'jam_masuk', 'jam_keluar']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data shift berhasil ditambahkan',
            'data'   => $shift
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data shift tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Detail izin cuti berhasil dimuat',
            'data'   => $shift
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data shift tidak ditemukan'
            ], 404);
        }

        // 1. Validasi input dari mobile
        $validator = Validator::make($request->all(), [
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_keluar' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $shift->update($request->only(['nama_shift', 'jam_masuk', 'jam_keluar']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data shift berhasil diperbarui',
            'data'   => $shift
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data shift tidak ditemukan'
            ], 404);
        }

        $shift->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data shift berhasil dihapus secara permanen'
        ], 200);
    }
}

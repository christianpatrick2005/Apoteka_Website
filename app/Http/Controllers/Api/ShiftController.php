<?php

namespace App\Http\Controllers\Api;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Shift::with(['jadwalPegawai'])->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar shift berhasil diambil.',
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_shift' => 'required|string|max:255',
            'jam_masuk'  => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $shift = Shift::create($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Data shift berhasil ditambahkan.',
            'data'    => $shift
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        $shift->load('jadwalPegawai');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail shift berhasil diambil.',
            'data'    => $shift
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $validator = Validator::make($request->all(), [
            'nama_shift' => 'required|string|max:255',
            'jam_masuk'  => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $shift->update($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Data shift berhasil diubah.',
            'data'    => $shift
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data shift berhasil dihapus.'
        ], 200);
    }
}
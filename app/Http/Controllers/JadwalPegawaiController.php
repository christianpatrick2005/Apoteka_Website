<?php

namespace App\Http\Controllers;

use App\Models\JadwalPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JadwalPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = JadwalPegawai::with(['user','shift'])->get();
        return view('views.ManageShift', compact('data'));
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

        return view('views.ManageShift', compact('jadwalPegawai'));
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
}

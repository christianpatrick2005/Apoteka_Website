<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzinCuti;
use Illuminate\Http\Request;

class PengajuanIzinCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi berkas berkas
        $request->validate([
            // Validasi aturan 
            'berkas_pendukung' => 'nullable|array', 
            
            // Validasi khusus untuk SETIAP file di dalam array tersebut (*)
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,mp4|max:10240', // Maks 10MB per file
        ]);

        // array kosong untuk menampung nama-nama file
        $pathBerkas = [];

        // pengecekan apakah ada file yang diunggah
        if ($request->hasFile('berkas_pendukung')) {
            
            // looping untuk setiap file yang diunggah
            foreach ($request->file('berkas_pendukung') as $file) {
                // Simpan file ke folder 'public/uploads/berkas' dan masukkan path-nya ke array
                $pathBerkas[] = $file->store('uploads/berkas', 'public');
            }
        }

        // Simpan ke database
        PengajuanIzinCuti::create([
            'user_id' => auth()->id(),
            //....blm selesai
            
            // Masukkan array pathBerkas. 
            // Berkat $casts di Model, Laravel akan otomatis mengubahnya menjadi JSON di MySQL.
            'berkas_pendukung' => !empty($pathBerkas) ? $pathBerkas : null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanIzinCuti $pengajuanIzinCuti)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DokumenPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class DokumenPegawaiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DokumenPegawai::with(['user'])->get();
        return view('ManageDokumen', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('forms.FormDokumen', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form HTML
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transkrip' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijasah'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sertifikat_kompetensi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        // JIKA GAGAL: Kembalikan ke halaman sebelumnya beserta pesan error dan data yang sudah diketik
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // 2. Siapkan array data untuk disimpan
        $data = $request->only(['user_id', 'tanggal_kadaluarsa_sipa']);

        // 3. Proses upload masing-masing file
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa', 'transkrip'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        // 4. Simpan ke database
        DokumenPegawai::create($data);

        return back()->with('success', 'Dokumen pegawai berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(DokumenPegawai $dokumenPegawai)
    {
        $dokumenPegawai->load('user');

        // Panggil file Blade HTML dan bawa datanya
        return view('details.dokumen', compact('dokumenPegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DokumenPegawai $dokumenPegawai)
    {
        //compact untuk membawa data lama
        $users = User::all();
        return view('forms.FormDokumen',compact('dokumenPegawai','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DokumenPegawai $dokumenPegawai)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'transkrip' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijasah'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sertifikat_kompetensi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // Update data dasar teks
        $data = $request->only(['user_id', 'tanggal_kadaluarsa_sipa']);

        // Cek dan timpa file lama dengan file baru jika ada
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa', 'transkrip'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file fisik lama di folder storage agar tidak menumpuk
                if ($dokumenPegawai->$field) {
                    Storage::disk('public')->delete($dokumenPegawai->$field);
                }
                // Upload file baru
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        $dokumenPegawai->update($data);

        return back()->with('success', 'Dokumen pegawai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenPegawai $dokumenPegawai)
    {

        // Hapus SEMUA file fisik yang terkait (Hanya memproses yang berupa file)
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($dokumenPegawai->$field) {
                Storage::disk('public')->delete($dokumenPegawai->$field);
            }
        }

        // Hapus data dari MySQL
        $dokumenPegawai->delete();

        return back()->with('success', 'Data dan file dokumen pegawai berhasil dihapus secara permanen');
    }
}

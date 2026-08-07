<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DokumenPegawai;
use Illuminate\Support\Facades\Storage;

class DokumenPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database MySQL (termasuk relasinya)
        $data = DokumenPegawai::with(['user'])->get();

        // 2. Kembalikan respons dalam struktur JSON yang rapi
        return response()->json([
            'status' => 'success',
            'pesan' => 'Data dokumen pegawai berhasil dimuat',
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
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijasah'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sertifikat_kompetensi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Siapkan array data untuk disimpan
        $data = ['user_id' => $request->user_id, 'tanggal_kadaluarsa_sipa' => $request->tanggal_kadaluarsa_sipa];

        // 3. Proses upload masing-masing file jika ada
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        // 4. Simpan ke database
        $dokumen = DokumenPegawai::create($data);

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Dokumen pegawai berhasil ditambahkan',
            'data'   => $dokumen
        ], 201);  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokumen = DokumenPegawai::with(['user'])->find($id);

        if (!$dokumen) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data dokumen tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Detail dokumen berhasil dimuat',
            'data'   => $dokumen
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokumen = DokumenPegawai::find($id);

        if (!$dokumen) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data dokumen tidak ditemukan'
            ], 404);
        }

        // Catatan: Di API Mobile, untuk upload file saat update, 
        // pastikan aplikasi mobile mengirim dengan method POST dan parameter _method=PUT
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijasah'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sertifikat_kompetensi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data dasar
        $data = $request->only(['user_id','tanggal_kadaluarsa_sipa']);

        // Cek dan timpa file lama dengan file baru jika ada
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file fisik lama di folder storage agar tidak menumpuk
                if ($dokumen->$field) {
                    Storage::disk('public')->delete($dokumen->$field);
                }
                // Upload file baru
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        $dokumen->update($data);

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Dokumen pegawai berhasil diperbarui',
            'data'   => $dokumen
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokumen = DokumenPegawai::find($id);

        if (!$dokumen) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data dokumen tidak ditemukan'
            ], 404);
        }

        // Hapus SEMUA file fisik yang terkait dengan pegawai ini di folder storage
        $fileFields = ['ktp', 'ijasah', 'str', 'sertifikat_kompetensi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($dokumen->$field) {
                Storage::disk('public')->delete($dokumen->$field);
            }
        }

        // Hapus data dari MySQL
        $dokumen->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data dan file dokumen pegawai berhasil dihapus secara permanen'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\DokumenPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DokumenPegawaiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DokumenPegawai::with(['user'])->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar dokumen pegawai berhasil diambil.',
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'ijazah_s2' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_s1'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'dokumen_profesi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Siapkan array data untuk disimpan
        $data = $request->only(['user_id', 'tanggal_kadaluarsa_sipa']);

        // 3. Proses upload masing-masing file
        $fileFields = ['ktp', 'ijazah_s1', 'ijazah_s2', 'str', 'dokumen_profesi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        // 4. Simpan ke database
        $dokumen = DokumenPegawai::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Dokumen pegawai berhasil ditambahkan.',
            'data'    => $dokumen
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DokumenPegawai $dokumenPegawai)
    {
        $dokumenPegawai->load('user');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail dokumen pegawai berhasil diambil.',
            'data'    => $dokumenPegawai
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DokumenPegawai $dokumenPegawai)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_s1'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_s2' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'dokumen_profesi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update data dasar teks
        $data = $request->only(['user_id', 'tanggal_kadaluarsa_sipa']);

        // Cek dan timpa file lama dengan file baru jika ada
        $fileFields = ['ktp', 'ijazah_s1', 'ijazah_s2', 'str', 'dokumen_profesi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($dokumenPegawai->$field) {
                    Storage::disk('public')->delete($dokumenPegawai->$field);
                }
                $data[$field] = $request->file($field)->store('uploads/dokumen', 'public');
            }
        }

        $dokumenPegawai->update($data);

        // Notifikasi OneSignal
        $pemilikDokumen = $dokumenPegawai->user ?? User::find($dokumenPegawai->user_id);
        
        if ($pemilikDokumen) {
            $pemilikId = $pemilikDokumen->onesignal_id ? [$pemilikDokumen->onesignal_id] : [];

            $manajerIds = User::where('role', 'manajer')
                ->whereNotNull('onesignal_id')
                ->pluck('onesignal_id')
                ->toArray();

            $targetIds = array_unique(array_merge($pemilikId, $manajerIds));

            if (!empty($targetIds)) {
                if ($request->filled('tanggal_kadaluarsa_sipa')) {
                    $tanggalInput = \Carbon\Carbon::parse($request->tanggal_kadaluarsa_sipa);
                    $batasWaktu = \Carbon\Carbon::now()->addMonths(6);
                    
                    $tanggalCantik = $tanggalInput->translatedFormat('d M Y');
                    $pengubah = auth()->check() ? auth()->user()->name : 'Sistem';

                    if ($tanggalInput->lessThanOrEqualTo($batasWaktu)) {
                        $pesan = "Peringatan SIPA: Dokumen SIPA milik {$pemilikDokumen->name} akan kedaluwarsa pada {$tanggalCantik}.";
                        $judul = "⚠️ SIPA Hampir Habis";
                    } else {
                        $pesan = "Masa aktif SIPA milik {$pemilikDokumen->name} telah diperpanjang hingga {$tanggalCantik} oleh {$pengubah}.";
                        $judul = "✅ SIPA Diperbarui";
                    }
                } else {
                    $pengubah = auth()->check() ? auth()->user()->name : 'Sistem';
                    $pesan = "Data dokumen kepegawaian (KTP, Ijazah, dll) milik {$pemilikDokumen->name} telah diperbarui oleh {$pengubah}.";
                    $judul = "📁 Dokumen Diperbarui";
                }

                Http::withHeaders([
                    'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://onesignal.com/api/v1/notifications', [
                    'app_id' => env('ONESIGNAL_APP_ID'),
                    'include_player_ids' => array_values($targetIds),
                    'contents' => [
                        'en' => $pesan,
                        'id' => $pesan
                    ],
                    'headings' => [
                        'en' => $judul,
                        'id' => $judul
                    ]
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Dokumen pegawai berhasil diperbarui.',
            'data'    => $dokumenPegawai
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenPegawai $dokumenPegawai)
    {
        $fileFields = ['ktp', 'ijazah_s1', 'ijazah_s2', 'str', 'dokumen_profesi', 'sipa'];
        foreach ($fileFields as $field) {
            if ($dokumenPegawai->$field) {
                Storage::disk('public')->delete($dokumenPegawai->$field);
            }
        }

        $dokumenPegawai->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data dan file dokumen pegawai berhasil dihapus secara permanen.'
        ], 200);
    }
}
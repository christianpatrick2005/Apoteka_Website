<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PengajuanIzinCuti;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PengajuanIzinCutiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PengajuanIzinCuti::with(['user', 'userPengganti'])->get();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pengajuan berhasil dimuat',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Perhatikan validasi array untuk berkas_pendukung)
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id', // Diubah jadi nullable jika tidak selalu ada
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'required|string',
            'keterangan' => 'required|string',
            'alamat_tempat' => 'required|string',
            'jenis' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'tanda_tangan' => 'nullable|file|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
            'berkas_pendukung' => 'nullable|array', // Harus array
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240', // Validasi isi array
            'status_pengajuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_persetujuan' => 'nullable|date',
            'komentar_manajer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Siapkan data teks (kecuali file)
        $data = $request->except(['berkas_pendukung', 'tanda_tangan']);

        // 3. Proses Upload Tanda Tangan (Satu File)
        if ($request->hasFile('tanda_tangan')) {
            $data['tanda_tangan'] = $request->file('tanda_tangan')->store('uploads/ttd', 'public');
        }

        // 4. Proses Upload Berkas Pendukung (Multi File / Array)
        $pathBerkas = [];
        if ($request->hasFile('berkas_pendukung')) {
            foreach ($request->file('berkas_pendukung') as $file) {
                $pathBerkas[] = $file->store('uploads/berkas_izin', 'public');
            }
        }
        // Masukkan array path ke dalam data. Akan otomatis jadi JSON jika di Model ada $casts
        $data['berkas_pendukung'] = !empty($pathBerkas) ? $pathBerkas : null;

        // 5. Simpan ke Database
        $pengajuan = PengajuanIzinCuti::create($data);

        // 6. Kirim Notifikasi ke User Pengganti via OneSignal
        if ($pengajuan->user_pengganti_id) {
            $pengganti = User::find($pengajuan->user_pengganti_id);
            if ($pengganti && $pengganti->onesignal_id) {
                $pengaju = User::find($pengajuan->user_id);
                $namaPengaju = $pengaju ? $pengaju->name : 'Seseorang';
                
                // Gunakan environment variable ONESIGNAL_REST_API_KEY dan ONESIGNAL_APP_ID
                $apiKey = env('ONESIGNAL_REST_API_KEY', 'YOUR_REST_API_KEY');
                $appId = env('ONESIGNAL_APP_ID', 'YOUR_APP_ID');

                Http::withHeaders([
                    'Authorization' => 'Basic ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://onesignal.com/api/v1/notifications', [
                    'app_id' => $appId,
                    'include_player_ids' => [$pengganti->onesignal_id],
                    'contents' => [
                        'en' => "Ada pengajuan cuti dari {$namaPengaju} yang membutuhkan Anda sebagai pengganti.",
                        'id' => "Ada pengajuan cuti dari {$namaPengaju} yang membutuhkan Anda sebagai pengganti."
                    ],
                    'headings' => [
                        'en' => "Butuh Persetujuan Cuti",
                        'id' => "Butuh Persetujuan Cuti"
                    ]
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data izin cuti berhasil ditambahkan',
            'data'   => $pengajuan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengajuan = PengajuanIzinCuti::with(['user','userPengganti'])->find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data izin cuti tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Detail izin cuti berhasil dimuat',
            'data'   => $pengajuan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengajuan = PengajuanIzinCuti::find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data izin cuti tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id',
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'required|string',
            'keterangan' => 'required|string',
            'alamat_tempat' => 'required|string',
            'jenis' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'tanda_tangan' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'berkas_pendukung' => 'nullable|array',
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240',
            'status_pengajuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_persetujuan' => 'nullable|date',
            'komentar_manajer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['berkas_pendukung', 'tanda_tangan']);

        // Update Tanda Tangan
        if ($request->hasFile('tanda_tangan')) {
            // Hapus ttd lama
            if ($pengajuan->tanda_tangan) {
                Storage::disk('public')->delete($pengajuan->tanda_tangan);
            }
            $data['tanda_tangan'] = $request->file('tanda_tangan')->store('uploads/ttd', 'public');
        }

        // Update Berkas Pendukung (Timpa dengan file baru)
        if ($request->hasFile('berkas_pendukung')) {
            // Hapus semua berkas lama di array
            if (is_array($pengajuan->berkas_pendukung)) {
                foreach ($pengajuan->berkas_pendukung as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            $pathBerkas = [];
            foreach ($request->file('berkas_pendukung') as $file) {
                $pathBerkas[] = $file->store('uploads/berkas_izin', 'public');
            }
            $data['berkas_pendukung'] = $pathBerkas;
        }

        $pengajuan->update($data);

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data izin cuti berhasil diperbarui',
            'data'   => $pengajuan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengajuan = PengajuanIzinCuti::find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data izin cuti tidak ditemukan'
            ], 404);
        }

        // 1. Hapus file Tanda Tangan fisik
        if ($pengajuan->tanda_tangan) {
            Storage::disk('public')->delete($pengajuan->tanda_tangan);
        }

        // 2. Hapus file Berkas Pendukung fisik (karena bentuknya array, harus dilooping)
        if (is_array($pengajuan->berkas_pendukung)) {
            foreach ($pengajuan->berkas_pendukung as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        // 3. Hapus data dari MySQL
        $pengajuan->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data dan file izin cuti berhasil dihapus secara permanen'
        ], 200);
    }
}
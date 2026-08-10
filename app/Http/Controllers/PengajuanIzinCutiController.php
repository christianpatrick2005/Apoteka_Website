<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzinCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class PengajuanIzinCutiController 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PengajuanIzinCuti::with(['user', 'userPengganti'])->get();

        return view('ManageIzinCuti', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('forms.FormIzinCuti', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi berkas berkas
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id', // Diubah jadi nullable jika tidak selalu ada
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'required|string',
            'keterangan' => 'required|string',
            'alamat_tempat' => 'required|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'berkas_pendukung' => 'nullable|array', // Harus array
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240', // Validasi isi array
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // 2. Siapkan data teks (kecuali file)
        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'alamat_tempat', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai']);

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
        PengajuanIzinCuti::create($data + [
            // Masukkan array pathBerkas. 
            // Berkat $casts di Model, Laravel akan otomatis mengubahnya menjadi JSON di MySQL.
            'berkas_pendukung' => !empty($pathBerkas) ? $pathBerkas : null,
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        $pengajuanIzinCuti->load('user','userPengganti');
        return view('details.cuti', compact('pengajuanIzinCuti'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        return view('forms.FormIzinCuti', compact('pengajuanIzinCuti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanIzinCuti $pengajuanIzinCuti)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id',
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'required|string',
            'keterangan' => 'required|string',
            'alamat_tempat' => 'required|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'berkas_pendukung' => 'nullable|array',
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'alamat_tempat', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai']);

        // Jika pegawai mengedit pengajuannya, kembalikan statusnya jadi 'pending' 
        // agar manajer tahu ada perubahan dan harus me-review ulang.
        $data['status_pengajuan'] = 'pending';

        // Update Berkas Pendukung (Timpa dengan file baru)
        if ($request->hasFile('berkas_pendukung')) {
            // Hapus semua berkas lama di array
            if (is_array($pengajuanIzinCuti->berkas_pendukung)) {
                foreach ($pengajuanIzinCuti->berkas_pendukung as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            $pathBerkas = [];
            foreach ($request->file('berkas_pendukung') as $file) {
                $pathBerkas[] = $file->store('uploads/berkas_izin', 'public');
            }
            $data['berkas_pendukung'] = $pathBerkas;
        }

        $pengajuanIzinCuti->update($data); 

        return back()->with('success', 'Pengajuan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        if ($pengajuanIzinCuti->tanda_tangan) {
            Storage::disk('public')->delete($pengajuanIzinCuti->tanda_tangan);
        }

        if (is_array($pengajuanIzinCuti->berkas_pendukung)) {
            foreach ($pengajuanIzinCuti->berkas_pendukung as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $pengajuanIzinCuti->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus');
    }

    /**
     * Memproses persetujuan atau penolakan oleh Manajer
     */
    public function persetujuan(Request $request, PengajuanIzinCuti $pengajuan)
    {
        // 1. Validasi khusus untuk manajer (hanya butuh status dan komentar)
        $validator = Validator::make($request->all(), [
            'status_pengajuan' => 'required|in:disetujui,ditolak',
            'komentar_manajer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Gagal memproses persetujuan.');
        }

        // 2. Update status, komentar, dan catat tanggal hari ini otomatis
        $pengajuan->update([
            'status_pengajuan'    => $request->status_pengajuan,
            'komentar_manajer'    => $request->komentar_manajer,
            'tanggal_persetujuan' => now(), // Fungsi bawaan Laravel untuk mengambil waktu saat ini
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui menjadi: ' . $request->status_pengajuan);
    }
}

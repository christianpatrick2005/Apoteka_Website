<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzinCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Http;

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
            'durasi' => 'nullable|string',
            'keterangan' => 'required|string',
            'alamat_tempat' => 'required|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'required_if:kategori,cuti|nullable|date',
            'tanggal_selesai' => 'required_if:kategori,cuti|nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'jam_selesai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'berkas_pendukung' => 'nullable|array', // Harus array
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240', // Validasi isi array
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // 2. Siapkan data teks (kecuali file)
        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'alamat_tempat', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai']);

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
        $pengajuan = PengajuanIzinCuti::create($data + [
            // Masukkan array pathBerkas. 
            // Berkat $casts di Model, Laravel akan otomatis mengubahnya menjadi JSON di MySQL.
            'berkas_pendukung' => !empty($pathBerkas) ? $pathBerkas : null,
        ]);

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
            'tanggal_mulai' => 'required_if:kategori,cuti|nullable|date',
            'tanggal_selesai' => 'required_if:kategori,cuti|nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'jam_selesai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'berkas_pendukung' => 'nullable|array',
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'alamat_tempat', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai']);

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
     * Menampilkan form persetujuan
     */
    public function showPersetujuanForm(PengajuanIzinCuti $pengajuan)
    {
        $pengajuan->load('user', 'userPengganti');
        return view('forms.FormPersetujuan', compact('pengajuan'));
    }

    public function persetujuanPengganti(Request $request, PengajuanIzinCuti $pengajuan)
    {
        // Pastikan yang login adalah benar-benar user_pengganti_id yang ditunjuk
        if (auth()->id() !== $pengajuan->user_pengganti_id) {
            return abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'status_pengganti' => 'required|in:disetujui,ditolak'
        ]);

        $pengajuan->update([
            'status_pengganti' => $request->status_pengganti
        ]);

        return back()->with('success', 'Anda telah merespons permintaan sebagai pengganti.');
    }

    /**
     * Memproses persetujuan atau penolakan oleh Manajer
     */
    public function persetujuan(Request $request, PengajuanIzinCuti $pengajuan)
    {
        // 1. Validasi input manajer
        $validator = Validator::make($request->all(), [
            'status_pengajuan' => 'required|in:disetujui,ditolak',
            'komentar_manajer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Gagal memproses persetujuan.');
        }

        // Ambil data pegawai yang mengajukan
        $user = $pengajuan->user;

        // 2. SKENARIO A: Manajer menyetujui pengajuan (dan sebelumnya statusnya BUKAN disetujui)
        if ($request->status_pengajuan === 'disetujui' && $pengajuan->status_pengajuan !== 'disetujui') {
            
            // Pastikan ini adalah kategori cuti (bukan izin biasa)
            if ($pengajuan->kategori === 'cuti' && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
                
                // Hitung jumlah hari menggunakan Carbon (Tanggal Selesai - Tanggal Mulai + 1)
                $tglMulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
                $tglSelesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
                $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

                // Kurangi jatah berdasarkan jenis cuti
                if ($pengajuan->jenis_cuti === 'cuti_tahunan') {
                    if ($user->jatah_cuti_tahunan < $jumlahHari) {
                        return back()->with('error', 'Persetujuan digagalkan! Sisa cuti tahunan pegawai (' . $user->jatah_cuti_tahunan . ' hari) tidak mencukupi untuk pengajuan ini (' . $jumlahHari . ' hari).');
                    }
                    $user->jatah_cuti_tahunan -= $jumlahHari;
                    $user->save();

                } elseif ($pengajuan->jenis_cuti === 'cuti_kehamilan') {
                    if ($user->jatah_cuti_kehamilan < $jumlahHari) {
                        return back()->with('error', 'Persetujuan digagalkan! Sisa cuti kehamilan pegawai (' . $user->jatah_cuti_kehamilan . ' hari) tidak mencukupi.');
                    }
                    $user->jatah_cuti_kehamilan -= $jumlahHari;
                    $user->save();
                }
            }
        }
        
        // 3. SKENARIO B: Manajer menolak pengajuan yang SEBELUMNYA sudah telanjur disetujui (Refund Jatah Cuti)
        elseif ($request->status_pengajuan === 'ditolak' && $pengajuan->status_pengajuan === 'disetujui') {
            
            if ($pengajuan->kategori === 'cuti' && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
                
                $tglMulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
                $tglSelesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
                $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

                // Kembalikan (tambahkan) jatah cuti yang sempat terpotong
                if ($pengajuan->jenis_cuti === 'cuti_tahunan') {
                    $user->jatah_cuti_tahunan += $jumlahHari;
                    $user->save();
                } elseif ($pengajuan->jenis_cuti === 'cuti_kehamilan') {
                    $user->jatah_cuti_kehamilan += $jumlahHari;
                    $user->save();
                }
            }
        }

        // 4. Update status dan komentar di tabel PengajuanIzinCuti
        $pengajuan->update([
            'status_pengajuan'    => $request->status_pengajuan,
            'komentar_manajer'    => $request->komentar_manajer,
            'tanggal_persetujuan' => now(),
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui menjadi: ' . ucfirst($request->status_pengajuan));
    }
}

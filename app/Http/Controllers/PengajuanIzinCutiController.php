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
        $query = PengajuanIzinCuti::with(['user', 'userPengganti']);

        if (auth()->check() && auth()->user()->role !== 'manajer') {
            // Pegawai hanya bisa melihat pengajuannya sendiri, 
            // atau pengajuan di mana dia ditunjuk sebagai pengganti.
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('user_pengganti_id', auth()->id());
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

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
            'user_pengganti_id' => 'required_if:kategori,cuti|nullable|exists:users,id', // Diwajibkan jika kategori adalah cuti
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'nullable|string',
            'keterangan' => 'required|string',
            // 'alamat_tempat' => 'nullable|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'required_if:kategori,cuti|nullable|date',
            'tanggal_selesai' => 'required_if:kategori,cuti|nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'jam_selesai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'berkas_pendukung' => 'nullable|array', // Harus array
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240', // Validasi isi array
            'geolocation' => 'required_if:kategori,izin|nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // 2. Siapkan data teks (kecuali file)
        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'geolocation']);

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

        // 2. Ambil onesignal_id milik Pegawai Pengganti
        $pengganti = User::find($request->user_pengganti_id);
        $penggantiIds = ($pengganti && $pengganti->onesignal_id) ? [$pengganti->onesignal_id] : [];

        // 3. Ambil semua onesignal_id milik Manajer
        $manajerIds = User::where('role', 'manajer')
            ->whereNotNull('onesignal_id')
            ->pluck('onesignal_id')
            ->toArray();

        // 4. Kirim notifikasi secara terpisah
        $namaPemohon = auth()->user()->name;
        $kategoriStr = strtolower($request->kategori); // "cuti" atau "izin"

        // Notifikasi untuk Pegawai Pengganti (jika ada)
        if (!empty($penggantiIds)) {
            $pesanPengganti = "Ada permintaan penggantian " . $kategoriStr;
            Http::withHeaders([
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'include_player_ids' => array_values($penggantiIds),
                'contents' => [
                    'en' => $pesanPengganti,
                    'id' => $pesanPengganti
                ],
                'headings' => [
                    'en' => "🔔 Permintaan Pengganti",
                    'id' => "🔔 Permintaan Pengganti"
                ],
            ]);
        }

        // Notifikasi untuk Manajer
        if (!empty($manajerIds)) {
            $pesanManajer = "Ada pengajuan " . $kategoriStr . " baru, mohon segera dicek";
            Http::withHeaders([
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'include_player_ids' => array_values($manajerIds),
                'contents' => [
                    'en' => $pesanManajer,
                    'id' => $pesanManajer
                ],
                'headings' => [
                    'en' => "🔔 Pengajuan Baru",
                    'id' => "🔔 Pengajuan Baru"
                ],
            ]);
        }

        return back()->with('success', 'Pengajuan ' . $kategoriStr . ' anda berhasil dikirim');
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
        if ($pengajuanIzinCuti->status_pengajuan === 'disetujui') {
            return back()->with('error', 'Pengajuan yang sudah disetujui tidak dapat diedit.');
        }
        
        return view('forms.FormIzinCuti', compact('pengajuanIzinCuti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanIzinCuti $pengajuanIzinCuti)
    {
        if ($pengajuanIzinCuti->status_pengajuan === 'disetujui') {
            return back()->with('error', 'Pengajuan yang sudah disetujui tidak dapat diedit.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id',
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'nullable|string',
            'keterangan' => 'required|string',
            // 'alamat_tempat' => 'required|string',
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
            'durasi', 'keterangan', 'jenis_cuti', 
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

        $dataUpdate = [
            'status_pengganti' => $request->status_pengganti
        ];

        if ($request->status_pengganti === 'ditolak') {
            $dataUpdate['status_pengajuan'] = 'ditolak';
        }

        $pengajuan->update($dataUpdate);

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

        // Validasi: Jika manager mencoba menyetujui, pastikan pengganti (jika ada) sudah menyetujui
        if ($request->status_pengajuan === 'disetujui') {
            if ($pengajuan->user_pengganti_id && $pengajuan->status_pengganti !== 'disetujui') {
                return back()->with('error', 'Persetujuan digagalkan! Pegawai pengganti belum menyetujui pengajuan ini.');
            }
        }

        if ($pengajuan->status_pengganti === 'ditolak') {
            $pengajuan->update([
                'status_pengajuan' => 'ditolak',
            ]);
            return back()->with('error', 'Persetujuan digagalkan! Pegawai pengganti tidak menyetujui pengajuan ini.');
        }

        // logika POTONG JATAH CUTI & KEMBALIKAN JATAH CUTI
        if ($pengajuan->kategori === 'cuti' && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
            $tglMulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
            $tglSelesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

            // Skenario A: Saat ini belum disetujui, lalu diubah menjadi DISETUJUI (Potong Cuti)
            if ($pengajuan->status_pengajuan !== 'disetujui' && $request->status_pengajuan === 'disetujui') {
                if ($pengajuan->jenis_cuti === 'cuti_tahunan') {
                    if ($user->jatah_cuti_tahunan < $jumlahHari) {
                        return back()->with('error', 'Persetujuan digagalkan! Sisa cuti tahunan pegawai (' . $user->jatah_cuti_tahunan . ' hari) tidak mencukupi untuk pengajuan ini (' . $jumlahHari . ' hari).');
                    }
                    $user->jatah_cuti_tahunan -= $jumlahHari;
                    $user->save();
                } elseif ($pengajuan->jenis_cuti === 'cuti_kehamilan') {
                    if ($user->jatah_cuti_kehamilan < $jumlahHari) {
                        return back()->with('error', 'Persetujuan digagalkan! Sisa cuti kehamilan tidak mencukupi.');
                    }
                    $user->jatah_cuti_kehamilan -= $jumlahHari;
                    $user->save();
                }
            }
            // Skenario B: Saat ini sudah disetujui, lalu diubah menjadi DITOLAK atau PENDING (Kembalikan Cuti)
            elseif ($pengajuan->status_pengajuan === 'disetujui' && ($request->status_pengajuan === 'ditolak' || $request->status_pengajuan === 'pending')) {
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

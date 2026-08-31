<?php

namespace App\Http\Controllers\Api;

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
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('user_pengganti_id', auth()->id());
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar pengajuan izin/cuti berhasil diambil.',
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_pengganti_id' => 'nullable|exists:users,id',
            'kategori' => 'required|in:izin,cuti',
            'tanggal_pengajuan' => 'required|date',
            'durasi' => 'nullable|string',
            'keterangan' => 'required|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'required_if:kategori,cuti|nullable|date',
            'tanggal_selesai' => 'required_if:kategori,cuti|nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'jam_selesai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'berkas_pendukung' => 'nullable|array',
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240',
            'geolocation' => 'required_if:kategori,izin|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'geolocation']);

        $pathBerkas = [];
        if ($request->hasFile('berkas_pendukung')) {
            foreach ($request->file('berkas_pendukung') as $file) {
                $pathBerkas[] = $file->store('uploads/berkas', 'public');
            }
        }

        $pengajuan = PengajuanIzinCuti::create($data + [
            'berkas_pendukung' => !empty($pathBerkas) ? $pathBerkas : null,
        ]);

        // Kirim Notifikasi OneSignal
        $pengganti = User::find($request->user_pengganti_id);
        $penggantiIds = ($pengganti && $pengganti->onesignal_id) ? [$pengganti->onesignal_id] : [];

        $manajerIds = User::where('role', 'manajer')
            ->whereNotNull('onesignal_id')
            ->pluck('onesignal_id')
            ->toArray();

        $targetIds = array_unique(array_merge($penggantiIds, $manajerIds));

        if (!empty($targetIds)) {
            $namaPemohon = auth()->check() ? auth()->user()->name : 'Pegawai';
            $pesan = "{$namaPemohon} mengajukan cuti/izin baru. Mohon segera dicek dan diproses.";

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
                    'en' => "🔔 Pengajuan Cuti Baru",
                    'id' => "🔔 Pengajuan Cuti Baru"
                ],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan berhasil dikirim!',
            'data'    => $pengajuan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanIzinCuti $pengajuanIzinCuti)
    {
        $pengajuanIzinCuti->load('user', 'userPengganti');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail pengajuan berhasil diambil.',
            'data'    => $pengajuanIzinCuti
        ], 200);
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
            'durasi' => 'nullable|string',
            'keterangan' => 'required|string',
            'jenis_cuti' => 'nullable|in:cuti_tahunan,cuti_kehamilan,lainnya',
            'tanggal_mulai' => 'required_if:kategori,cuti|nullable|date',
            'tanggal_selesai' => 'required_if:kategori,cuti|nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'jam_selesai' => 'required_if:kategori,izin|nullable|date_format:H:i',
            'berkas_pendukung' => 'nullable|array',
            'berkas_pendukung.*' => 'file|mimes:jpeg,png,jpg,pdf,mp4|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->only(['user_id', 'user_pengganti_id', 'kategori', 'tanggal_pengajuan', 
            'durasi', 'keterangan', 'jenis_cuti', 
            'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai']);

        $data['status_pengajuan'] = 'pending';

        if ($request->hasFile('berkas_pendukung')) {
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

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan berhasil diperbarui.',
            'data'    => $pengajuanIzinCuti
        ], 200);
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

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan berhasil dihapus.'
        ], 200);
    }

    public function persetujuanPengganti(Request $request, PengajuanIzinCuti $pengajuan)
    {
        if (auth()->id() !== $pengajuan->user_pengganti_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status_pengganti' => 'required|in:disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $pengajuan->update([
            'status_pengganti' => $request->status_pengganti
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Anda telah merespons permintaan sebagai pengganti.',
            'data'    => $pengajuan
        ], 200);
    }

    /**
     * Memproses persetujuan atau penolakan oleh Manajer
     */
    public function persetujuan(Request $request, PengajuanIzinCuti $pengajuan)
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan' => 'required|in:disetujui,ditolak',
            'komentar_manajer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses persetujuan.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = $pengajuan->user;

        if ($request->status_pengajuan === 'disetujui') {
            if ($pengajuan->user_pengganti_id && $pengajuan->status_pengganti !== 'disetujui') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Persetujuan digagalkan! Pegawai pengganti belum menyetujui pengajuan ini.'
                ], 400);
            }
        }

        if ($pengajuan->status_pengganti === 'ditolak') {
            $pengajuan->update([
                'status_pengajuan' => 'ditolak',
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Persetujuan digagalkan! Pegawai pengganti tidak menyetujui pengajuan ini.'
            ], 400);
        }

        // Potong / Kembalikan Jatah Cuti
        if ($pengajuan->kategori === 'cuti' && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
            $tglMulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
            $tglSelesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

            if ($pengajuan->status_pengajuan !== 'disetujui' && $request->status_pengajuan === 'disetujui') {
                if ($pengajuan->jenis_cuti === 'cuti_tahunan') {
                    if ($user->jatah_cuti_tahunan < $jumlahHari) {
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'Persetujuan digagalkan! Sisa cuti tahunan pegawai (' . $user->jatah_cuti_tahunan . ' hari) tidak mencukupi untuk pengajuan ini (' . $jumlahHari . ' hari).'
                        ], 400);
                    }
                    $user->jatah_cuti_tahunan -= $jumlahHari;
                    $user->save();
                } elseif ($pengajuan->jenis_cuti === 'cuti_kehamilan') {
                    if ($user->jatah_cuti_kehamilan < $jumlahHari) {
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'Persetujuan digagalkan! Sisa cuti kehamilan tidak mencukupi.'
                        ], 400);
                    }
                    $user->jatah_cuti_kehamilan -= $jumlahHari;
                    $user->save();
                }
            } elseif ($pengajuan->status_pengajuan === 'disetujui' && ($request->status_pengajuan === 'ditolak' || $request->status_pengajuan === 'pending')) {
                if ($pengajuan->jenis_cuti === 'cuti_tahunan') {
                    $user->jatah_cuti_tahunan += $jumlahHari;
                    $user->save();
                } elseif ($pengajuan->jenis_cuti === 'cuti_kehamilan') {
                    $user->jatah_cuti_kehamilan += $jumlahHari;
                    $user->save();
                }
            }
        }

        $pengajuan->update([
            'status_pengajuan'    => $request->status_pengajuan,
            'komentar_manajer'    => $request->komentar_manajer,
            'tanggal_persetujuan' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Status pengajuan berhasil diperbarui menjadi: ' . ucfirst($request->status_pengajuan),
            'data'    => $pengajuan
        ], 200);
    }
}
<?php

namespace App\Http\Controllers;

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
            'ijazah_s2' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ktp'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_s1'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'dokumen_profesi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
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
        $fileFields = ['ktp', 'ijazah_s1','ijazah_s2','str','dokumen_profesi','sipa'];
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
            'ijazah_s1'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_s2' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'str'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'dokumen_profesi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sipa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tanggal_kadaluarsa_sipa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // Update data dasar teks
        $data = $request->only(['user_id', 'tanggal_kadaluarsa_sipa']);

        // Cek dan timpa file lama dengan file baru jika ada
        $fileFields = ['ktp', 'ijazah_s1', 'ijazah_s2', 'str', 'dokumen_profesi', 'sipa'];
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

        // Panggil relasi user agar kita tahu ini dokumen milik siapa
        $pemilikDokumen = $dokumenPegawai->user ?? User::find($dokumenPegawai->user_id);
        
        if ($pemilikDokumen) {
            // 1. Ambil ID OneSignal Pemilik Dokumen
            $pemilikId = $pemilikDokumen->onesignal_id ? [$pemilikDokumen->onesignal_id] : [];

            // 2. Ambil semua ID OneSignal Manajer
            $manajerIds = User::where('role', 'manajer')
                ->whereNotNull('onesignal_id')
                ->pluck('onesignal_id')
                ->toArray();

            // 3. Gabungkan ID pemilik & manajer, lalu hapus jika ada ID yang duplikat
            $targetIds = array_unique(array_merge($pemilikId, $manajerIds));

            // 4. Kirimkan notifikasi jika ada minimal 1 penerima
            if (!empty($targetIds)) {
                
                // CEK APAKAH TANGGAL SIPA IKUT DIUBAH/DIISI
                if ($request->filled('tanggal_kadaluarsa_sipa')) {
                    
                    // 1. Ubah inputan menjadi format tanggal Carbon
                    $tanggalInput = \Carbon\Carbon::parse($request->tanggal_kadaluarsa_sipa);
                    // 2. Hitung batas waktu 6 bulan dari sekarang
                    $batasWaktu = \Carbon\Carbon::now()->addMonths(6);
                    
                    $tanggalCantik = $tanggalInput->translatedFormat('d M Y');
                    $pengubah = auth()->check() ? auth()->user()->name : 'Sistem';

                    // 3. Bandingkan! Apakah kurang dari 6 bulan?
                    if ($tanggalInput->lessThanOrEqualTo($batasWaktu)) {
                        // KONDISI A: Benar-benar hampir habis (< 6 bulan)
                        $pesan = "Peringatan SIPA: Dokumen SIPA milik {$pemilikDokumen->name} akan kedaluwarsa pada {$tanggalCantik}.";
                        $judul = "⚠️ SIPA Hampir Habis";
                    } else {
                        // KONDISI B: Tanggal diubah, tapi masa aktifnya masih lama / aman
                        $pesan = "Masa aktif SIPA milik {$pemilikDokumen->name} telah diperpanjang hingga {$tanggalCantik} oleh {$pengubah}.";
                        $judul = "✅ SIPA Diperbarui";
                    }

                } else {
                    // KONDISI C: Jika tanggal SIPA tidak diubah sama sekali (cuma ubah KTP/Ijazah)
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

        return back()->with('success', 'Dokumen pegawai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenPegawai $dokumenPegawai)
    {

        // Hapus SEMUA file fisik yang terkait (Hanya memproses yang berupa file)
        $fileFields = ['ktp', 'ijazah_s1', 'ijazah_s2', 'str', 'dokumen_profesi', 'sipa'];
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

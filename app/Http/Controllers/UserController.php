<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Shift;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database (Prosesnya SAMA PERSIS dengan API)
        $data = User::with(['dokumenPegawai', 'jadwalPegawai', 'pengajuanCuti'])->get();

        // 2. Kembalikan respons ke file HTML/Blade (Ini yang BERBEDA)
        return view('ManagePegawai', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('forms.FormPegawai');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari mobile
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'alamat_surabaya' => 'required|string|max:255',
            'alamat_asal' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:255',
            'status_pernikahan' => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'posisi' => 'required|string|max:255',
            'gaji' => 'required|numeric',
            'nomor_ktp' => 'required|string|numeric|digits:16',
            'kewarganegaraan' => 'required|string|max:255',
            'role' => 'required|in:manajer,pegawai',
            'jatah_cuti_tahunan' => 'required|integer',
            'jatah_cuti_kehamilan' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        $data = $validator->validated();
        // Enkripsi password sebelum disimpan ke database
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('dokumenPegawai', 'jadwalPegawai', 'pengajuanCuti');

        // Ambil daftar pilihan shift (Pagi, Siang, Malam, dll)
        $shifts = Shift::all(); 

        // Kirimkan variabel $shifts ke view
        return view('details.pegawai', compact('user', 'shifts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('forms.FormPegawai', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Cuma boleh unique kalau bukan data dia sendiri
            'password' => 'nullable',
            'alamat_surabaya' => 'required|string|max:255',
            'alamat_asal' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:255',
            'status_pernikahan' => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'posisi' => 'required|string|max:255',
            'gaji' => 'required|numeric',
            'nomor_ktp' => 'required|string|numeric|digits:16',
            'kewarganegaraan' => 'required|string|max:255',
            'role' => 'required|in:manajer,pegawai',
            'jatah_cuti_tahunan' => 'required|integer',
            'jatah_cuti_kehamilan' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        // Ambil data yang divalidasi, KECUALI password
        $data = $request->except(['password']);

        // Jika form password diisi, maka enkripsi dan masukkan ke array data yang akan di-update
        // Jika dikosongkan, maka password lama tidak akan berubah
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Data berhasil dihapus');

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with(['dokumenPegawai', 'jadwalPegawai', 'pengajuanCuti'])->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar pegawai berhasil diambil.',
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required',
            'alamat_surabaya'      => 'required|string|max:255',
            'alamat_asal'          => 'required|string|max:255',
            'nomor_hp'             => 'required|string|max:255',
            'tempat_lahir'         => 'required|string|max:255',
            'tanggal_lahir'        => 'required|date',
            'agama'                => 'required|string|max:255',
            'status_pernikahan'    => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin'        => 'required|in:Laki-laki,Perempuan',
            'posisi'               => 'required|string|max:255',
            'gaji'                 => 'required|numeric',
            'nomor_ktp'            => 'required|string|numeric|digits:16',
            'kewarganegaraan'       => 'required|string|max:255',
            'role'                 => 'required|in:manajer,pegawai',
            'jatah_cuti_tahunan'   => 'required|integer',
            'jatah_cuti_kehamilan' => 'required|integer',
            'Foto_Profil'          => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('Foto_Profil')) {
            $path = $request->file('Foto_Profil')->store('profil', 'public');
            $data['Foto_Profil'] = $path;
        }

        $user = User::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pegawai berhasil ditambahkan.',
            'data'    => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('dokumenPegawai', 'jadwalPegawai', 'pengajuanCuti');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail pegawai berhasil diambil.',
            'data'    => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . $user->id,
            'password'             => 'nullable',
            'alamat_surabaya'      => 'required|string|max:255',
            'alamat_asal'          => 'required|string|max:255',
            'nomor_hp'             => 'required|string|max:255',
            'tempat_lahir'         => 'required|string|max:255',
            'tanggal_lahir'        => 'required|date',
            'agama'                => 'required|string|max:255',
            'status_pernikahan'    => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin'        => 'required|in:Laki-laki,Perempuan',
            'posisi'               => 'required|string|max:255',
            'gaji'                 => 'required|numeric',
            'nomor_ktp'            => 'required|string|numeric|digits:16',
            'kewarganegaraan'       => 'required|string|max:255',
            'role'                 => 'required|in:manajer,pegawai',
            'jatah_cuti_tahunan'   => 'required|integer',
            'jatah_cuti_kehamilan' => 'required|integer',
            'Foto_Profil'          => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = collect($validator->validated())->except(['password'])->toArray();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('Foto_Profil')) {
            if ($user->Foto_Profil && Storage::disk('public')->exists($user->Foto_Profil)) {
                Storage::disk('public')->delete($user->Foto_Profil);
            }
            
            $path = $request->file('Foto_Profil')->store('profil', 'public');
            $data['Foto_Profil'] = $path;
        }

        $user->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pegawai berhasil diperbarui.',
            'data'    => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->Foto_Profil && Storage::disk('public')->exists($user->Foto_Profil)) {
            Storage::disk('public')->delete($user->Foto_Profil);
        }

        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pegawai berhasil dihapus.'
        ], 200);
    }

    public function profilSaya()
    {
        $user = auth()->user();
        $user->load('dokumenPegawai', 'jadwalPegawai', 'pengajuanCuti');

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil saya berhasil diambil.',
            'data'    => $user
        ], 200);
    }

    public function updateProfilSaya(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'Foto_Profil' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('Foto_Profil')) {
            if ($user->Foto_Profil && Storage::disk('public')->exists($user->Foto_Profil)) {
                Storage::disk('public')->delete($user->Foto_Profil);
            }

            $path = $request->file('Foto_Profil')->store('foto_profil', 'public');
            $user->Foto_Profil = $path;
            $user->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto profil berhasil diperbarui.',
            'data'    => $user
        ], 200);
    }
}
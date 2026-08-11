<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database MySQL (termasuk relasinya)
        $data = User::with(['dokumenPegawai', 'pengajuanCuti', 'jadwalPegawai'])->get();

        // 2. Kembalikan respons dalam struktur JSON yang rapi
        return response()->json([
            'status' => 'success',
            'pesan' => 'Data user berhasil dimuat',
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'alamat_surabaya' => 'required|string|max:255',
            'alamat_asal' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'agama' => 'required|string|max:255',
            'status_pernikahan' => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'posisi' => 'required|string|max:255',
            'gaji' => 'required|numeric',
            'nomor_ktp' => 'required|string|max:255',
            'kewarganegaraan' => 'required|string|max:255',
            'role' => 'required|in:manajer,pegawai',
            'jatah_cuti' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke database (Langsung ambil data yang dibutuhkan)
        $user = User::create($request->only(['name', 'email', 'password', 'alamat_surabaya', 'alamat_asal', 'nomor_hp', 'tempat_lahir', 'tanggal_lahir', 'agama', 'status_pernikahan', 'jenis_kelamin', 'posisi', 'gaji', 'nomor_ktp', 'kewarganegaraan', 'role', 'jatah_cuti']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data user berhasil ditambahkan',
            'data'   => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data user tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Detail user berhasil dimuat',
            'data'   => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data user tidak ditemukan'
            ], 404);
        }

        // 1. Validasi input dari mobile
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'alamat_surabaya' => 'required|string|max:255',
            'alamat_asal' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'agama' => 'required|string|max:255',
            'status_pernikahan' => 'required|in:Menikah,Belum Menikah',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'posisi' => 'required|string|max:255',
            'gaji' => 'required|numeric',
            'nomor_ktp' => 'required|string|max:255',
            'kewarganegaraan' => 'required|string|max:255',
            'role' => 'required|in:manajer,pegawai',
            'jatah_cuti' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'password', 'alamat_surabaya', 'alamat_asal', 'nomor_hp', 'tempat_lahir', 'tanggal_lahir', 'agama', 'status_pernikahan', 'jenis_kelamin', 'posisi', 'gaji', 'nomor_ktp', 'kewarganegaraan', 'role', 'jatah_cuti']));

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data user berhasil diperbarui',
            'data'   => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Data user tidak ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data user berhasil dihapus secara permanen'
        ], 200);
    }
}

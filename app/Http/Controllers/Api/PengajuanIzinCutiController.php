<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanIzinCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil data dari database MySQL (termasuk relasinya)
        $data = PengajuanIzinCuti::with(['user', 'userPengganti'])->get();

        // 2. Kembalikan respons dalam struktur JSON yang rapi
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

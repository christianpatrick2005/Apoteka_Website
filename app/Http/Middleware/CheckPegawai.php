<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPegawai
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login DAN jabatannya adalah pegawai
        if (Auth::check() && Auth::user()->role === 'pegawai') {
            return $next($request);
        }

        // Jika manajer mencoba masuk ke rute khusus pegawai
        return abort(403, 'Akses Ditolak! Halaman ini khusus untuk Pegawai.');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckManager
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login DAN jabatannya adalah manager
        if (Auth::check() && Auth::user()->role === 'manajer') {
            // Jika benar manager, silakan lewat
            return $next($request);
        }

        // Jika bukan manager, tolak aksesnya (Error 403 Forbidden)
        return abort(403, 'Akses Ditolak! Halaman ini khusus untuk Manajer.');
    }
}

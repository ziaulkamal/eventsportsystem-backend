<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari cookie
       $token = urldecode(request()->cookie('auth_token'));

        if (!$token) {
            return redirect('/auth/login')->with('error', 'Token tidak ditemukan.');
        }


        // Periksa apakah token valid
        if (!$this->isValidToken($token)) {
            return redirect('/auth/login')->with('error', 'Token tidak valid.');
        }

        return $next($request);
    }

    /**
     * Contoh fungsi validasi token.
     */
    protected function isValidToken($token)
    {
        // Misal validasi sederhana: token harus mengandung karakter "|"
        return strpos($token, '|') !== false;
    }
}

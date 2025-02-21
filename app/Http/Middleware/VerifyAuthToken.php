<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class VerifyAuthToken
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari cookie
        $token = $request->cookie('auth_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah token tersebut valid di database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || $accessToken->expires_at < now()) {
            return redirect()->route('login')->with('error', 'Token tidak valid atau sudah kadaluwarsa.');
        }

        return $next($request);
    }
}

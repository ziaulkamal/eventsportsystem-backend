<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Cek apakah permintaan mengharapkan JSON
        if ($request->expectsJson()) {
            // Jika tidak ada token, kembalikan response 401 dengan pesan JSON
            return response()->json(['message' => 'Akses ditolak'], 401);
        }

        // Jika bukan permintaan API, redirect ke login (tidak digunakan di sini)
        return null;
    }


}

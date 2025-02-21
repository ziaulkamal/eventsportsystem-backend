<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAcceptHeaderIsSet
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $acceptHeader = $request->header('Accept');

        // Jika header Accept tidak ada atau berisi "*/*"
        if (!$acceptHeader || trim($acceptHeader) === '*/*') {
            return response()->json(
                ['message' => 'Header Accept tidak diset atau tidak valid'],
                Response::HTTP_NOT_ACCEPTABLE // HTTP 406 Not Acceptable
            );
        }

        return $next($request);
    }
}

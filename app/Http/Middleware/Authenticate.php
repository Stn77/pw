<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login, redirect ke home (atau dashboard)
        if (!Auth::check()) {
            return redirect()->route('login'); // ganti 'login' dengan route yang sesuai
        }

        // Jika belum login, lanjutkan request
        return $next($request);
    }
}

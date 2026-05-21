<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedTutorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || $user->role !== 'tutor') {
            abort(403, 'Unauthorized.');
        }

        $tutor = $user->tutor;
        
        if (!$tutor || $tutor->status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'Akun tutor Anda masih menunggu verifikasi dari admin. Anda tidak dapat mengakses fitur ini sampai disetujui.');
        }

        return $next($request);
    }
}
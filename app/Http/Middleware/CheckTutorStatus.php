<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTutorStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role === 'tutor' && $user->tutor && $user->tutor->status !== 'active') {
            if (! $request->routeIs('tutor.pending')) {
                return redirect()->route('tutor.pending')
                    ->with('warning', 'Akun Anda belum aktif. Menunggu verifikasi admin.');
            }
        }

        return $next($request);
    }
}

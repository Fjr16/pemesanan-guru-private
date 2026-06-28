<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (empty($roles) || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        return match($user->role) {
            'admin'  => redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin.'),
            'tutor'  => redirect()->route('tutor.dashboard')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin.'),
            default  => redirect()->route('siswa.dashboard')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin.'),
        };
    }
}

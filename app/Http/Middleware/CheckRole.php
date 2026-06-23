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
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (empty($roles) || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        $user = Auth::user();
        $roles = ['admin', 'siswa', 'tutor'];

        // Izinkan jika salah satu role cocok
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Redirect ke dashboard yang sesuai
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

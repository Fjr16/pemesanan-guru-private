<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required', 'in:siswa,tutor,admin'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'credentials' => 'Email atau password salah. Silakan coba lagi.',
            ]);
        }

        $user = Auth::user();

        // Validasi role yang dipilih
        if ($user->role !== $request->role) {
            Auth::logout();
            throw ValidationException::withMessages([
                'credentials' => 'Akun Anda terdaftar sebagai ' . ucfirst($user->role) . ', bukan ' . ucfirst($request->role) . '.',
            ]);
        }

        // Tutor: cek apakah sudah diverifikasi admin
        if ($user->role === 'tutor' && $user->tutorProfile?->status === 'pending') {
            Auth::logout();
            return back()->with('warning', 'Akun tutor Anda masih menunggu verifikasi admin. Mohon tunggu konfirmasi via email.');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo($user->role))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectTo(string $role): string
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'tutor' => route('tutor.dashboard'),
            default => route('siswa.dashboard'),
        };
    }
}

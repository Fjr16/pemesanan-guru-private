<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('login');

        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (! $user || ! Auth::attempt(['email' => $user->email, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'credentials' => 'Username/email atau password salah. Silakan coba lagi.',
            ]);
        }

        $user = Auth::user();

        // Tutor: cek apakah sudah diverifikasi admin
        if ($user->role === 'tutor' && $user->tutor?->status === 'pending') {
            Auth::logout();
            return back()->with('warning', 'Akun tutor Anda masih menunggu verifikasi admin. Mohon tunggu konfirmasi via email.');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo($user->role))
            ->with('success', 'Selamat datang kembali, ' . $user->username . '!');
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

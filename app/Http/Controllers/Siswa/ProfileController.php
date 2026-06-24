<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = $user->student;

        return view('pages.siswa.profil', [
            'user'    => $user,
            'student' => $student,
        ]);
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $student = $user->student;

        $request->validate([
            'username'       => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email'          => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'no_hp'          => ['required', 'string', 'max:15'],
            'name'           => ['required', 'string', 'max:100'],
            'tempat_lhr'     => ['required', 'string', 'max:50'],
            'tanggal_lhr'    => ['required', 'date', 'before:today'],
            'alamat'         => ['required', 'string', 'max:255'],
        ], [
            'username.unique'         => 'Username sudah digunakan.',
            'email.unique'            => 'Email sudah terdaftar.',
            'tanggal_lhr.before'      => 'Tanggal lahir harus sebelum hari ini.',
        ]);

        $user->update([
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ]);

        if ($student) {
            $student->update([
                'name'         => $request->name,
                'tempat_lhr'   => $request->tempat_lhr,
                'tanggal_lhr'  => $request->tanggal_lhr,
                'alamat'       => $request->alamat,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}

<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'alamat'         => ['required', 'string', 'max:500'],
            'lat'            => ['required', 'numeric', 'between:-90,90', 'decimal:0,7'],
            'lng'            => ['required', 'numeric', 'between:-180,180', 'decimal:0,7'],
        ], [
            'username.unique'         => 'Username sudah digunakan.',
            'email.unique'            => 'Email sudah terdaftar.',
            'tanggal_lhr.before'      => 'Tanggal lahir harus sebelum hari ini.',
            'lat.between'             => 'Latitude harus antara -90 dan 90.',
            'lng.between'             => 'Longitude harus antara -180 dan 180.',
            'lat.decimal'             => 'Latitude harus berupa angka desimal.',
            'lng.decimal'             => 'Longitude harus berupa angka desimal.',
        ]);

        $newLat = (float) $request->lat;
        $newLng = (float) $request->lng;

        $coordChanged =
        $student?->latitude_dom !== null && $student?->langitude_dom !== null &&
        (number_format((float) $student->latitude_dom, 7, '.', '') !== number_format($newLat, 7, '.', '') ||
        number_format((float) $student->langitude_dom, 7, '.', '') !== number_format($newLng, 7, '.', ''));

        DB::transaction(function () use ($student, $request, $coordChanged, $newLat, $newLng, $user) {
            $user->update([
                'username' => $request->username,
                'email'    => $request->email,
                'no_hp'    => $request->no_hp,
            ]);    

            $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name'         => $request->name,
                    'tempat_lhr'   => $request->tempat_lhr,
                    'tanggal_lhr'  => $request->tanggal_lhr,
                    'alamat'       => $request->alamat,
                    'latitude_dom' => $newLat,
                    'langitude_dom' => $newLng,
                ]
            );

            if ($coordChanged) {
                $student->cacheDistances()->delete();
            }
        });
        
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

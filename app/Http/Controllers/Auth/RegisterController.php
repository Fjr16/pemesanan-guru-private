<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\TutorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'siswa');

        // ── Base validation (semua role) ─────────────────────────
        $request->validate([
            'role'     => ['required', 'in:siswa,tutor'],
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms'    => ['accepted'],
        ], [
            'email.unique'  => 'Email sudah terdaftar. Silakan gunakan email lain atau login.',
            'terms.accepted'=> 'Anda harus menyetujui syarat & ketentuan.',
            'password.min'  => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // ── Validasi tambahan untuk tutor ────────────────────────
        if ($role === 'tutor') {
            $request->validate([
                'subjects'         => ['required', 'array', 'min:1'],
                'subjects.*'       => ['exists:mata_pelajarans,id'],
                'hourly_rate'      => ['required', 'numeric', 'min:10000'],
                'experience_years' => ['required', 'integer', 'min:0'],
                'education_level'  => ['required', 'string'],
                'bio'              => ['nullable', 'string', 'max:300'],
            ]);
        }

        DB::beginTransaction();
        try {

            // Buat user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => $role,
            ]);

            if ($role === 'tutor') {

                // Buat profil tutor (status pending — butuh verifikasi admin)
                $tutor = TutorProfile::create([
                    'user_id'          => $user->id,
                    'hourly_rate'      => $request->hourly_rate,
                    'experience_years' => $request->experience_years,
                    'education_level'  => $request->education_level,
                    'bio'              => $request->bio,
                    'status'           => 'pending',
                ]);

                // Attach mata pelajaran
                $tutor->mataPelajaran()->attach($request->subjects);

                // Simpan jadwal ketersediaan
                if ($request->has('schedules')) {
                    foreach ($request->schedules as $dayIndex => $slots) {
                        foreach ((array) $slots as $jam) {
                            TutorSchedule::create([
                                'tutor_profile_id' => $tutor->id,
                                'hari'             => $this->indexToDay($dayIndex),
                                'jam_mulai'        => $jam,
                                'jam_selesai'      => date('H:i', strtotime($jam . ' +1 hour')),
                                'is_available'     => true,
                            ]);
                        }
                    }
                }

                DB::commit();

                // Notifikasi ke admin (opsional: event/job)
                // event(new TutorRegistered($tutor));

                return redirect()->route('login')
                    ->with('info', 'Pendaftaran tutor berhasil! Akun Anda sedang menunggu verifikasi admin. Kami akan mengirim email setelah diverifikasi.');

            }

            // Siswa — langsung login
            Auth::login($user);
            $request->session()->regenerate();

            DB::commit();

            return redirect()->route('siswa.dashboard')
                ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    private function indexToDay(int $index): string
    {
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        return $days[$index] ?? 'Senin';
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudiedHistory;
use App\Models\SubjectCategory;
use App\Models\Tutor;
use App\Models\TutorProfile;
use App\Models\TutorSchedule;
use App\Models\TutorSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        $mataPelajaran = SubjectCategory::where('is_active', true)->orderBy('name')->get();
        return view('auth.register', compact('mataPelajaran'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'siswa');

        $baseRules = [
            'role'     => ['required', 'in:siswa,tutor'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'no_hp'    => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms'    => ['accepted'],
        ];

        $baseMessages = [
            'username.unique'    => 'Username sudah digunakan.',
            'email.unique'       => 'Email sudah terdaftar. Silakan gunakan email lain atau login.',
            'terms.accepted'     => 'Anda harus menyetujui syarat & ketentuan.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        if ($role === 'siswa') {
            $request->validate(array_merge($baseRules, [
                'siswa_name'         => ['required', 'string', 'max:100'],
                'tempat_lhr'         => ['required', 'string', 'max:50'],
                'siswa_tanggal_lhr'  => ['required', 'date', 'before:today'],
                'alamat'             => ['required', 'string', 'max:255'],
            ]), array_merge($baseMessages, [
                'siswa_name.required'        => 'Nama lengkap wajib diisi.',
                'siswa_tanggal_lhr.required' => 'Tanggal lahir wajib diisi.',
                'siswa_tanggal_lhr.before'   => 'Tanggal lahir harus sebelum hari ini.',
            ]));
        } else {
            $request->validate(array_merge($baseRules, [
                'tutor_name'               => ['required', 'string', 'max:50'],
                'jenis_kelamin'            => ['required', 'in:Pria,Wanita'],
                'tutor_tanggal_lhr'        => ['required', 'date', 'before:today'],
                'domisili'                 => ['required', 'string', 'max:100'],
                'job'                      => ['required', 'string', 'max:30'],
                'hourly_rate'              => ['required', 'numeric', 'min:10000'],
                'lokasi_mengajar'          => ['required', 'in:offline,online,fleksibel'],
                'desc'                     => ['nullable', 'string', 'max:500'],
                'subject_tingkatan'        => ['required', 'array', 'min:1'],
                'subject_tingkatan.*'      => ['required', 'array', 'min:1'],
                'subject_tingkatan.*.*'    => ['string', 'in:SD,SMP,SMA,Kuliah'],
                'riwayat_pendidikan'                 => ['nullable', 'array'],
                'riwayat_pendidikan.*.sekolah'       => ['required_with:riwayat_pendidikan', 'string', 'max:50'],
                'riwayat_pendidikan.*.jurusan'       => ['required_with:riwayat_pendidikan', 'string', 'max:50'],
                'riwayat_pendidikan.*.periode'       => ['required_with:riwayat_pendidikan', 'string', 'max:50'],
                'pengalaman'                           => ['nullable', 'array'],
                'pengalaman.*.experience_years'       => ['required_with:pengalaman', 'integer', 'min:0'],
                'pengalaman.*.periode'                 => ['required_with:pengalaman', 'string', 'max:50'],
                'pengalaman.*.jumlah_siswa'            => ['required_with:pengalaman', 'integer', 'min:0'],
                'pengalaman.*.tempat'                  => ['nullable', 'string', 'max:100'],
            ]), array_merge($baseMessages, [
                'tutor_name.required'              => 'Nama lengkap wajib diisi.',
                'tutor_tanggal_lhr.required'       => 'Tanggal lahir wajib diisi.',
                'tutor_tanggal_lhr.before'         => 'Tanggal lahir harus sebelum hari ini.',
                'subject_tingkatan.required'       => 'Pilih minimal 1 mata pelajaran.',
                'subject_tingkatan.min'            => 'Pilih minimal 1 mata pelajaran.',
                'subject_tingkatan.*.required'     => 'Pilih minimal 1 tingkatan untuk setiap mata pelajaran.',
                'subject_tingkatan.*.min'          => 'Pilih minimal 1 tingkatan untuk setiap mata pelajaran.',
                'subject_tingkatan.*.*.in'         => 'Tingkatan tidak valid.',
                'jenis_kelamin.in'                 => 'Jenis kelamin harus Pria atau Wanita.',
                'hourly_rate.min'                  => 'Tarif minimal Rp 10.000.',
                'lokasi_mengajar.in'               => 'Lokasi mengajar tidak valid.',
            ]));
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'no_hp'    => $request->no_hp,
                'password' => Hash::make($request->password),
                'role'     => $role,
            ]);

            if ($role === 'siswa') {
                Student::create([
                    'user_id'      => $user->id,
                    'name'         => $request->siswa_name,
                    'tempat_lhr'   => $request->tempat_lhr,
                    'tanggal_lhr'  => $request->siswa_tanggal_lhr,
                    'alamat'       => $request->alamat,
                ]);

                Auth::login($user);
                $request->session()->regenerate();
                DB::commit();

                return redirect()->route('siswa.dashboard')
                    ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $request->siswa_name . '!');
            }

            $tutor = Tutor::create([
                'user_id'         => $user->id,
                'name'            => $request->tutor_name,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tanggal_lhr'     => $request->tutor_tanggal_lhr,
                'domisili'        => $request->domisili,
                'desc'            => $request->desc,
                'job'             => $request->job,
                'hourly_rate'     => $request->hourly_rate,
                'lokasi_mengajar' => $request->lokasi_mengajar,
                'status'          => 'pending',
            ]);

            foreach ($request->subject_tingkatan as $subjectId => $tingkatans) {
                TutorSubject::create([
                    'tutor_id'            => $tutor->id,
                    'subject_category_id' => $subjectId,
                    'tingkatan'           => implode(',', $tingkatans),
                ]);
            }

            if ($request->filled('riwayat_pendidikan')) {
                foreach ($request->riwayat_pendidikan as $row) {
                    if (!empty($row['sekolah'])) {
                        StudiedHistory::create([
                            'tutor_id' => $tutor->id,
                            'jenjang'  => $row['jenjang'] ?? null,
                            'sekolah'  => $row['sekolah'],
                            'jurusan'  => $row['jurusan'],
                            'periode'  => $row['periode'],
                        ]);
                    }
                }
            }

            if ($request->filled('pengalaman')) {
                foreach ($request->pengalaman as $row) {
                    if (!empty($row['periode'])) {
                        TutorProfile::create([
                            'tutor_id'         => $tutor->id,
                            'experience_years' => $row['experience_years'] ?? 0,
                            'periode'          => $row['periode'],
                            'jumlah_siswa'     => $row['jumlah_siswa'] ?? 0,
                            'tempat'           => $row['tempat'] ?? null,
                        ]);
                    }
                }
            }

            if ($request->has('schedules')) {
                $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                foreach ($request->schedules as $dayIndex => $slots) {
                    foreach ((array) $slots as $jam) {
                        TutorSchedule::create([
                            'tutor_id'  => $tutor->id,
                            'day'       => $days[$dayIndex] ?? 'Senin',
                            'jam_start' => $jam,
                            'jam_end'   => date('H:i', strtotime($jam . ' +2 hours')),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('login')
                ->with('info', 'Pendaftaran tutor berhasil! Akun Anda sedang menunggu verifikasi admin.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }
}

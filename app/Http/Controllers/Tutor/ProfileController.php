<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\StudiedHistory;
use App\Models\SubjectCategory;
use App\Models\TutorProfile;
use App\Models\TutorSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $tutor = $user->tutor;

        $mataPelajaran = SubjectCategory::where('is_active', true)->orderBy('name')->get();
        $selectedSubjects = $tutor
            ? $tutor->tutorSubjects->pluck('subject_category_id')->toArray()
            : [];

        return view('pages.tutor.profil', [
            'user'             => $user,
            'tutor'            => $tutor,
            'mataPelajaran'    => $mataPelajaran,
            'selectedSubjects' => $selectedSubjects,
        ]);
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $tutor = $user->tutor;

        $request->validate([
            'username'      => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email'         => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'no_hp'         => ['required', 'string', 'max:15'],
            'name'          => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:Pria,Wanita'],
            'tanggal_lhr'   => ['required', 'date', 'before:today'],
            'domisili'      => ['required', 'string', 'max:100'],
            'desc'          => ['nullable', 'string', 'max:500'],
            'job'           => ['required', 'string', 'max:30'],
            'hourly_rate'   => ['required', 'numeric', 'min:10000'],
            'lokasi_mengajar' => ['required', 'in:offline,online,fleksibel'],
        ], [
            'username.unique'      => 'Username sudah digunakan.',
            'email.unique'         => 'Email sudah terdaftar.',
            'jenis_kelamin.in'     => 'Jenis kelamin harus Pria atau Wanita.',
            'hourly_rate.min'      => 'Tarif minimal Rp 10.000.',
            'lokasi_mengajar.in'   => 'Lokasi mengajar tidak valid.',
        ]);

        $user->update([
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ]);

        if ($tutor) {
            $tutor->update([
                'name'            => $request->name,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tanggal_lhr'     => $request->tanggal_lhr,
                'domisili'        => $request->domisili,
                'desc'            => $request->desc,
                'job'             => $request->job,
                'hourly_rate'     => $request->hourly_rate,
                'lokasi_mengajar' => $request->lokasi_mengajar,
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

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        $tutor = Auth::user()->tutor;

        if ($tutor && $tutor->foto) {
            Storage::disk('public')->delete($tutor->foto);
        }

        $path = $request->file('foto')->store('tutors/foto', 'public');

        if ($tutor) {
            $tutor->update(['foto' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    // ── Mata Pelajaran ───────────────────────────────────────

    public function storeSubject(Request $request)
    {
        $tutor = Auth::user()->tutor;

        $request->validate([
            'subject_category_id' => ['required', 'exists:subject_categories,id'],
            'tingkatan'           => ['required', 'array', 'min:1'],
            'tingkatan.*'         => ['string', 'in:SD,SMP,SMA,Kuliah'],
        ], [
            'subject_category_id.required' => 'Pilih mata pelajaran.',
            'subject_category_id.exists'   => 'Mata pelajaran tidak valid.',
            'tingkatan.required'           => 'Pilih minimal 1 tingkatan.',
            'tingkatan.min'                => 'Pilih minimal 1 tingkatan.',
        ]);

        $exists = TutorSubject::where('tutor_id', $tutor->id)
            ->where('subject_category_id', $request->subject_category_id)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Mata pelajaran ini sudah ditambahkan.');
        }

        TutorSubject::create([
            'tutor_id'            => $tutor->id,
            'subject_category_id' => $request->subject_category_id,
            'tingkatan'           => implode(',', $request->tingkatan),
        ]);

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function updateSubject(Request $request, TutorSubject $subject)
    {
        if ($subject->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $request->validate([
            'tingkatan'   => ['required', 'array', 'min:1'],
            'tingkatan.*' => ['string', 'in:SD,SMP,SMA,Kuliah'],
        ]);

        $subject->update([
            'tingkatan' => implode(',', $request->tingkatan),
        ]);

        return back()->with('success', 'Tingkatan berhasil diperbarui.');
    }

    public function destroySubject(TutorSubject $subject)
    {
        if ($subject->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $subject->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    // ── Pengalaman Mengajar ───────────────────────────────────

    public function storeExperience(Request $request)
    {
        $tutor = Auth::user()->tutor;

        $request->validate([
            'experience_years' => ['required', 'integer', 'min:0'],
            'periode'          => ['required', 'string', 'max:50'],
            'jumlah_siswa'     => ['required', 'integer', 'min:0'],
            'tempat'           => ['nullable', 'string', 'max:100'],
        ]);

        TutorProfile::create([
            'tutor_id'         => $tutor->id,
            'experience_years' => $request->experience_years,
            'periode'          => $request->periode,
            'jumlah_siswa'     => $request->jumlah_siswa,
            'tempat'           => $request->tempat,
        ]);

        return back()->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function updateExperience(Request $request, TutorProfile $experience)
    {
        if ($experience->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $request->validate([
            'experience_years' => ['required', 'integer', 'min:0'],
            'periode'          => ['required', 'string', 'max:50'],
            'jumlah_siswa'     => ['required', 'integer', 'min:0'],
            'tempat'           => ['nullable', 'string', 'max:100'],
        ]);

        $experience->update($request->only('experience_years', 'periode', 'jumlah_siswa', 'tempat'));

        return back()->with('success', 'Pengalaman berhasil diperbarui.');
    }

    public function destroyExperience(TutorProfile $experience)
    {
        if ($experience->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $experience->delete();

        return back()->with('success', 'Pengalaman berhasil dihapus.');
    }

    // ── Riwayat Pendidikan ────────────────────────────────────

    public function storeEducation(Request $request)
    {
        $tutor = Auth::user()->tutor;

        $request->validate([
            'jenjang' => ['required', 'string', 'max:20'],
            'sekolah' => ['required', 'string', 'max:50'],
            'jurusan' => ['required', 'string', 'max:50'],
            'periode' => ['required', 'string', 'max:50'],
        ]);

        StudiedHistory::create([
            'tutor_id' => $tutor->id,
            'jenjang'  => $request->jenjang,
            'sekolah'  => $request->sekolah,
            'jurusan'  => $request->jurusan,
            'periode'  => $request->periode,
        ]);

        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function updateEducation(Request $request, StudiedHistory $education)
    {
        if ($education->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $request->validate([
            'jenjang' => ['required', 'string', 'max:20'],
            'sekolah' => ['required', 'string', 'max:50'],
            'jurusan' => ['required', 'string', 'max:50'],
            'periode' => ['required', 'string', 'max:50'],
        ]);

        $education->update($request->only('jenjang', 'sekolah', 'jurusan', 'periode'));

        return back()->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function destroyEducation(StudiedHistory $education)
    {
        if ($education->tutor_id !== Auth::user()->tutor->id) {
            abort(403);
        }

        $education->delete();

        return back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}

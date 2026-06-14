<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $tutor     = Auth::user()->tutorProfile;
        abort_if(!$tutor, 404);

        $schedules = TutorSchedule::where('tutor_profile_id', $tutor->id)
            ->with(['bookings' => fn($q) => $q->whereIn('status', ['pending','confirmed'])])
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('tutor.jadwal', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari'        => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $tutor = Auth::user()->tutorProfile;
        abort_if(!$tutor, 403);

        // Cek duplikasi
        $exists = TutorSchedule::where('tutor_profile_id', $tutor->id)
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Slot jadwal untuk hari dan jam ini sudah ada.'
            ], 422);
        }

        $schedule = TutorSchedule::create([
            'tutor_profile_id' => $tutor->id,
            'hari'             => $request->hari,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'is_available'     => true,
            'is_repeat'        => $request->boolean('is_repeat'),
        ]);

        return response()->json([
            'message' => 'Slot jadwal berhasil ditambahkan.',
            'data'    => $schedule,
        ]);
    }

    public function destroy(int $id)
    {
        $tutor    = Auth::user()->tutorProfile;
        $schedule = TutorSchedule::where('id', $id)
            ->where('tutor_profile_id', $tutor->id)
            ->firstOrFail();

        // Cek apakah ada booking aktif
        $hasBooking = $schedule->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasBooking) {
            return response()->json([
                'message' => 'Slot ini tidak dapat dihapus karena sudah ada booking aktif.'
            ], 422);
        }

        $schedule->delete();

        return response()->json(['message' => 'Slot jadwal berhasil dihapus.']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        // $status = $request->input('status', 'all');

        // $query = TutorProfile::with(['user','mataPelajaran'])
        //     ->withCount('bookings');

        // if ($status !== 'all') $query->where('status', $status);

        // if ($request->filled('q')) {
        //     $q = $request->q;
        //     $query->whereHas('user', fn($uq) =>
        //         $uq->where('name','like',"%$q%")->orWhere('email','like',"%$q%")
        //     );
        // }

        // $tutors            = $query->orderByDesc('created_at')->paginate(15);
        // $pendingTutorCount = TutorProfile::where('status','pending')->count();

        $data = collect();
        return view('pages.admin.tutor', compact('data'));
    }

    public function verifikasi(TutorProfile $tutor)
    {
        abort_if($tutor->status !== 'pending', 422, 'Tutor sudah diverifikasi.');
        $tutor->update(['status' => 'active']);

        // Mail::to($tutor->user->email)->queue(new TutorVerifiedMail($tutor));

        return response()->json(['message' => 'Tutor berhasil diverifikasi.']);
    }

    public function tolak(TutorProfile $tutor)
    {
        $tutor->update(['status' => 'rejected']);
        return response()->json(['message' => 'Pendaftaran tutor ditolak.']);
    }

    public function destroy(TutorProfile $tutor)
    {
        $tutor->user->delete(); // soft delete cascade
        return response()->json(['message' => 'Akun tutor berhasil dihapus.']);
    }
}

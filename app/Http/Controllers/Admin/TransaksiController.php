<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user','tutorProfile.user','mataPelajaran'])
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->whereHas('user', fn($uq) =>
                    $uq->where('name','like',"%$q%")->orWhere('email','like',"%$q%")
                )->orWhereHas('tutorProfile.user', fn($uq) =>
                    $uq->where('name','like',"%$q%")
                );
            });
        }

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('mapel_id')) $query->where('mata_pelajaran_id', $request->mapel_id);
        if ($request->filled('from'))     $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))       $query->whereDate('created_at', '<=', $request->to);

        $bookings = $query->paginate(20);

        $stats = [
            'total'     => Booking::count(),
            'pending'   => Booking::where('status','pending')->count(),
            'confirmed' => Booking::where('status','confirmed')->count(),
            'completed' => Booking::where('status','completed')->count(),
        ];

        $mataPelajaran = MataPelajaran::orderBy('nama')->get();

        return view('admin.transaksi', compact('bookings','stats','mataPelajaran'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user','tutorProfile.user','mataPelajaran']);
        return view('admin.transaksi-detail', compact('booking'));
    }
}

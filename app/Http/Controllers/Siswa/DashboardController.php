<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function rekapKelas(Request $request)
    {
        $student = Auth::user()->student;

        if (! $student) {
            return view('pages.siswa.rekap-kelas', [
                'sessions' => collect(),
                'statusFilter' => 'all',
                'tutorName' => '',
                'startDate' => '',
                'endDate' => '',
            ]);
        }

        $statusFilter = in_array($request->input('status'), ['all', 'menunggu', 'selesai'], true)
            ? $request->input('status')
            : 'all';

        $tutorName = trim((string) $request->input('tutor', ''));
        $startDate = $request->input('tanggal_mulai');
        $endDate = $request->input('tanggal_selesai');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            if ($start->greaterThan($end)) {
                [$start, $end] = [$end, $start];
                $startDate = $start->toDateString();
                $endDate = $end->toDateString();
            }

            if ($end->diffInDays($start) > 30) {
                return back()->with('error', 'Rentang tanggal maksimal 1 bulan.');
            }
        }

        $query = OrderDetail::query()
            ->whereHas('order', function ($orderQuery) use ($student) {
                $orderQuery->where('student_id', $student->id)
                    ->where(function ($statusQuery) {
                        $statusQuery->where('status', 'pending')
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('status', 'confirmed')
                                    ->whereHas('payments', function ($paymentQuery) {
                                        $paymentQuery->where('status', 'paid');
                                    });
                            })
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('status', 'complete')
                                    ->whereHas('payments', function ($paymentQuery) {
                                        $paymentQuery->where('status', 'paid');
                                    });
                            });
                    });
            })
            ->with(['order.tutor', 'order.payments'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_start');

        if ($statusFilter === 'menunggu') {
            $query->where('status', 'pending');
        }

        if ($statusFilter === 'selesai') {
            $query->where('status', 'completed');
        }

        if ($tutorName !== '') {
            $query->whereHas('order.tutor', function ($tutorQuery) use ($tutorName) {
                $tutorQuery->where('name', 'like', '%' . $tutorName . '%');
            });
        }

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $sessions = $query->get()->map(function ($detail) {
            $detail->display_status = $detail->status === 'completed' ? 'Selesai' : 'Menunggu';
            $detail->display_kehadiran = $detail->kehadiran === 'hadir' ? ['label' => 'Hadir', 'icon' => 'bi-check-circle-fill', 'class' => 'success'] : ['label' => 'Tidak Hadir', 'icon' => 'bi-x-circle-fill', 'class' => 'danger'];
            $detail->display_time = $detail->jam_start . ' - ' . $detail->jam_end . ' WIB';
            $detail->display_tutor = $detail->order?->tutor?->name ?? '-';
            $detail->display_materi = $detail->materi ?: 'Belum ditentukan';
            $detail->display_tujuan = $detail->tujuan_pembelajaran ?: 'Belum dicatat oleh tutor';
            $detail->display_penilaian = $detail->penilaian ?: 'Belum ada penilaian tutor untuk sesi ini.';

            return $detail;
        });

        return view('pages.siswa.rekap-kelas', compact(
            'sessions',
            'statusFilter',
            'tutorName',
            'startDate',
            'endDate'
        ));
    }

    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return view('pages.siswa.dashboard', [
                'stats'            => ['total_booking' => 0, 'pending' => 0, 'completed' => 0, 'cancelled' => 0],
                'pendingCount'     => 0,
                'upcomingSessions' => collect(),
                'favoriteTutors'   => collect(),
            ]);
        }

        $stats = [
            'total_booking' => Order::where('student_id', $student->id)->count(),
            'pending'       => Order::where('student_id', $student->id)->whereEffectiveStatus('pending')->count(),
            'completed'     => Order::where('student_id', $student->id)->whereEffectiveStatus('complete')->count(),
            'cancelled'     => Order::where('student_id', $student->id)->whereEffectiveStatus('canceled')->count(),
        ];

        $pendingCount = $stats['pending'];

        $upcomingSessions = Order::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'pending')
                            ->where(function ($q3) {
                                $q3->whereNull('expired_at')
                                    ->orWhere('expired_at', '>=', now());
                            });
                    });
            })
            ->with(['tutor.user', 'tutor.tutorSubjects.subjectCategory', 'orderDetails'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($o) {
                $detail = $o->orderDetails->first();
                return [
                    'tutor_name' => $o->tutor->name ?? '-',
                    'mapel'      => $o->tutor->tutorSubjects->first()->subjectCategory->name ?? '-',
                    'hari'       => $detail ? Carbon::parse($detail->tanggal)->translatedFormat('l') : '-',
                    'jam'        => $detail ? $detail->jam_start : '-',
                    'status'     => $o->effective_status,
                ];
            })
            ->toArray();

        $favoriteTutors = Order::where('student_id', $student->id)
            ->selectRaw('tutor_id, COUNT(*) as total_sesi')
            ->groupBy('tutor_id')
            ->orderByDesc('total_sesi')
            ->limit(3)
            ->get()
            ->map(function ($row) {
                $tutor = \App\Models\Tutor::with('tutorSubjects.subjectCategory')->find($row->tutor_id);
                return [
                    'name'       => $tutor->name ?? '-',
                    'mapel'      => $tutor->tutorSubjects->first()->subjectCategory->name ?? '-',
                    'total_sesi' => $row->total_sesi,
                ];
            })
            ->toArray();

        return view('pages.siswa.dashboard', compact(
            'stats', 'pendingCount', 'upcomingSessions', 'favoriteTutors'
        ));
    }
}

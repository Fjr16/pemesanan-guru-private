<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ScheduleLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TutorManageClassController extends Controller
{
   public function index()
    {
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return view('pages.tutor.manage-class', [
                'orders' => [],
            ]);
        }

        $orders = Order::where('tutor_id', $tutor->id)
            ->whereIn('status', ['confirmed', 'complete'])
            ->whereHas('payments', function($item){
                return $item->where('status', 'paid');
            })
            ->with(['student.user', 'orderDetails'])
            ->latest()
            ->get();

        return view('pages.tutor.manage-class', compact('orders'));
    }

    public function show($id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->with(['student.user', 'orderDetails.tutorSchedule'])
            ->firstOrFail();

        return view('pages.tutor.manage-class-show', compact('order'));
    }

    public function selesai(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $tutor = Auth::user()->tutor;

            abort_unless($tutor, 404);

            $item = OrderDetail::findOrFail($id);
            $isPast = $item->tanggal . ' ' . $item->jam_end < now()->format('Y-m-d H:i:s');

            if (!$isPast) {
                return back()->with('error', 'Sesi belum selesai.');
            }

            $validators = Validator::make($request->all(), [
                'tujuan_pembelajaran' => 'required|string|max:500',
                'materi' => 'required|string|max:255',
                'penilaian' => 'required|string|max:500',
                'kehadiran' => 'required|in:hadir,alpha',
            ]);

            if ($validators->fails()) {
                return back()->with('error', $validators->errors()->first());
            }

            $item->update([
                'tujuan_pembelajaran' => $request->input('tujuan_pembelajaran'),
                'materi' => $request->input('materi'),
                'penilaian' => $request->input('penilaian'),
                'kehadiran' => $request->input('kehadiran'),
                'status' => 'completed',
            ]);

            ScheduleLock::where('order_detail_id', $item->id)
                ->where('status', 'locked')
                ->update(['status' => 'confirmed', 'expired_at' => null]);

            // Check if all order details are completed
            $allCompleted = $item->order->orderDetails->every(function ($detail) {
                return $detail->status === 'completed';
            });
            if ($allCompleted) {
                $item->order->update(['status' => 'complete']);

                // create rating for student if not exists
                $item->order->student->tutorRatings()->firstOrCreate([
                    'tutor_id' => $tutor->id,
                    'order_id' => $item->order->id,
                ], [
                    'status' => 'pending',
                    'expired_at' => now()->addDays(7),
                    'is_appear' => false,
                ]);
            }


            DB::commit();
            return back()->with('success', 'Sesi ditandai selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menandai sesi selesai.');
        }
    }
}

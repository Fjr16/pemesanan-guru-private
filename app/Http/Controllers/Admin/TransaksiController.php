<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['student.user', 'tutor', 'payments'])
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('student.user', fn ($s) => $s->where('username', 'like', "%{$q}%"))
                    ->orWhereHas('student', fn ($s) => $s->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('tutor', fn ($t) => $t->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'completed' => Order::where('status', 'complete')->count(),
        ];

        $bookings = $query->paginate(15)->withQueryString();

        return view('pages.admin.transaksi', compact('bookings', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with(['student.user', 'tutor.user', 'orderDetails', 'payments'])
            ->findOrFail($id);

        return view('pages.admin.transaksi-detail', compact('order'));
    }
}

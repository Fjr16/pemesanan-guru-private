<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'total_booking'   => 312,
            'completed'       => 268,
            'confirmed'       => 23,
            'pending'         => 15,
            'cancelled'       => 6,
            'new_users'       => 34,
            'total_transaksi' => 24650000,
        ];

        $topTutors = collect([
            (object)['name'=>'Ahmad Rifai','bookings_count'=>45,'rating'=>4.8],
            (object)['name'=>'Siti Nurhaliza','bookings_count'=>38,'rating'=>4.7],
            (object)['name'=>'Hendra Kusuma','bookings_count'=>32,'rating'=>4.6],
            (object)['name'=>'Budi Santoso','bookings_count'=>28,'rating'=>4.5],
            (object)['name'=>'Rina Wati','bookings_count'=>22,'rating'=>4.4],
        ]);

        return view('pages.admin.laporan', compact('stats','topTutors'));
    }
}

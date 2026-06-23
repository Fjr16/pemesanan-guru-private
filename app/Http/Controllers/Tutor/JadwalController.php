<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class JadwalController extends Controller
{
    public function index()
    {
        $schedules = collect([
            (object)['id'=>1, 'hari'=>'Senin','jam_mulai'=>'08:00','jam_selesai'=>'10:00','bookings_count'=>1],
            (object)['id'=>2, 'hari'=>'Senin','jam_mulai'=>'14:00','jam_selesai'=>'16:00','bookings_count'=>0],
            (object)['id'=>3, 'hari'=>'Selasa','jam_mulai'=>'09:00','jam_selesai'=>'11:00','bookings_count'=>0],
            (object)['id'=>4, 'hari'=>'Selasa','jam_mulai'=>'13:00','jam_selesai'=>'15:00','bookings_count'=>1],
            (object)['id'=>5, 'hari'=>'Rabu','jam_mulai'=>'10:00','jam_selesai'=>'12:00','bookings_count'=>0],
            (object)['id'=>6, 'hari'=>'Kamis','jam_mulai'=>'08:00','jam_selesai'=>'10:00','bookings_count'=>1],
            (object)['id'=>7, 'hari'=>'Kamis','jam_mulai'=>'16:00','jam_selesai'=>'18:00','bookings_count'=>0],
            (object)['id'=>8, 'hari'=>'Jumat','jam_mulai'=>'09:00','jam_selesai'=>'11:00','bookings_count'=>1],
            (object)['id'=>9, 'hari'=>'Sabtu','jam_mulai'=>'13:00','jam_selesai'=>'15:00','bookings_count'=>1],
        ]);

        return view('pages.tutor.jadwal', compact('schedules'));
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Slot jadwal berhasil ditambahkan.']);
    }

    public function destroy(int $id)
    {
        return response()->json(['message' => 'Slot jadwal berhasil dihapus.']);
    }
}

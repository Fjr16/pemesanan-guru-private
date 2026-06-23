<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'    => 48,
            'active'   => 45,
            'pending'  => 3,
            'rejected' => 0,
        ];

        $tutors = collect([
            (object)['id'=>1, 'name'=>'Ahmad Rifai','email'=>'ahmad.rifai@mail.com','hourly_rate'=>150000,'status'=>'pending','created_at'=>Carbon::now()->subDays(2),'subjects'=>collect([(object)['nama'=>'Matematika'],(object)['nama'=>'Fisika']])],
            (object)['id'=>2, 'name'=>'Siti Nurhaliza','email'=>'siti.nurhaliza@mail.com','hourly_rate'=>120000,'status'=>'pending','created_at'=>Carbon::now()->subDays(1),'subjects'=>collect([(object)['nama'=>'Bahasa Inggris']])],
            (object)['id'=>3, 'name'=>'Dwi Wahyuni','email'=>'dwi.wahyuni@mail.com','hourly_rate'=>130000,'status'=>'pending','created_at'=>Carbon::now(),'subjects'=>collect([(object)['nama'=>'Kimia'],(object)['nama'=>'Biologi']])],
            (object)['id'=>4, 'name'=>'Budi Santoso','email'=>'budi.santoso@mail.com','hourly_rate'=>180000,'status'=>'active','created_at'=>Carbon::now()->subMonth(),'subjects'=>collect([(object)['nama'=>'Matematika']])],
            (object)['id'=>5, 'name'=>'Rina Wati','email'=>'rina.wati@mail.com','hourly_rate'=>100000,'status'=>'active','created_at'=>Carbon::now()->subMonths(2),'subjects'=>collect([(object)['nama'=>'Bahasa Indonesia']])],
            (object)['id'=>6, 'name'=>'Hendra Kusuma','email'=>'hendra@mail.com','hourly_rate'=>200000,'status'=>'active','created_at'=>Carbon::now()->subMonths(3),'subjects'=>collect([(object)['nama'=>'Fisika'],(object)['nama'=>'Kimia']])],
            (object)['id'=>7, 'name'=>'Maya Anggraeni','email'=>'maya@mail.com','hourly_rate'=>160000,'status'=>'active','created_at'=>Carbon::now()->subMonths(1),'subjects'=>collect([(object)['nama'=>'Biologi']])],
            (object)['id'=>8, 'name'=>'Doni Firmansyah','email'=>'doni@mail.com','hourly_rate'=>140000,'status'=>'active','created_at'=>Carbon::now()->subMonths(4),'subjects'=>collect([(object)['nama'=>'Ekonomi'],(object)['nama'=>'Akuntansi']])],
            (object)['id'=>9, 'name'=>'Lestari Wulan','email'=>'lestari@mail.com','hourly_rate'=>170000,'status'=>'active','created_at'=>Carbon::now()->subMonths(2),'subjects'=>collect([(object)['nama'=>'Bahasa Inggris']])],
            (object)['id'=>10, 'name'=>'Fajar Nugroho','email'=>'fajar@mail.com','hourly_rate'=>190000,'status'=>'active','created_at'=>Carbon::now()->subMonths(5),'subjects'=>collect([(object)['nama'=>'Matematika'],(object)['nama'=>'Fisika']])],
        ]);

        return view('pages.admin.tutor', compact('tutors', 'stats'));
    }

    public function verifikasi($id)
    {
        return response()->json(['message' => 'Tutor berhasil diverifikasi.']);
    }

    public function tolak($id)
    {
        return response()->json(['message' => 'Pendaftaran tutor ditolak.']);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Akun tutor berhasil dihapus.']);
    }
}

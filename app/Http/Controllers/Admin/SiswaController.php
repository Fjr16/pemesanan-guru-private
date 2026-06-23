<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'        => 128,
            'active_month' => 87,
            'new_week'     => 12,
        ];

        $siswaList = collect([
            (object)['id'=>1, 'name'=>'Budi Santoso','email'=>'budi.siswa@mail.com','no_hp'=>'081234567890','orders_count'=>5,'created_at'=>Carbon::now()->subMonths(3)],
            (object)['id'=>2, 'name'=>'Anisa Putri','email'=>'anisa@mail.com','no_hp'=>'085678901234','orders_count'=>3,'created_at'=>Carbon::now()->subMonths(2)],
            (object)['id'=>3, 'name'=>'Rizky Pratama','email'=>'rizky@mail.com','no_hp'=>'087890123456','orders_count'=>8,'created_at'=>Carbon::now()->subMonths(4)],
            (object)['id'=>4, 'name'=>'Dewi Lestari','email'=>'dewi@mail.com','no_hp'=>'082134567890','orders_count'=>2,'created_at'=>Carbon::now()->subMonth()],
            (object)['id'=>5, 'name'=>'Farhan Maulana','email'=>'farhan@mail.com','no_hp'=>'089012345678','orders_count'=>1,'created_at'=>Carbon::now()->subWeek()],
            (object)['id'=>6, 'name'=>'Gita Puspita','email'=>'gita@mail.com','no_hp'=>'083456789012','orders_count'=>4,'created_at'=>Carbon::now()->subMonths(1)],
            (object)['id'=>7, 'name'=>'Hadi Wijaya','email'=>'hadi@mail.com','no_hp'=>'086789012345','orders_count'=>6,'created_at'=>Carbon::now()->subMonths(5)],
            (object)['id'=>8, 'name'=>'Indah Permata','email'=>'indah@mail.com','no_hp'=>'084567890123','orders_count'=>0,'created_at'=>Carbon::now()->subDays(3)],
            (object)['id'=>9, 'name'=>'Joko Susilo','email'=>'joko@mail.com','no_hp'=>'081987654321','orders_count'=>2,'created_at'=>Carbon::now()->subMonths(2)],
            (object)['id'=>10, 'name'=>'Kartika Sari','email'=>'kartika@mail.com','no_hp'=>'085123456789','orders_count'=>7,'created_at'=>Carbon::now()->subMonths(6)],
            (object)['id'=>11, 'name'=>'Lukman Hakim','email'=>'lukman@mail.com','no_hp'=>'087654321098','orders_count'=>1,'created_at'=>Carbon::now()->subDays(5)],
            (object)['id'=>12, 'name'=>'Mega Wati','email'=>'mega@mail.com','no_hp'=>'082345678901','orders_count'=>3,'created_at'=>Carbon::now()->subMonths(1)],
        ]);

        return view('pages.admin.siswa', compact('siswaList', 'stats'));
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }
}

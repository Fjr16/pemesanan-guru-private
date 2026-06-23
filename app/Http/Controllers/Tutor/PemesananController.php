<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'pending'   => 3,
            'confirmed' => 5,
            'completed' => 48,
            'cancelled' => 2,
        ];

        $allOrders = collect([
            (object)['id'=>1, 'siswa'=>'Budi Santoso','email'=>'budi@mail.com','no_hp'=>'081234567890','mapel'=>'Matematika','hari'=>'Senin','jam'=>'09:00','durasi'=>2,'total'=>300000,'status'=>'pending','catatan'=>'Mohon bantu saya persiapan ujian minggu depan.','waktu'=>'2 jam lalu','tarif'=>150000,'created_at'=>Carbon::now()->subHours(2),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            (object)['id'=>2, 'siswa'=>'Anisa Putri','email'=>'anisa@mail.com','no_hp'=>'085678901234','mapel'=>'Fisika','hari'=>'Selasa','jam'=>'14:00','durasi'=>1.5,'total'=>225000,'status'=>'pending','catatan'=>'','waktu'=>'5 jam lalu','tarif'=>150000,'created_at'=>Carbon::now()->subHours(5),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            (object)['id'=>3, 'siswa'=>'Rizky Pratama','email'=>'rizky@mail.com','no_hp'=>'087890123456','mapel'=>'Kimia','hari'=>'Rabu','jam'=>'10:00','durasi'=>2,'total'=>300000,'status'=>'pending','catatan'=>'Fokus pada materi stoikiometri.','waktu'=>'1 hari lalu','tarif'=>150000,'created_at'=>Carbon::now()->subDay(),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            (object)['id'=>4, 'siswa'=>'Dewi Lestari','email'=>'dewi@mail.com','no_hp'=>'082134567890','mapel'=>'Matematika','hari'=>'Kamis','jam'=>'16:00','durasi'=>2,'total'=>300000,'status'=>'confirmed','catatan'=>'','waktu'=>'3 hari lalu','tarif'=>150000,'created_at'=>Carbon::now()->subDays(3),'confirmed_at'=>Carbon::now()->subDays(2),'paid_at'=>null,'completed_at'=>null],
            (object)['id'=>5, 'siswa'=>'Farhan Maulana','email'=>'farhan@mail.com','no_hp'=>'089012345678','mapel'=>'Fisika','hari'=>'Jumat','jam'=>'08:00','durasi'=>2,'total'=>300000,'status'=>'confirmed','catatan'=>'','waktu'=>'4 hari lalu','tarif'=>150000,'created_at'=>Carbon::now()->subDays(4),'confirmed_at'=>Carbon::now()->subDays(3),'paid_at'=>null,'completed_at'=>null],
            (object)['id'=>6, 'siswa'=>'Gita Puspita','email'=>'gita@mail.com','no_hp'=>'083456789012','mapel'=>'Biologi','hari'=>'Sabtu','jam'=>'13:00','durasi'=>2,'total'=>300000,'status'=>'completed','catatan'=>'','waktu'=>'1 minggu lalu','tarif'=>150000,'created_at'=>Carbon::now()->subWeek(),'confirmed_at'=>Carbon::now()->subDays(6),'paid_at'=>Carbon::now()->subDays(5),'completed_at'=>Carbon::now()->subDays(5)],
            (object)['id'=>7, 'siswa'=>'Hadi Wijaya','email'=>'hadi@mail.com','no_hp'=>'086789012345','mapel'=>'Ekonomi','hari'=>'Senin','jam'=>'15:00','durasi'=>2,'total'=>300000,'status'=>'completed','catatan'=>'','waktu'=>'2 minggu lalu','tarif'=>150000,'created_at'=>Carbon::now()->subWeeks(2),'confirmed_at'=>Carbon::now()->subWeeks(2),'paid_at'=>Carbon::now()->subWeeks(2),'completed_at'=>Carbon::now()->subWeeks(2)],
            (object)['id'=>8, 'siswa'=>'Indah Permata','email'=>'indah@mail.com','no_hp'=>'084567890123','mapel'=>'Bahasa Inggris','hari'=>'Selasa','jam'=>'11:00','durasi'=>1,'total'=>150000,'status'=>'cancelled','catatan'=>'Jadwal bentrok.','waktu'=>'2 minggu lalu','tarif'=>150000,'created_at'=>Carbon::now()->subWeeks(2),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
        ]);

        // Filter by status
        $status = request('status', '');
        if ($status) {
            $orders = $allOrders->where('status', $status)->values();
        } else {
            $orders = $allOrders;
        }

        // Paginate manually
        $page = (int) request('page', 1);
        $perPage = 10;
        $total = $orders->count();
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $orders->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.tutor.pemesanan', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $orders = [
            1 => (object)['id'=>1, 'siswa'=>'Budi Santoso','email'=>'budi@mail.com','no_hp'=>'081234567890','mapel'=>'Matematika','hari'=>'Senin','jam'=>'09:00','durasi'=>2,'total'=>300000,'tarif'=>150000,'status'=>'pending','catatan'=>'Mohon bantu saya persiapan ujian minggu depan.','created_at'=>Carbon::now()->subHours(2),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            2 => (object)['id'=>2, 'siswa'=>'Anisa Putri','email'=>'anisa@mail.com','no_hp'=>'085678901234','mapel'=>'Fisika','hari'=>'Selasa','jam'=>'14:00','durasi'=>1.5,'total'=>225000,'tarif'=>150000,'status'=>'pending','catatan'=>'','created_at'=>Carbon::now()->subHours(5),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            3 => (object)['id'=>3, 'siswa'=>'Rizky Pratama','email'=>'rizky@mail.com','no_hp'=>'087890123456','mapel'=>'Kimia','hari'=>'Rabu','jam'=>'10:00','durasi'=>2,'total'=>300000,'tarif'=>150000,'status'=>'pending','catatan'=>'Fokus pada materi stoikiometri.','created_at'=>Carbon::now()->subDay(),'confirmed_at'=>null,'paid_at'=>null,'completed_at'=>null],
            4 => (object)['id'=>4, 'siswa'=>'Dewi Lestari','email'=>'dewi@mail.com','no_hp'=>'082134567890','mapel'=>'Matematika','hari'=>'Kamis','jam'=>'16:00','durasi'=>2,'total'=>300000,'tarif'=>150000,'status'=>'confirmed','catatan'=>'','created_at'=>Carbon::now()->subDays(3),'confirmed_at'=>Carbon::now()->subDays(2),'paid_at'=>null,'completed_at'=>null],
            5 => (object)['id'=>5, 'siswa'=>'Farhan Maulana','email'=>'farhan@mail.com','no_hp'=>'089012345678','mapel'=>'Fisika','hari'=>'Jumat','jam'=>'08:00','durasi'=>2,'total'=>300000,'tarif'=>150000,'status'=>'confirmed','catatan'=>'','created_at'=>Carbon::now()->subDays(4),'confirmed_at'=>Carbon::now()->subDays(3),'paid_at'=>null,'completed_at'=>null],
            6 => (object)['id'=>6, 'siswa'=>'Gita Puspita','email'=>'gita@mail.com','no_hp'=>'083456789012','mapel'=>'Biologi','hari'=>'Sabtu','jam'=>'13:00','durasi'=>2,'total'=>300000,'tarif'=>150000,'status'=>'completed','catatan'=>'','created_at'=>Carbon::now()->subWeek(),'confirmed_at'=>Carbon::now()->subDays(6),'paid_at'=>Carbon::now()->subDays(5),'completed_at'=>Carbon::now()->subDays(5)],
        ];

        $order = $orders[$id] ?? $orders[1];

        return view('pages.tutor.pemesanan-detail', compact('order'));
    }

    public function terima($id)
    {
        return redirect()->route('tutor.pemesanan')->with('success', 'Pesanan berhasil diterima!');
    }

    public function tolak(Request $request, $id)
    {
        return redirect()->route('tutor.pemesanan')->with('success', 'Pesanan ditolak.');
    }

    public function selesai($id)
    {
        return response()->json(['message' => 'Sesi ditandai selesai.']);
    }
}

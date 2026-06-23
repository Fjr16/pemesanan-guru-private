<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'     => 312,
            'pending'   => 15,
            'confirmed' => 23,
            'completed' => 268,
        ];

        $bookings = collect([
            (object)['id'=>1, 'student'=>(object)['name'=>'Budi Santoso','email'=>'budi@mail.com'], 'tutor'=>(object)['name'=>'Ahmad Rifai','email'=>'ahmad@mail.com'], 'scheduled_day'=>'Senin','scheduled_time'=>'09:00','total_price'=>300000,'status'=>'pending','payment_status'=>'unpaid','created_at'=>Carbon::now()->subHours(2)],
            (object)['id'=>2, 'student'=>(object)['name'=>'Anisa Putri','email'=>'anisa@mail.com'], 'tutor'=>(object)['name'=>'Siti Nurhaliza','email'=>'siti@mail.com'], 'scheduled_day'=>'Selasa','scheduled_time'=>'14:00','total_price'=>240000,'status'=>'confirmed','payment_status'=>'paid','created_at'=>Carbon::now()->subHours(5)],
            (object)['id'=>3, 'student'=>(object)['name'=>'Rizky Pratama','email'=>'rizky@mail.com'], 'tutor'=>(object)['name'=>'Budi Santoso','email'=>'budi.tutor@mail.com'], 'scheduled_day'=>'Rabu','scheduled_time'=>'10:00','total_price'=>360000,'status'=>'completed','payment_status'=>'paid','created_at'=>Carbon::now()->subDay()],
            (object)['id'=>4, 'student'=>(object)['name'=>'Dewi Lestari','email'=>'dewi@mail.com'], 'tutor'=>(object)['name'=>'Rina Wati','email'=>'rina@mail.com'], 'scheduled_day'=>'Kamis','scheduled_time'=>'16:00','total_price'=>200000,'status'=>'pending','payment_status'=>'unpaid','created_at'=>Carbon::now()->subDays(2)],
            (object)['id'=>5, 'student'=>(object)['name'=>'Farhan Maulana','email'=>'farhan@mail.com'], 'tutor'=>(object)['name'=>'Hendra Kusuma','email'=>'hendra@mail.com'], 'scheduled_day'=>'Jumat','scheduled_time'=>'08:00','total_price'=>400000,'status'=>'confirmed','payment_status'=>'pending_verification','created_at'=>Carbon::now()->subDays(2)],
            (object)['id'=>6, 'student'=>(object)['name'=>'Gita Puspita','email'=>'gita@mail.com'], 'tutor'=>(object)['name'=>'Maya Anggraeni','email'=>'maya@mail.com'], 'scheduled_day'=>'Sabtu','scheduled_time'=>'13:00','total_price'=>320000,'status'=>'completed','payment_status'=>'paid','created_at'=>Carbon::now()->subDays(3)],
            (object)['id'=>7, 'student'=>(object)['name'=>'Hadi Wijaya','email'=>'hadi@mail.com'], 'tutor'=>(object)['name'=>'Doni Firmansyah','email'=>'doni@mail.com'], 'scheduled_day'=>'Senin','scheduled_time'=>'15:00','total_price'=>280000,'status'=>'completed','payment_status'=>'paid','created_at'=>Carbon::now()->subDays(4)],
            (object)['id'=>8, 'student'=>(object)['name'=>'Indah Permata','email'=>'indah@mail.com'], 'tutor'=>(object)['name'=>'Lestari Wulan','email'=>'lestari@mail.com'], 'scheduled_day'=>'Selasa','scheduled_time'=>'11:00','total_price'=>340000,'status'=>'cancelled','payment_status'=>'unpaid','created_at'=>Carbon::now()->subDays(5)],
            (object)['id'=>9, 'student'=>(object)['name'=>'Joko Susilo','email'=>'joko@mail.com'], 'tutor'=>(object)['name'=>'Fajar Nugroho','email'=>'fajar@mail.com'], 'scheduled_day'=>'Rabu','scheduled_time'=>'09:00','total_price'=>380000,'status'=>'completed','payment_status'=>'paid','created_at'=>Carbon::now()->subDays(6)],
            (object)['id'=>10, 'student'=>(object)['name'=>'Kartika Sari','email'=>'kartika@mail.com'], 'tutor'=>(object)['name'=>'Ahmad Rifai','email'=>'ahmad@mail.com'], 'scheduled_day'=>'Kamis','scheduled_time'=>'14:00','total_price'=>300000,'status'=>'pending','payment_status'=>'unpaid','created_at'=>Carbon::now()->subWeek()],
        ]);

        $mataPelajaran = collect([
            (object)['id'=>1,'nama'=>'Matematika'],
            (object)['id'=>2,'nama'=>'Fisika'],
            (object)['id'=>3,'nama'=>'Kimia'],
            (object)['id'=>4,'nama'=>'Biologi'],
            (object)['id'=>5,'nama'=>'Bahasa Inggris'],
        ]);

        // Paginate manually
        $page = (int) request('page', 1);
        $perPage = 10;
        $total = $bookings->count();
        $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
            $bookings->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.admin.transaksi', compact('bookings','stats','mataPelajaran'));
    }

    public function show($id)
    {
        $order = (object)[
            'id' => $id,
            'status' => 'confirmed',
            'catatan' => 'Mohon bantu saya mempersiapkan ujian matematika minggu depan. Terima kasih.',
            'total_payment' => 300000,
            'confirmed_at' => Carbon::now()->subHours(3),
            'completed_at' => null,
            'created_at' => Carbon::now()->subDay(),
            'student' => (object)['name'=>'Budi Santoso','email'=>'budi@mail.com'],
            'tutor' => (object)['name'=>'Ahmad Rifai','email'=>'ahmad@mail.com'],
            'payment' => (object)['metode'=>'Transfer BCA','status'=>'paid','transactionId'=>'TXN-20260623-001','paid_at'=>Carbon::now()->subHours(1)],
            'details' => collect([
                (object)['tanggal'=>'2026-06-25','jam_start'=>'09:00','jam_end'=>'11:00','durasi'=>2,'harga'=>300000,'status'=>'confirmed'],
            ]),
        ];

        return view('pages.admin.transaksi-detail', compact('order'));
    }
}

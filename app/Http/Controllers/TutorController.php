<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TutorController extends Controller
{
    public function show($id)
    {
        $tutors = [
            1 => (object)[
                'id' => 1, 'status' => 'active', 'hourly_rate' => 150000, 'bio' => 'Tutor Matematika & Fisika berpengalaman 5 tahun. Spesialis persiapan ujian nasional dan SBMPTN.',
                'experience_years' => 5, 'lokasi_mengajar' => 'fleksibel', 'desc' => 'Saya adalah tutor dengan pengalaman 5 tahun mengajar Matematika dan Fisika untuk siswa SMA. Telah membantu ratusan siswa masuk PTN favorit melalui pendekatan konseptual dan latihan soal intensif.',
                'user' => (object)['name' => 'Ahmad Rifai', 'email' => 'ahmad@mail.com'],
                'mataPelajaran' => collect([(object)['nama' => 'Matematika'], (object)['nama' => 'Fisika']]),
                'reviews_count' => 45, 'reviews_avg_rating' => 4.8, 'session_count' => 38,
                'schedules' => collect([
                    (object)['id'=>1,'hari'=>'Senin','jam_mulai'=>'08:00','jam_selesai'=>'10:00','is_available'=>true],
                    (object)['id'=>2,'hari'=>'Senin','jam_mulai'=>'14:00','jam_selesai'=>'16:00','is_available'=>false],
                    (object)['id'=>3,'hari'=>'Selasa','jam_mulai'=>'09:00','jam_selesai'=>'11:00','is_available'=>true],
                    (object)['id'=>4,'hari'=>'Rabu','jam_mulai'=>'10:00','jam_selesai'=>'12:00','is_available'=>true],
                    (object)['id'=>5,'hari'=>'Kamis','jam_mulai'=>'08:00','jam_selesai'=>'10:00','is_available'=>false],
                    (object)['id'=>6,'hari'=>'Jumat','jam_mulai'=>'09:00','jam_selesai'=>'11:00','is_available'=>true],
                    (object)['id'=>7,'hari'=>'Sabtu','jam_mulai'=>'13:00','jam_selesai'=>'15:00','is_available'=>true],
                ]),
                'education' => collect([
                    (object)['sekolah'=>'Universitas Indonesia','jurusan'=>'Matematika','periode'=>'2014-2018'],
                    (object)['sekolah'=>'SMA Negeri 1 Jakarta','jurusan'=>'IPA','periode'=>'2011-2014'],
                ]),
            ],
            2 => (object)[
                'id' => 2, 'status' => 'active', 'hourly_rate' => 120000, 'bio' => 'Lulusan Sastra Inggris UI. Berpengalaman mengajar TOEFL dan IELTS.',
                'experience_years' => 4, 'lokasi_mengajar' => 'online', 'desc' => 'Lulusan Sastra Inggris Universitas Indonesia dengan skor IELTS 8.0. Berpengalaman mengajar TOEFL dan IELTS untuk semua level.',
                'user' => (object)['name' => 'Siti Nurhaliza', 'email' => 'siti@mail.com'],
                'mataPelajaran' => collect([(object)['nama' => 'Bahasa Inggris']]),
                'reviews_count' => 32, 'reviews_avg_rating' => 4.7, 'session_count' => 28,
                'schedules' => collect([
                    (object)['id'=>1,'hari'=>'Senin','jam_mulai'=>'10:00','jam_selesai'=>'12:00','is_available'=>true],
                    (object)['id'=>2,'hari'=>'Selasa','jam_mulai'=>'14:00','jam_selesai'=>'16:00','is_available'=>true],
                    (object)['id'=>3,'hari'=>'Kamis','jam_mulai'=>'10:00','jam_selesai'=>'12:00','is_available'=>false],
                    (object)['id'=>4,'hari'=>'Jumat','jam_mulai'=>'14:00','jam_selesai'=>'16:00','is_available'=>true],
                ]),
                'education' => collect([
                    (object)['sekolah'=>'Universitas Indonesia','jurusan'=>'Sastra Inggris','periode'=>'2015-2019'],
                ]),
            ],
        ];

        $tutor = $tutors[$id] ?? $tutors[1];

        $reviews = collect([
            (object)['id'=>1, 'rating'=>5, 'comment'=>'Penjelasan sangat jelas dan sabar. Nilai naik signifikan!','created_at'=>Carbon::now()->subWeek(), 'siswa'=>(object)['name'=>'Budi Santoso']],
            (object)['id'=>2, 'rating'=>5, 'comment'=>'Tutor terbaik yang pernah saya temui. Sangat recommended.','created_at'=>Carbon::now()->subWeeks(2), 'siswa'=>(object)['name'=>'Anisa Putri']],
            (object)['id'=>3, 'rating'=>4, 'comment'=>'Materi disampaikan dengan cara yang mudah dipahami.','created_at'=>Carbon::now()->subMonth(), 'siswa'=>(object)['name'=>'Rizky Pratama']],
            (object)['id'=>4, 'rating'=>5, 'comment'=>'Waktu belajar sangat efektif. Terima kasih!','created_at'=>Carbon::now()->subMonths(2), 'siswa'=>(object)['name'=>'Dewi Lestari']],
        ]);

        return view('show', compact('tutor', 'reviews'));
    }
}

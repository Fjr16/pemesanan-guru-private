<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TutorSearchController extends Controller
{
    private function allTutors(): array
    {
        return [
            ['id'=>1, 'name'=>'Ahmad Rifai', 'bio'=>'Tutor Matematika & Fisika berpengalaman 5 tahun. Spesialis persiapan ujian nasional dan SBMPTN.','hourly_rate'=>150000, 'rating'=>4.8, 'rating_count'=>45, 'session_count'=>38, 'subjects'=>['Matematika','Fisika'], 'experience'=>5],
            ['id'=>2, 'name'=>'Siti Nurhaliza', 'bio'=>'Lulusan Sastra Inggris UI. Berpengalaman mengajar TOEFL dan IELTS untuk semua level.','hourly_rate'=>120000, 'rating'=>4.7, 'rating_count'=>32, 'session_count'=>28, 'subjects'=>['Bahasa Inggris'], 'experience'=>4],
            ['id'=>3, 'name'=>'Hendra Kusuma', 'bio'=>'Dosen Fisika ITB. Mengajar dengan pendekatan konseptual dan problem-solving.','hourly_rate'=>200000, 'rating'=>4.9, 'rating_count'=>28, 'session_count'=>25, 'subjects'=>['Fisika','Kimia'], 'experience'=>7],
            ['id'=>4, 'name'=>'Budi Santoso', 'bio'=>'Ahli matematika dengan metode belajar menyenangkan. Garansi pemahaman materi.','hourly_rate'=>180000, 'rating'=>4.6, 'rating_count'=>22, 'session_count'=>20, 'subjects'=>['Matematika'], 'experience'=>5],
            ['id'=>5, 'name'=>'Rina Wati', 'bio'=>'Guru bahasa Indonesia berprestasi. Fokus pada penulisan kreatif dan pemahaman sastra.','hourly_rate'=>100000, 'rating'=>4.5, 'rating_count'=>18, 'session_count'=>15, 'subjects'=>['Bahasa Indonesia','Sejarah'], 'experience'=>3],
            ['id'=>6, 'name'=>'Maya Anggraeni', 'bio'=>'Dokter umum yang passionate mengajar biologi. Spesialis anatomi dan fisiologi.','hourly_rate'=>160000, 'rating'=>4.7, 'rating_count'=>20, 'session_count'=>18, 'subjects'=>['Biologi'], 'experience'=>4],
            ['id'=>7, 'name'=>'Doni Firmansyah', 'bio'=>'Praktisi akuntansi dengan pengalaman di Big 4. Mengajar akuntansi dasar hingga lanjutan.','hourly_rate'=>170000, 'rating'=>4.4, 'rating_count'=>15, 'session_count'=>12, 'subjects'=>['Ekonomi','Akuntansi'], 'experience'=>6],
            ['id'=>8, 'name'=>'Lestari Wulan', 'bio'=>'Native speaker English teacher with TESOL certification. Fun and interactive classes.','hourly_rate'=>190000, 'rating'=>4.8, 'rating_count'=>25, 'session_count'=>22, 'subjects'=>['Bahasa Inggris'], 'experience'=>5],
            ['id'=>9, 'name'=>'Fajar Nugroho', 'bio'=>'Software engineer yang passion mengajar coding. Python, JavaScript, dan data science.','hourly_rate'=>220000, 'rating'=>4.9, 'rating_count'=>12, 'session_count'=>10, 'subjects'=>['Informatika'], 'experience'=>4],
            ['id'=>10, 'name'=>'Kartika Sari', 'bio'=>'Seniman dan desainer grafis. Mengajar seni rupa, desain, dan kreativitas.','hourly_rate'=>130000, 'rating'=>4.3, 'rating_count'=>8, 'session_count'=>6, 'subjects'=>['Seni Budaya'], 'experience'=>3],
            ['id'=>11, 'name'=>'Agus Setiawan', 'bio'=>'Guru kimia berpengalaman. Metode eksperimen sederhana untuk pemahaman konsep.','hourly_rate'=>140000, 'rating'=>4.5, 'rating_count'=>16, 'session_count'=>14, 'subjects'=>['Kimia'], 'experience'=>4],
            ['id'=>12, 'name'=>'Dewi Anggraini', 'bio'=>'Geografer dan kartografer. Mengajar geografi dengan pendekatan visual dan GIS.','hourly_rate'=>110000, 'rating'=>4.2, 'rating_count'=>6, 'session_count'=>4, 'subjects'=>['Geografi'], 'experience'=>2],
        ];
    }

    public function search(Request $request): JsonResponse
    {
        $all = $this->allTutors();

        // Filter: mata pelajaran
        if ($request->filled('mata_pelajaran_id')) {
            $mapelId = (int) $request->mata_pelajaran_id;
            $mapelNames = [1=>'Matematika',2=>'Fisika',3=>'Kimia',4=>'Biologi',5=>'Bahasa Inggris',6=>'Bahasa Indonesia',7=>'Ekonomi',8=>'Informatika',9=>'Akuntansi',10=>'Sejarah',11=>'Geografi',12=>'Seni Budaya'];
            $filterName = $mapelNames[$mapelId] ?? '';
            if ($filterName) {
                $all = array_filter($all, fn($t) => in_array($filterName, $t['subjects']));
            }
        }

        // Sort
        switch ($request->input('sort', 'popular')) {
            case 'rating':
                usort($all, fn($a, $b) => $b['rating'] <=> $a['rating']);
                break;
            case 'price_asc':
                usort($all, fn($a, $b) => $a['hourly_rate'] <=> $b['hourly_rate']);
                break;
            case 'price_desc':
                usort($all, fn($a, $b) => $b['hourly_rate'] <=> $a['hourly_rate']);
                break;
            default:
                usort($all, fn($a, $b) => $b['session_count'] <=> $a['session_count']);
        }

        $perPage = 6;
        $page = max(1, (int) $request->input('page', 1));
        $total = count($all);
        $data = array_slice($all, ($page - 1) * $perPage, $perPage);

        return response()->json([
            'data'     => $data,
            'total'    => $total,
            'page'     => $page,
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    public function jadwal(Request $request, int $tutorId): JsonResponse
    {
        $allSchedules = [
            ['id'=>1, 'hari'=>'Senin','jam_mulai'=>'08:00','jam_selesai'=>'10:00','is_booked'=>false],
            ['id'=>2, 'hari'=>'Senin','jam_mulai'=>'14:00','jam_selesai'=>'16:00','is_booked'=>true],
            ['id'=>3, 'hari'=>'Selasa','jam_mulai'=>'09:00','jam_selesai'=>'11:00','is_booked'=>false],
            ['id'=>4, 'hari'=>'Rabu','jam_mulai'=>'10:00','jam_selesai'=>'12:00','is_booked'=>false],
            ['id'=>5, 'hari'=>'Kamis','jam_mulai'=>'08:00','jam_selesai'=>'10:00','is_booked'=>true],
            ['id'=>6, 'hari'=>'Kamis','jam_mulai'=>'16:00','jam_selesai'=>'18:00','is_booked'=>false],
            ['id'=>7, 'hari'=>'Jumat','jam_mulai'=>'09:00','jam_selesai'=>'11:00','is_booked'=>false],
            ['id'=>8, 'hari'=>'Sabtu','jam_mulai'=>'13:00','jam_selesai'=>'15:00','is_booked'=>false],
        ];

        return response()->json(['data' => $allSchedules]);
    }
}

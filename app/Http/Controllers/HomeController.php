<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $mataPelajaran = collect([
            (object)['id'=>1, 'nama'=>'Matematika','icon'=>'📐','tutor_count'=>12],
            (object)['id'=>2, 'nama'=>'Fisika','icon'=>'🔬','tutor_count'=>8],
            (object)['id'=>3, 'nama'=>'Kimia','icon'=>'🧪','tutor_count'=>6],
            (object)['id'=>4, 'nama'=>'Biologi','icon'=>'🧬','tutor_count'=>5],
            (object)['id'=>5, 'nama'=>'Bahasa Inggris','icon'=>'📖','tutor_count'=>10],
            (object)['id'=>6, 'nama'=>'Bahasa Indonesia','icon'=>'📝','tutor_count'=>4],
            (object)['id'=>7, 'nama'=>'Ekonomi','icon'=>'💰','tutor_count'=>3],
            (object)['id'=>8, 'nama'=>'Informatika','icon'=>'💻','tutor_count'=>7],
            (object)['id'=>9, 'nama'=>'Akuntansi','icon'=>'🧮','tutor_count'=>2],
            (object)['id'=>10, 'nama'=>'Sejarah','icon'=>'🏛️','tutor_count'=>1],
            (object)['id'=>11, 'nama'=>'Geografi','icon'=>'🗺️','tutor_count'=>1],
            (object)['id'=>12, 'nama'=>'Seni Budaya','icon'=>'🎨','tutor_count'=>2],
        ]);

        $stats = [
            'total_tutor' => 45,
            'total_sesi'  => 312,
            'kepuasan'    => 98,
        ];

        // Dummy tutors for server-side first paint
        $tutors = collect([
            $this->makeTutor(1, 'Ahmad Rifai', 150000, 4.8, 45, 38, ['Matematika','Fisika'], 'Tutor Matematika & Fisika berpengalaman 5 tahun. Spesialis persiapan ujian nasional dan SBMPTN.'),
            $this->makeTutor(2, 'Siti Nurhaliza', 120000, 4.7, 32, 28, ['Bahasa Inggris'], 'Lulusan Sastra Inggris UI. Berpengalaman mengajar TOEFL dan IELTS untuk semua level.'),
            $this->makeTutor(3, 'Hendra Kusuma', 200000, 4.9, 28, 25, ['Fisika','Kimia'], 'Dosen Fisika ITB. Mengajar dengan pendekatan konseptual dan problem-solving.'),
            $this->makeTutor(4, 'Budi Santoso', 180000, 4.6, 22, 20, ['Matematika'], 'Ahli matematika dengan metode belajar menyenangkan. Garansi pemahaman materi.'),
            $this->makeTutor(5, 'Rina Wati', 100000, 4.5, 18, 15, ['Bahasa Indonesia','Sejarah'], 'Guru bahasa Indonesia berprestasi. Fokus pada penulisan kreatif dan pemahaman sastra.'),
            $this->makeTutor(6, 'Maya Anggraeni', 160000, 4.7, 20, 18, ['Biologi'], 'Dokter umum yang passionate mengajar biologi. Spesialis anatomi dan fisiologi.'),
        ]);

        return view('home', compact('mataPelajaran', 'stats', 'tutors'));
    }

    private function makeTutor($id, $name, $rate, $rating, $ratingCount, $sessions, $subjects, $bio)
    {
        $t = new \stdClass();
        $t->id = $id;
        $t->user = (object)['name' => $name];
        $t->hourly_rate = $rate;
        $t->avg_rating = $rating;
        $t->review_count = $ratingCount;
        $t->session_count = $sessions;
        $t->experience_years = rand(2, 8);
        $t->bio = $bio;
        $t->mataPelajaran = collect(array_map(fn($s) => (object)['nama' => $s], $subjects));
        return $t;
    }
}

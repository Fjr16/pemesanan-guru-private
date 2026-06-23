<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $mataPelajaran = collect([
            (object)['id'=>1, 'nama'=>'Matematika','icon'=>'📐','deskripsi'=>'Aljabar, geometri, kalkulus, dan statistik.','is_active'=>true,'tutors_count'=>12,'created_at'=>Carbon::now()->subMonths(6)],
            (object)['id'=>2, 'nama'=>'Fisika','icon'=>'🔬','deskripsi'=>'Mekanika, termodinamika, optik, dan listrik.','is_active'=>true,'tutors_count'=>8,'created_at'=>Carbon::now()->subMonths(6)],
            (object)['id'=>3, 'nama'=>'Kimia','icon'=>'🧪','deskripsi'=>'Kimia organik, anorganik, dan analitik.','is_active'=>true,'tutors_count'=>6,'created_at'=>Carbon::now()->subMonths(5)],
            (object)['id'=>4, 'nama'=>'Biologi','icon'=>'🧬','deskripsi'=>'Sel, genetika, ekologi, dan anatomi.','is_active'=>true,'tutors_count'=>5,'created_at'=>Carbon::now()->subMonths(5)],
            (object)['id'=>5, 'nama'=>'Bahasa Inggris','icon'=>'📖','deskripsi'=>'Grammar, speaking, listening, dan writing.','is_active'=>true,'tutors_count'=>10,'created_at'=>Carbon::now()->subMonths(6)],
            (object)['id'=>6, 'nama'=>'Bahasa Indonesia','icon'=>'📝','deskripsi'=>'Tata bahasa, sastra, dan penulisan.','is_active'=>true,'tutors_count'=>4,'created_at'=>Carbon::now()->subMonths(4)],
            (object)['id'=>7, 'nama'=>'Ekonomi','icon'=>'💰','deskripsi'=>'Mikroekonomi, makroekonomi, dan akuntansi.','is_active'=>true,'tutors_count'=>3,'created_at'=>Carbon::now()->subMonths(3)],
            (object)['id'=>8, 'nama'=>'Akuntansi','icon'=>'🧮','deskripsi'=>'Akuntansi dasar, menengah, dan lanjutan.','is_active'=>true,'tutors_count'=>2,'created_at'=>Carbon::now()->subMonths(3)],
            (object)['id'=>9, 'nama'=>'Sejarah','icon'=>'🏛️','deskripsi'=>'Sejarah Indonesia dan dunia.','is_active'=>false,'tutors_count'=>1,'created_at'=>Carbon::now()->subMonths(2)],
            (object)['id'=>10, 'nama'=>'Geografi','icon'=>'🗺️','deskripsi'=>'Geografi fisik dan manusia.','is_active'=>false,'tutors_count'=>0,'created_at'=>Carbon::now()->subMonth()],
            (object)['id'=>11, 'nama'=>'Informatika','icon'=>'💻','deskripsi'=>'Pemrograman, algoritma, dan struktur data.','is_active'=>true,'tutors_count'=>7,'created_at'=>Carbon::now()->subMonths(4)],
            (object)['id'=>12, 'nama'=>'Seni Budaya','icon'=>'🎨','deskripsi'=>'Seni rupa, musik, dan teater.','is_active'=>true,'tutors_count'=>2,'created_at'=>Carbon::now()->subMonths(2)],
        ]);

        return view('pages.admin.mata-pelajaran', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => ['required','string','max:100'],
            'icon'      => ['nullable','string','max:10'],
            'deskripsi' => ['nullable','string','max:300'],
            'is_active' => ['nullable','boolean'],
        ]);

        return response()->json(['message' => 'Mata pelajaran berhasil ditambahkan.', 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('PATCH')) {
            return response()->json(['message' => 'Status berhasil diperbarui.']);
        }

        return response()->json(['message' => 'Mata pelajaran berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Mata pelajaran berhasil dihapus.']);
    }
}

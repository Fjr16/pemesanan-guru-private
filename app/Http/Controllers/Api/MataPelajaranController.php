<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MataPelajaranController extends Controller
{
    public function index(): JsonResponse
    {
        $data = collect([
            ['id'=>1, 'nama'=>'Matematika','icon'=>'📐'],
            ['id'=>2, 'nama'=>'Fisika','icon'=>'🔬'],
            ['id'=>3, 'nama'=>'Kimia','icon'=>'🧪'],
            ['id'=>4, 'nama'=>'Biologi','icon'=>'🧬'],
            ['id'=>5, 'nama'=>'Bahasa Inggris','icon'=>'📖'],
            ['id'=>6, 'nama'=>'Bahasa Indonesia','icon'=>'📝'],
            ['id'=>7, 'nama'=>'Ekonomi','icon'=>'💰'],
            ['id'=>8, 'nama'=>'Informatika','icon'=>'💻'],
            ['id'=>9, 'nama'=>'Akuntansi','icon'=>'🧮'],
            ['id'=>10, 'nama'=>'Sejarah','icon'=>'🏛️'],
            ['id'=>11, 'nama'=>'Geografi','icon'=>'🗺️'],
            ['id'=>12, 'nama'=>'Seni Budaya','icon'=>'🎨'],
        ]);

        return response()->json(['data' => $data]);
    }
}

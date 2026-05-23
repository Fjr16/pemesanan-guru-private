<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;

class MataPelajaranController extends Controller
{
    /**
     * GET /api/mata-pelajaran
     * Mengembalikan semua mata pelajaran (untuk dropdown register tutor)
     */
    public function index(): JsonResponse
    {
        // $data = MataPelajaran::orderBy('nama')
        //     ->get(['id', 'nama', 'icon']);
        $data = collect();

        return response()->json(['data' => $data]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectCategory;
use Illuminate\Http\JsonResponse;

class MataPelajaranController extends Controller
{
    public function index(): JsonResponse
    {
        $data = SubjectCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name as nama', 'foto as icon']);

        return response()->json(['data' => $data]);
    }
}

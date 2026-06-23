<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'        => User::where('role', 'siswa')->count(),
            'active_month' => User::where('role', 'siswa')
                ->where('updated_at', '>=', now()->subMonth())
                ->count(),
            'new_week'     => User::where('role', 'siswa')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
        ];

        $siswaList = User::where('role', 'siswa')
            ->withCount('studentOrders')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin.siswa', compact('siswaList', 'stats'));
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = User::where('role', 'siswa')->findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Siswa berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus siswa.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }
}

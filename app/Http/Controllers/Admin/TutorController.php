<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total'    => Tutor::count(),
            'active'   => Tutor::where('status', 'active')->count(),
            'pending'  => Tutor::where('status', 'pending')->count(),
            'rejected' => Tutor::where('status', 'rejected')->count(),
        ];

        $tutors = Tutor::with(['user', 'tutorSubjects.subjectCategory'])
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin.tutor', compact('tutors', 'stats'));
    }

    public function verifikasi($id): JsonResponse
    {
        try {
            $tutor = Tutor::findOrFail($id);
            $tutor->status = 'active';
            $tutor->save();

            return response()->json([
                'status' => true,
                'message' => 'Tutor berhasil diverifikasi.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memverifikasi tutor.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    public function tolak($id): JsonResponse
    {
        try {
            $tutor = Tutor::findOrFail($id);
            $tutor->status = 'rejected';
            $tutor->save();

            return response()->json([
                'status' => true,
                'message' => 'Pendaftaran tutor ditolak.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menolak tutor.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $tutor = Tutor::findOrFail($id);
            $tutor->status = $request->boolean('is_active') ? 'active' : 'rejected';
            $tutor->save();

            return response()->json([
                'status' => true,
                'message' => $tutor->status === 'active'
                    ? 'Tutor berhasil diaktifkan.'
                    : 'Tutor berhasil dinonaktifkan.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah status tutor.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $tutor = Tutor::findOrFail($id);
            $tutor->delete();

            return response()->json([
                'status' => true,
                'message' => 'Tutor berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus tutor.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }
}

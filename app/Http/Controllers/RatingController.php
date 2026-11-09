<?php

namespace App\Http\Controllers;

use App\Models\TutorRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getPendingRating()
    {
        $item = TutorRating::where('student_id', auth()->user()->student->id)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->where('is_appear', false)
            ->latest()
            ->first();

        if(!$item) return response()->json(['data' => null]);

        // agar pop up rating hanya dimunculkan satu kali saja
        $item->update(['is_appear' => true]);

        return response()->json([
            'data' => [
                'id' => $item->id,
                'tutor_name' => $item->tutor?->name ?? '-',
                'foto' => $item->tutor->foto,
            ]
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TutorRating $rating)
    {
        abort_unless(auth()->user()->student->id === $rating->student_id, 403);
        abort_unless($rating->status === 'pending' && $rating->expired_at->isFeature(), 422, 'proses rating telah berakhir');

        $validators = Validator::make($request->all(), [
            'score' => 'required|integer|between:1,5',
            'ulasan' => 'nullable|string|',
        ]);

        if($validators->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan Validasi',
                'errors' => $validators->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $rating->update([
                'score' => $request->score,
                'ulasan' => $request->ulasan,
                'status' => 'rated',
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil',
                'errors' => []
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan, hubungi developer',
                'errors' => [$th->getMessage()]
            ]);
        }
    }
}

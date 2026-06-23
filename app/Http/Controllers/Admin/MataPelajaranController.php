<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $mataPelajaran = SubjectCategory::withCount('tutorSubjects as tutors_count')
            ->orderBy('name')
            ->get();

        return view('pages.admin.mata-pelajaran', compact('mataPelajaran'));
    }

    public function store(Request $request): JsonResponse
    {
        $validators = Validator::make($request->all(), [
            'subject_category_id' => ['nullable', 'exists:subject_categories,id'],
            'nama'      => ['required', 'string', 'max:100'],
            'icon'      => ['nullable', 'string', 'max:10'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ], [
            'nama.required'  => 'Nama mata pelajaran wajib diisi.',
            'nama.string'    => 'Nama mata pelajaran harus berupa teks.',
            'nama.max'       => 'Nama mata pelajaran maksimal 100 karakter.',
            'icon.string'    => 'Icon harus berupa teks.',
            'icon.max'       => 'Icon maksimal 10 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max'  => 'Deskripsi maksimal 255 karakter.',
            'is_active.required' => 'Status aktif wajib dipilih.',
            'is_active.boolean'  => 'Status aktif tidak valid.',
            'subject_category_id.exists' => 'Mata pelajaran tidak ditemukan.',
        ]);

        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Periksa kembali data yang dimasukkan.',
                'errors' => $validators->errors(),
            ], 422);
        }

        try {
            $isUpdate = (bool) $request->subject_category_id;

            $item = $isUpdate
                ? SubjectCategory::findOrFail($request->subject_category_id)
                : new SubjectCategory;

            $item->name = $request->nama;
            $item->foto = $request->icon;
            $item->deskripsi = $request->deskripsi;
            $item->is_active = $request->is_active;
            $item->save();

            return response()->json([
                'status' => true,
                'message' => $isUpdate
                    ? 'Mata pelajaran berhasil diperbarui.'
                    : 'Mata pelajaran berhasil ditambahkan.',
                'data' => $item,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $item = SubjectCategory::findOrFail($id);
            $item->is_active = $request->boolean('is_active');
            $item->save();

            return response()->json([
                'status' => true,
                'message' => $item->is_active
                    ? 'Mata pelajaran berhasil diaktifkan.'
                    : 'Mata pelajaran berhasil dinonaktifkan.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah status mata pelajaran.',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }
}

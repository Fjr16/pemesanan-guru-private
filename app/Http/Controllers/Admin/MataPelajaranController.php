<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\SubjectCategory;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        // $mataPelajaran = SubjectCategory::withCount(['tutors','bookings'])
        //     ->orderBy('nama')
        //     ->paginate(20);
        $mataPelajaran = collect();

        return view('pages.admin.mata-pelajaran', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => ['required','string','max:100','unique:mata_pelajarans,nama'],
            'icon'      => ['nullable','string','max:10'],
            'deskripsi' => ['nullable','string','max:300'],
            'is_active' => ['nullable','boolean'],
        ]);

        $mp = MataPelajaran::create([
            'nama'      => $data['nama'],
            'icon'      => $data['icon'] ?? '📚',
            'deskripsi' => $data['deskripsi'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json(['message' => 'Mata pelajaran berhasil ditambahkan.', 'data' => $mp]);
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        // Izinkan PATCH hanya untuk toggle is_active
        if ($request->isMethod('PATCH')) {
            $mataPelajaran->update(['is_active' => $request->boolean('is_active')]);
            return response()->json(['message' => 'Status berhasil diperbarui.']);
        }

        $data = $request->validate([
            'nama'      => ['required','string','max:100','unique:mata_pelajarans,nama,'.$mataPelajaran->id],
            'icon'      => ['nullable','string','max:10'],
            'deskripsi' => ['nullable','string','max:300'],
            'is_active' => ['nullable','boolean'],
        ]);

        $mataPelajaran->update($data);

        return response()->json(['message' => 'Mata pelajaran berhasil diperbarui.', 'data' => $mataPelajaran]);
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->tutors()->detach();
        $mataPelajaran->delete();

        return response()->json(['message' => 'Mata pelajaran berhasil dihapus.']);
    }
}

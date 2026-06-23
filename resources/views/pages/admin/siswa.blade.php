@extends('layouts.admin')

@section('title', 'Siswa — Admin TutorKu')

@section('content')

{{-- ── Page header ──────────────────────────────────────────── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px;flex-wrap:wrap;">
    <div>
        <h1 style="font-size:16px;font-weight:600;margin:0 0 2px;color:#1a1a2e">Siswa</h1>
        <p style="font-size:13px;color:#8890a8;margin:0">Kelola data siswa yang terdaftar di platform.</p>
    </div>
</div>

{{-- ── Stats row ─────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-people-fill" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Siswa</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-person-check-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Aktif Bulan Ini</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['active_month'] }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-person-plus-fill" style="color:#1e40af;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Baru Minggu Ini</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['new_week'] }}</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ───────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:12px;flex-wrap:wrap;">
    <div style="position:relative;max-width:300px;width:100%">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8890a8;font-size:13px;pointer-events:none"></i>
        <input type="text"
               id="searchSiswa"
               placeholder="Cari nama atau email siswa..."
               style="width:100%;height:34px;padding:0 12px 0 32px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;transition:border-color .15s;"
               onfocus="this.style.borderColor='#a5b4fc'"
               onblur="this.style.borderColor='#e8eaf0'">
    </div>
    <p style="font-size:12px;color:#8890a8;margin:0;white-space:nowrap" id="countLabel">
        0 siswa
    </p>
</div>

{{-- ── Table panel ───────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;overflow:hidden">
    <div class="table-responsive">
        <table style="width:100%;font-size:13px;border-collapse:collapse" id="siswaTable">
            <thead>
                <tr>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:44px;">#</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">Siswa</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">No. HP</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:80px;">Booking</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:80px;">Daftar</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:70px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $siswa)
                    <tr class="siswa-row"
                        data-nama="{{ strtolower($siswa->username) }}"
                        style="transition:background .1s"
                        onmouseover="this.style.background='#fafbff'"
                        onmouseout="this.style.background=''">
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;color:#8890a8;font-size:12px;vertical-align:middle">
                            {{ $loop->iteration }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:36px;height:36px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
                                    {{ strtoupper(substr($siswa->username, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $siswa->username }}</div>
                                    <div style="font-size:11px;color:#8890a8;">{{ $siswa->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;font-size:13px;color:#4b5574;">
                            {{ $siswa->no_hp ?? '-' }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                {{ $siswa->student_orders_count ?? 0 }}x
                            </span>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;font-size:12px;color:#8890a8;white-space:nowrap;">
                            {{ $siswa->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <button type="button"
                                    class="tk-action-btn btn-delete-siswa"
                                    data-id="{{ $siswa->id }}"
                                    data-nama="{{ $siswa->username }}"
                                    title="Hapus"
                                    style="border-color:#fecaca;background:#fef2f2;color:#991b1b">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="6" style="padding:52px 0;text-align:center;color:#8890a8">
                            <i class="bi bi-people" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px"></i>
                            <p style="font-size:13px;margin:0">Belum ada siswa terdaftar.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Toast container --}}
<div id="toastContainer"
     style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px">
</div>

{{-- ================================================================
     MODAL KONFIRMASI HAPUS
================================================ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-trash3" style="font-size:22px;color:#991b1b"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">Hapus Siswa?</h5>
                <p style="font-size:13px;font-weight:500;color:#4b5574;margin:0 0 4px" id="deleteSiswaName"></p>
                <p style="font-size:12px;color:#991b1b;margin:0 0 24px;">Data siswa dan riwayat pemesanannya akan dihapus.</p>
                <input type="hidden" id="deleteSiswaId">
                <div style="display:flex;gap:8px">
                    <button type="button" class="tk-topbar-btn" style="flex:1;justify-content:center;color:#4b5574" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmDelete"
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#991b1b;color:#fff;transition:background .15s">
                        <i class="bi bi-trash3"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    /* ── Toast helper ──────────────────────────────────────── */
    function showToast(msg, type = 'success') {
        const colors = type === 'success'
            ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0'
            : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca';
        const icon = type === 'success'
            ? '<i class="bi bi-check-circle-fill"></i>'
            : '<i class="bi bi-exclamation-circle-fill"></i>';
        const t = document.createElement('div');
        t.style.cssText = `display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;min-width:240px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1);animation:slideUp .2s ease;${colors}`;
        t.innerHTML = icon + ' ' + msg;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
    }

    /* ── Search ───────────────────────────────────────────── */
    document.getElementById('searchSiswa').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.siswa-row').forEach(row => {
            const match = (row.dataset.nama || '').includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        document.getElementById('countLabel').textContent = visible + ' siswa';
    });

    /* ── Hapus ─────────────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-siswa');
        if (!btn) return;
        document.getElementById('deleteSiswaId').value = btn.dataset.id;
        document.getElementById('deleteSiswaName').textContent = '"' + btn.dataset.nama + '"';
        deleteModal.show();
    });
    document.getElementById('confirmDelete').addEventListener('click', function () {
        const id = document.getElementById('deleteSiswaId').value;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menghapus...';
        fetch(`/admin/siswa/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) throw data;
            deleteModal.hide();
            showToast(data.message);
            setTimeout(() => location.reload(), 700);
        })
        .catch(err => showToast(err?.message || 'Gagal menghapus siswa.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-trash3"></i> Hapus'; });
    });

});
</script>

<style>
    .form-check-input:checked { background-color:#1e2d6b; border-color:#1e2d6b; }
    .form-check-input:focus   { box-shadow:0 0 0 3px rgba(30,45,107,.12); }
    @keyframes slideUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin    { to { transform:rotate(360deg); } }
    .btn-delete-siswa:hover:not(:disabled) { background:#fee2e2 !important; }
</style>
@endpush

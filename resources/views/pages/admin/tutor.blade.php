@extends('layouts.admin')

@section('title', 'Tutor — Admin TutorKu')
@section('page-title', 'Manajemen Tutor')

@section('content')

{{-- ── Page header ──────────────────────────────────────────── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px;flex-wrap:wrap;">
    <div>
        <h1 style="font-size:16px;font-weight:600;margin:0 0 2px;color:#1a1a2e">Tutor</h1>
        <p style="font-size:13px;color:#8890a8;margin:0">Kelola data tutor, verifikasi pendaftaran, dan status akun.</p>
    </div>
</div>

{{-- ── Stats row ─────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-mortarboard-fill" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Tutor</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-check-circle-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Aktif</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['active'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-hourglass-split" style="color:#b45309;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Pending</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['pending'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-x-circle-fill" style="color:#991b1b;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Ditolak</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['rejected'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ───────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:12px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <div style="position:relative;max-width:260px;width:100%">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8890a8;font-size:13px;pointer-events:none"></i>
            <input type="text"
                   id="searchTutor"
                   placeholder="Cari nama atau email tutor..."
                   style="width:100%;height:34px;padding:0 12px 0 32px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;transition:border-color .15s;"
                   onfocus="this.style.borderColor='#a5b4fc'"
                   onblur="this.style.borderColor='#e8eaf0'">
        </div>
        <select id="filterStatus"
                style="height:34px;padding:0 28px 0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;cursor:pointer;appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238890a8' d='M6 8L1 3h10z'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="pending">Pending</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>
    <p style="font-size:12px;color:#8890a8;margin:0;white-space:nowrap" id="countLabel">
        0 tutor
    </p>
</div>

{{-- ── Table panel ───────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;overflow:hidden">
    <div class="table-responsive">
        <table style="width:100%;font-size:13px;border-collapse:collapse" id="tutorTable">
            <thead>
                <tr>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:44px;">#</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">Tutor</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">Mata Pelajaran</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:90px;">Tarif/Jam</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:80px;">Status</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:80px;">Daftar</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tutors as $tutor)
                    <tr class="tutor-row"
                        data-nama="{{ strtolower($tutor->name ?? '') }}"
                        data-status="{{ $tutor->status ?? 'pending' }}"
                        style="transition:background .1s"
                        onmouseover="this.style.background='#fafbff'"
                        onmouseout="this.style.background=''">
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;color:#8890a8;font-size:12px;vertical-align:middle">
                            {{ $loop->iteration }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:36px;height:36px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
                                    {{ strtoupper(substr($tutor->name ?? 'T', 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $tutor->name ?? '-' }}</div>
                                    <div style="font-size:11px;color:#8890a8;">{{ $tutor->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach(($tutor->subjects ?? []) as $subj)
                                    <span style="display:inline-flex;align-items:center;gap:3px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:500;padding:2px 8px;border-radius:12px;">
                                        {{ $subj->nama ?? $subj }}
                                    </span>
                                @endforeach
                                @if(empty($tutor->subjects) || count($tutor->subjects) === 0)
                                    <span style="font-size:11px;color:#b0b8cc;">-</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;white-space:nowrap;">
                            <span style="font-weight:500;font-size:13px;color:#1a1a2e;">Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            @php $st = $tutor->status ?? 'pending'; @endphp
                            @if($st === 'active')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Aktif
                                </span>
                            @elseif($st === 'pending')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-hourglass-split" style="font-size:10px;"></i> Pending
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#991b1b;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;font-size:12px;color:#8890a8;white-space:nowrap;">
                            {{ $tutor->created_at ? $tutor->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div style="display:flex;gap:6px;">
                                @if($st === 'pending')
                                    <button type="button"
                                            class="btn-verif-tutor"
                                            data-id="{{ $tutor->id }}"
                                            data-nama="{{ $tutor->name ?? '' }}"
                                            style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid #a7f3d0;background:#f0fdf4;color:#15803d;transition:background .15s;"
                                            onmouseover="this.style.background='#dcfce7'"
                                            onmouseout="this.style.background='#f0fdf4'"
                                            title="Verifikasi">
                                        <i class="bi bi-check-lg" style="font-size:11px;"></i> Verifikasi
                                    </button>
                                    <button type="button"
                                            class="btn-tolak-tutor"
                                            data-id="{{ $tutor->id }}"
                                            data-nama="{{ $tutor->name ?? '' }}"
                                            style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid #fecaca;background:#fef2f2;color:#991b1b;transition:background .15s;"
                                            onmouseover="this.style.background='#fee2e2'"
                                            onmouseout="this.style.background='#fef2f2'"
                                            title="Tolak">
                                        <i class="bi bi-x-lg" style="font-size:11px;"></i>
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn-detail-tutor"
                                            data-id="{{ $tutor->id }}"
                                            style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid #d0d5e8;background:#fff;color:#1e2d6b;transition:background .15s;"
                                            onmouseover="this.style.background='#f0f3ff'"
                                            onmouseout="this.style.background='#fff'"
                                            title="Detail">
                                        <i class="bi bi-eye" style="font-size:11px;"></i> Detail
                                    </button>
                                @endif
                                <button type="button"
                                        class="btn-delete-tutor"
                                        data-id="{{ $tutor->id }}"
                                        data-nama="{{ $tutor->name ?? '' }}"
                                        style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid #fecaca;background:#fef2f2;color:#991b1b;transition:background .15s;"
                                        onmouseover="this.style.background='#fee2e2'"
                                        onmouseout="this.style.background='#fef2f2'"
                                        title="Hapus">
                                    <i class="bi bi-trash" style="font-size:11px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="7" style="padding:52px 0;text-align:center;color:#8890a8">
                            <i class="bi bi-mortarboard" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px"></i>
                            <p style="font-size:13px;margin:0">Belum ada tutor terdaftar.</p>
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
     MODAL KONFIRMASI VERIFIKASI
=============================================================== --}}
<div class="modal fade" id="verifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-shield-check" style="font-size:22px;color:#15803d"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">Verifikasi Tutor?</h5>
                <p style="font-size:13px;color:#4b5574;margin:0 0 4px" id="verifTutorName"></p>
                <p style="font-size:12px;color:#8890a8;margin:0 0 24px;">Tutor akan menerima notifikasi dan langsung aktif di platform.</p>
                <input type="hidden" id="verifTutorId">
                <div style="display:flex;gap:8px">
                    <button type="button" class="tk-topbar-btn" style="flex:1;justify-content:center;color:#4b5574" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmVerif"
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#15803d;color:#fff;transition:background .15s">
                        <i class="bi bi-check-lg"></i> Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL KONFIRMASI TOLAK
=============================================================== --}}
<div class="modal fade" id="tolakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fffbeb;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-x-circle" style="font-size:22px;color:#b45309"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">Tolak Tutor?</h5>
                <p style="font-size:13px;color:#4b5574;margin:0 0 4px" id="tolakTutorName"></p>
                <p style="font-size:12px;color:#8890a8;margin:0 0 24px;">Pendaftaran tutor akan ditolak.</p>
                <input type="hidden" id="tolakTutorId">
                <div style="display:flex;gap:8px">
                    <button type="button" class="tk-topbar-btn" style="flex:1;justify-content:center;color:#4b5574" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmTolak"
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#b45309;color:#fff;transition:background .15s">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL KONFIRMASI HAPUS
=============================================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-trash3" style="font-size:22px;color:#991b1b"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">Hapus Tutor?</h5>
                <p style="font-size:13px;font-weight:500;color:#4b5574;margin:0 0 4px" id="deleteTutorName"></p>
                <p style="font-size:12px;color:#991b1b;margin:0 0 24px;">Tindakan ini tidak dapat dibatalkan.</p>
                <input type="hidden" id="deleteTutorId">
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

    const verifModal = new bootstrap.Modal(document.getElementById('verifModal'));
    const tolakModal = new bootstrap.Modal(document.getElementById('tolakModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

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

    /* ── Search + filter ──────────────────────────────────── */
    function applyFilters() {
        const q = document.getElementById('searchTutor').value.toLowerCase().trim();
        const status = document.getElementById('filterStatus').value;
        let visible = 0;
        document.querySelectorAll('.tutor-row').forEach(row => {
            const matchName = (row.dataset.nama || '').includes(q);
            const matchStatus = !status || row.dataset.status === status;
            row.style.display = (matchName && matchStatus) ? '' : 'none';
            if (matchName && matchStatus) visible++;
        });
        document.getElementById('countLabel').textContent = visible + ' tutor';
    }
    document.getElementById('searchTutor').addEventListener('input', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);

    /* ── Verifikasi ───────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-verif-tutor');
        if (!btn) return;
        document.getElementById('verifTutorId').value = btn.dataset.id;
        document.getElementById('verifTutorName').textContent = btn.dataset.nama;
        verifModal.show();
    });
    document.getElementById('confirmVerif').addEventListener('click', function () {
        const id = document.getElementById('verifTutorId').value;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Memproses...';
        fetch(`/admin/tutor/${id}/verifikasi`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { verifModal.hide(); showToast('Tutor berhasil diverifikasi!'); setTimeout(() => location.reload(), 700); })
        .catch(() => showToast('Gagal memverifikasi tutor.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg"></i> Verifikasi'; });
    });

    /* ── Tolak ─────────────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-tolak-tutor');
        if (!btn) return;
        document.getElementById('tolakTutorId').value = btn.dataset.id;
        document.getElementById('tolakTutorName').textContent = btn.dataset.nama;
        tolakModal.show();
    });
    document.getElementById('confirmTolak').addEventListener('click', function () {
        const id = document.getElementById('tolakTutorId').value;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Memproses...';
        fetch(`/admin/tutor/${id}/tolak`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { tolakModal.hide(); showToast('Pendaftaran tutor ditolak.', 'error'); setTimeout(() => location.reload(), 700); })
        .catch(() => showToast('Gagal menolak tutor.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-x-lg"></i> Tolak'; });
    });

    /* ── Hapus ─────────────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-tutor');
        if (!btn) return;
        document.getElementById('deleteTutorId').value = btn.dataset.id;
        document.getElementById('deleteTutorName').textContent = '"' + btn.dataset.nama + '"';
        deleteModal.show();
    });
    document.getElementById('confirmDelete').addEventListener('click', function () {
        const id = document.getElementById('deleteTutorId').value;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menghapus...';
        fetch(`/admin/tutor/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { deleteModal.hide(); showToast('Tutor berhasil dihapus.'); setTimeout(() => location.reload(), 700); })
        .catch(() => showToast('Gagal menghapus tutor.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-trash3"></i> Hapus'; });
    });

});
</script>

<style>
    @keyframes slideUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin    { to { transform:rotate(360deg); } }
</style>
@endpush

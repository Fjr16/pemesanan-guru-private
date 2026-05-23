@extends('layouts.app')

@section('title', 'Admin Panel — TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">

{{-- ================================================================
     SIDEBAR ADMIN
================================================================ --}}
<aside class="tk-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 mb-3 text-center">
        <div class="tk-avatar-sm mx-auto mb-2"
             style="width:44px;height:44px;font-size:.9rem;background:var(--tk-primary-light);">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="text-white fw-500" style="font-size:.875rem;">{{ Auth::user()->name }}</div>
        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Super Admin</div>
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">

    <div class="tk-sidebar-section">Dashboard</div>
    <a href="{{ route('admin.dashboard') }}" class="tk-sidebar-link active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="tk-sidebar-section mt-2">Manajemen</div>
    <a href="{{ route('admin.tutor') }}" class="tk-sidebar-link">
        <i class="bi bi-mortarboard"></i> Tutor
        @if($pendingTutorCount > 0)
            <span class="badge rounded-pill bg-warning text-dark ms-auto"
                  style="font-size:.65rem;">{{ $pendingTutorCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.siswa') }}" class="tk-sidebar-link">
        <i class="bi bi-people"></i> Siswa
    </a>
    <a href="{{ route('admin.mapel.index') }}" class="tk-sidebar-link">
        <i class="bi bi-book"></i> Mata Pelajaran
    </a>
    <a href="{{ route('admin.transaksi') }}" class="tk-sidebar-link">
        <i class="bi bi-receipt"></i> Transaksi
    </a>
    <a href="{{ route('admin.laporan') }}" class="tk-sidebar-link">
        <i class="bi bi-bar-chart-line"></i> Laporan
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-auto mb-3">
        @csrf
        <button type="submit" class="tk-sidebar-link w-100 text-start border-0 bg-transparent"
                style="color:rgba(255,255,255,.4);">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</aside>

{{-- ================================================================
     MAIN
================================================================ --}}
<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:1100px;">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:2px;">Admin Panel</h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    {{ now()->translatedFormat('l, d F Y') }} · TutorKu Management System
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.laporan') }}" class="tk-btn-outline-primary"
                   style="font-size:.8125rem;padding:.4375rem .875rem;">
                    <i class="bi bi-download"></i> Export Laporan
                </a>
            </div>
        </div>

        {{-- ── METRIK UTAMA ─────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['total_siswa'] }}</div>
                        <div class="tk-stat-label">Total Siswa</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-green">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['total_tutor_aktif'] }}</div>
                        <div class="tk-stat-label">Tutor Aktif</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-yellow">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['tutor_pending'] }}</div>
                        <div class="tk-stat-label">Tutor Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['total_booking'] }}</div>
                        <div class="tk-stat-label">Total Booking</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking bulan ini & pendapatan --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="tk-card" style="background:var(--tk-primary-dark);border:none;">
                    <div class="tk-card-body">
                        <div class="text-white-50 small mb-1">Booking Bulan Ini</div>
                        <div class="text-white fw-700" style="font-size:1.75rem;">
                            {{ $stats['booking_bulan_ini'] }}
                        </div>
                        <div style="color:var(--tk-primary-300);font-size:.8rem;">
                            @if($stats['growth_booking'] >= 0)
                                <i class="bi bi-arrow-up-short"></i> +{{ $stats['growth_booking'] }}% dari bulan lalu
                            @else
                                <i class="bi bi-arrow-down-short"></i> {{ $stats['growth_booking'] }}% dari bulan lalu
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="tk-card" style="background:var(--tk-success-text);border:none;">
                    <div class="tk-card-body">
                        <div class="text-white-50 small mb-1">Sesi Selesai</div>
                        <div class="text-white fw-700" style="font-size:1.75rem;">
                            {{ $stats['sesi_selesai'] }}
                        </div>
                        <div style="color:rgba(255,255,255,.6);font-size:.8rem;">
                            Bulan {{ now()->translatedFormat('F Y') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="tk-card">
                    <div class="tk-card-body">
                        <div class="text-muted small mb-1">Total Transaksi Platform</div>
                        <div class="fw-700" style="font-size:1.375rem;color:var(--tk-primary-dark);">
                            Rp {{ number_format($stats['total_transaksi'], 0, ',', '.') }}
                        </div>
                        <div style="font-size:.8rem;color:var(--tk-text-muted);">
                            Akumulasi semua pembayaran
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── VERIFIKASI TUTOR BARU ───────────────────────── --}}
            <div class="col-lg-7">
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            Verifikasi Tutor Baru
                            @if($pendingTutorCount > 0)
                                <span class="badge rounded-pill ms-1"
                                      style="background:var(--tk-warning-bg);color:var(--tk-warning-text);
                                             border:1px solid var(--tk-warning-border);font-size:.7rem;">
                                    {{ $pendingTutorCount }} menunggu
                                </span>
                            @endif
                        </h2>
                        <a href="{{ route('admin.tutor') }}" class="tk-link" style="font-size:.8125rem;">
                            Lihat semua →
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.875rem;">
                            <thead>
                                <tr style="background:var(--tk-surface);">
                                    <th style="padding:.625rem 1.25rem;font-weight:500;color:var(--tk-text-muted);border-bottom:1px solid var(--tk-border);">Tutor</th>
                                    <th style="padding:.625rem 1rem;font-weight:500;color:var(--tk-text-muted);border-bottom:1px solid var(--tk-border);">Spesialisasi</th>
                                    <th style="padding:.625rem 1rem;font-weight:500;color:var(--tk-text-muted);border-bottom:1px solid var(--tk-border);">Daftar</th>
                                    <th style="padding:.625rem 1rem;font-weight:500;color:var(--tk-text-muted);border-bottom:1px solid var(--tk-border);">Status</th>
                                    <th style="padding:.625rem 1rem;font-weight:500;color:var(--tk-text-muted);border-bottom:1px solid var(--tk-border);">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pendingTutorTable">
                                @forelse($pendingTutors as $tutor)
                                    <tr class="pending-tutor-row"
                                        data-tutor-id="{{ $tutor->id }}"
                                        style="border-bottom:1px solid var(--tk-border-light);">
                                        <td style="padding:.75rem 1.25rem;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="tk-tutor-avatar"
                                                     style="width:30px;height:30px;font-size:.75rem;">
                                                    {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-500" style="font-size:.875rem;">
                                                        {{ $tutor->user->name }}
                                                    </div>
                                                    <div class="text-muted" style="font-size:.75rem;">
                                                        {{ $tutor->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:.75rem 1rem;">
                                            <div style="font-size:.8rem;">
                                                {{ $tutor->mataPelajaran->pluck('nama')->join(', ') ?: '-' }}
                                            </div>
                                        </td>
                                        <td style="padding:.75rem 1rem;">
                                            <div style="font-size:.8rem;color:var(--tk-text-muted);">
                                                {{ $tutor->created_at->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td style="padding:.75rem 1rem;">
                                            <span class="tutor-status-badge tk-badge tk-badge-pending">
                                                Menunggu
                                            </span>
                                        </td>
                                        <td style="padding:.75rem 1rem;">
                                            <div class="d-flex gap-1">
                                                <button type="button"
                                                        class="btn-verif-tutor tk-btn-primary"
                                                        data-tutor-id="{{ $tutor->id }}"
                                                        data-tutor-name="{{ $tutor->user->name }}"
                                                        style="width:auto;padding:.25rem .625rem;font-size:.75rem;">
                                                    <i class="bi bi-check-lg"></i> Verif
                                                </button>
                                                <button type="button"
                                                        class="btn-tolak-tutor"
                                                        data-tutor-id="{{ $tutor->id }}"
                                                        style="border:1px solid var(--tk-danger-border);
                                                               background:var(--tk-danger-bg);
                                                               color:var(--tk-danger-text);
                                                               border-radius:var(--tk-radius);
                                                               padding:.25rem .5rem;font-size:.75rem;cursor:pointer;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                <a href="{{ route('tutor.show', $tutor->id) }}"
                                                   target="_blank"
                                                   style="border:1px solid var(--tk-border);background:#fff;
                                                          color:var(--tk-text-muted);border-radius:var(--tk-radius);
                                                          padding:.25rem .5rem;font-size:.75rem;text-decoration:none;">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyPendingRow">
                                        <td colspan="5" class="text-center py-4 text-muted" style="font-size:.875rem;">
                                            <i class="bi bi-check-circle-fill me-2" style="color:var(--tk-success-text);"></i>
                                            Semua tutor sudah diverifikasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN ────────────────────────────────── --}}
            <div class="col-lg-5 d-flex flex-column gap-4">

                {{-- Status booking terkini --}}
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-activity me-2 text-primary"></i>Booking Terkini
                        </h2>
                        <a href="{{ route('admin.transaksi') }}" class="tk-link" style="font-size:.8125rem;">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="tk-card-body p-0">
                        @forelse($recentBookings as $booking)
                            <div class="d-flex align-items-center gap-3 p-3
                                {{ !$loop->last ? 'border-bottom' : '' }}"
                                 style="border-color:var(--tk-border-light)!important;">
                                <div class="tk-tutor-avatar flex-shrink-0"
                                     style="width:32px;height:32px;font-size:.75rem;
                                            background:var(--tk-primary-100);color:var(--tk-primary);">
                                    {{ strtoupper(substr($booking->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-truncate fw-500" style="font-size:.8375rem;">
                                        {{ $booking->user->name ?? '-' }}
                                        <span class="text-muted fw-400">→</span>
                                        {{ $booking->tutorProfile->user->name ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $booking->mataPelajaran->nama ?? '-' }} ·
                                        {{ $booking->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <span class="tk-badge tk-badge-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Belum ada booking.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Mata pelajaran populer --}}
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-graph-up me-2 text-primary"></i>Mapel Terpopuler
                        </h2>
                    </div>
                    <div class="tk-card-body">
                        @foreach($popularMapel as $i => $mp)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:.8375rem;font-weight:500;">{{ $mp->nama }}</span>
                                    <span style="font-size:.8rem;color:var(--tk-text-muted);">
                                        {{ $mp->bookings_count }} booking
                                    </span>
                                </div>
                                <div style="height:6px;background:var(--tk-border);border-radius:100px;overflow:hidden;">
                                    <div style="height:100%;background:var(--tk-primary);border-radius:100px;
                                                width:{{ $mp->bookings_count / max($popularMapel->max('bookings_count'), 1) * 100 }}%;
                                                transition:width .4s;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
</div>

{{-- MODAL KONFIRMASI VERIFIKASI TUTOR --}}
<div class="modal fade" id="verifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-body text-center p-4">
                <div style="width:52px;height:52px;background:var(--tk-primary-50);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-shield-check" style="color:var(--tk-primary);font-size:1.25rem;"></i>
                </div>
                <h5 class="fw-600 mb-1">Verifikasi Tutor?</h5>
                <p class="text-muted small mb-1" id="verifTutorName"></p>
                <p class="text-muted small mb-4">
                    Tutor akan menerima notifikasi email dan langsung bisa aktif di platform.
                </p>
                <input type="hidden" id="verifTutorId">
                <div class="d-flex gap-2">
                    <button type="button" class="flex-1 btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmVerif" class="flex-1 tk-btn-primary"
                            style="width:auto;">
                        <i class="bi bi-check-lg"></i> Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    const verifModal = new bootstrap.Modal('#verifModal');

    // ── VERIFIKASI TUTOR ─────────────────────────────────────
    $(document).on('click', '.btn-verif-tutor', function () {
        const id   = $(this).data('tutor-id');
        const name = $(this).data('tutor-name');

        $('#verifTutorId').val(id);
        $('#verifTutorName').text('Tutor: ' + name);
        verifModal.show();
    });

    $('#confirmVerif').on('click', function () {
        const id   = $('#verifTutorId').val();
        const $btn = $(this);

        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-2" style="width:12px;height:12px;border-width:2px;"></span>Memproses...');

        $.ajax({
            url: `/admin/tutor/${id}/verifikasi`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                verifModal.hide();
                showToast('Tutor berhasil diverifikasi! Email notifikasi dikirim.', 'success');

                // Update row
                const $row = $(`.pending-tutor-row[data-tutor-id="${id}"]`);
                $row.find('.tutor-status-badge')
                    .removeClass('tk-badge-pending')
                    .addClass('tk-badge-completed')
                    .html('<i class="bi bi-patch-check-fill me-1"></i>Aktif');
                $row.find('.btn-verif-tutor, .btn-tolak-tutor').remove();

                // Update pending counter badge di sidebar
                updatePendingTutorBadge(-1);
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal memverifikasi tutor.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-check-lg"></i> Verifikasi');
            }
        });
    });

    // ── TOLAK TUTOR ──────────────────────────────────────────
    $(document).on('click', '.btn-tolak-tutor', function () {
        const id = $(this).data('tutor-id');
        if (!confirm('Tolak pendaftaran tutor ini? Tindakan ini tidak dapat dibatalkan.')) return;

        $.ajax({
            url: `/admin/tutor/${id}/tolak`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                showToast('Pendaftaran tutor ditolak.', 'info');
                $(`.pending-tutor-row[data-tutor-id="${id}"]`).fadeOut(300, function () {
                    $(this).remove();
                    updatePendingTutorBadge(-1);
                    if ($('.pending-tutor-row').length === 0) {
                        $('#pendingTutorTable').html(`
                            <tr><td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle-fill me-2" style="color:var(--tk-success-text);"></i>
                                Semua tutor sudah diverifikasi.
                            </td></tr>`);
                    }
                });
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal menolak tutor.', 'error');
            }
        });
    });

    function updatePendingTutorBadge(delta) {
        const $badge = $('.tk-sidebar-link .badge').first();
        if ($badge.length) {
            const next = (parseInt($badge.text()) || 0) + delta;
            if (next <= 0) $badge.remove();
            else $badge.text(next);
        }
    }

});
</script>
@endpush

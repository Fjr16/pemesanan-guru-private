@extends('layouts.app')

@section('title', 'Dashboard Tutor — TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">

{{-- ================================================================
     SIDEBAR TUTOR
================================================================ --}}
<aside class="tk-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 mb-3 text-center">
        <div class="tk-avatar-sm mx-auto mb-2"
             style="width:44px;height:44px;font-size:.9rem;background:var(--tk-primary-light);">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="text-white fw-500" style="font-size:.875rem;">{{ Auth::user()->name }}</div>
        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Tutor</div>
        @if(Auth::user()->tutorProfile?->status === 'active')
            <span class="tk-badge tk-badge-completed mt-1" style="font-size:.65rem;">
                <i class="bi bi-patch-check-fill"></i> Terverifikasi
            </span>
        @else
            <span class="tk-badge tk-badge-pending mt-1" style="font-size:.65rem;">Menunggu</span>
        @endif
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">

    <div class="tk-sidebar-section">Menu</div>
    <a href="{{ route('tutor.dashboard') }}" class="tk-sidebar-link active">
        <i class="bi bi-grid"></i> Dashboard
    </a>
    <a href="{{ route('tutor.pemesanan') }}" class="tk-sidebar-link">
        <i class="bi bi-calendar-check"></i> Pemesanan
        @if($pendingCount > 0)
            <span class="badge rounded-pill bg-warning text-dark ms-auto"
                  style="font-size:.65rem;">{{ $pendingCount }}</span>
        @endif
    </a>
    <a href="{{ route('tutor.jadwal') }}" class="tk-sidebar-link">
        <i class="bi bi-calendar3"></i> Kelola Jadwal
    </a>
    <a href="{{ route('tutor.riwayat') }}" class="tk-sidebar-link">
        <i class="bi bi-clock-history"></i> Riwayat Mengajar
    </a>

    <div class="tk-sidebar-section mt-2">Akun</div>
    <a href="{{ route('tutor.profil') }}" class="tk-sidebar-link">
        <i class="bi bi-person"></i> Profil Saya
    </a>
    <a href="{{ route('tutor.show', Auth::user()->tutorProfile?->id) }}"
       class="tk-sidebar-link" target="_blank">
        <i class="bi bi-eye"></i> Lihat Profil Publik
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
    <div class="container-fluid" style="max-width:960px;">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:2px;">
                    Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋
                </h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ route('tutor.jadwal') }}" class="tk-btn-primary"
               style="width:auto;padding:.5rem 1rem;font-size:.875rem;">
                <i class="bi bi-calendar-plus"></i> Atur Jadwal
            </a>
        </div>

        {{-- ── STAT CARDS ─────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-yellow">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['pending'] }}</div>
                        <div class="tk-stat-label">Request Masuk</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['confirmed'] }}</div>
                        <div class="tk-stat-label">Sesi Terjadwal</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['completed'] }}</div>
                        <div class="tk-stat-label">Sesi Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-green">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num" style="font-size:1.125rem;">
                            Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}
                        </div>
                        <div class="tk-stat-label">Pendapatan Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── REQUEST BOOKING MASUK ───────────────────────── --}}
            <div class="col-lg-7">
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-inbox me-2 text-primary"></i>
                            Request Booking Masuk
                            @if($pendingCount > 0)
                                <span class="badge rounded-pill ms-1"
                                      style="background:var(--tk-warning-bg);color:var(--tk-warning-text);
                                             font-size:.7rem;font-weight:500;border:1px solid var(--tk-warning-border);">
                                    {{ $pendingCount }} baru
                                </span>
                            @endif
                        </h2>
                        <a href="{{ route('tutor.pemesanan') }}" class="tk-link" style="font-size:.8125rem;">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="tk-card-body p-0" id="pendingRequestsList">
                        @forelse($pendingBookings as $booking)
                            <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }} booking-request-item"
                                 data-booking-id="{{ $booking->id }}"
                                 style="border-color:var(--tk-border-light)!important;transition:background .15s;">

                                <div class="d-flex align-items-start gap-3">
                                    <div class="tk-tutor-avatar flex-shrink-0"
                                         style="width:40px;height:40px;font-size:.85rem;
                                                background:var(--tk-primary-100);color:var(--tk-primary);">
                                        {{ strtoupper(substr($booking->user->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-500" style="font-size:.9rem;">
                                                {{ $booking->user->name }}
                                            </span>
                                            <span class="tk-badge tk-badge-pending" style="font-size:.7rem;">
                                                Pending
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="text-muted" style="font-size:.8rem;">
                                                <i class="bi bi-book me-1"></i>
                                                {{ $booking->mataPelajaran->nama ?? '-' }}
                                            </span>
                                            <span class="text-muted" style="font-size:.8rem;">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $booking->scheduled_day }}, {{ $booking->scheduled_time }} WIB
                                            </span>
                                            <span class="text-muted" style="font-size:.8rem;">
                                                <i class="bi bi-clock me-1"></i>{{ $booking->duration }} jam
                                            </span>
                                        </div>

                                        @if($booking->catatan)
                                            <div class="mb-2 px-2 py-1 rounded"
                                                 style="background:var(--tk-surface);font-size:.78rem;color:var(--tk-text-muted);">
                                                <i class="bi bi-chat-text me-1"></i>
                                                {{ Str::limit($booking->catatan, 80) }}
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button"
                                                    class="btn-terima-booking tk-btn-primary"
                                                    data-booking-id="{{ $booking->id }}"
                                                    style="width:auto;padding:.375rem .875rem;font-size:.8125rem;">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                            <button type="button"
                                                    class="btn-tolak-booking"
                                                    data-booking-id="{{ $booking->id }}"
                                                    style="border:1px solid var(--tk-danger-border);
                                                           background:var(--tk-danger-bg);
                                                           color:var(--tk-danger-text);border-radius:var(--tk-radius);
                                                           padding:.375rem .875rem;font-size:.8125rem;cursor:pointer;">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                            <span class="text-muted" style="font-size:.75rem;">
                                                {{ $booking->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size:.875rem;">
                                    Tidak ada request booking baru.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN ──────────────────────────────────── --}}
            <div class="col-lg-5 d-flex flex-column gap-4">

                {{-- Jadwal mengajar hari ini --}}
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-calendar-day me-2 text-primary"></i>Jadwal Hari Ini
                        </h2>
                        <span style="font-size:.8rem;color:var(--tk-text-muted);">
                            {{ now()->translatedFormat('l') }}
                        </span>
                    </div>
                    <div class="tk-card-body p-0">
                        @forelse($todaySessions as $session)
                            <div class="d-flex align-items-center gap-3 p-3
                                 {{ !$loop->last ? 'border-bottom' : '' }}"
                                 style="border-color:var(--tk-border-light)!important;">
                                <div style="width:4px;height:40px;background:var(--tk-primary);border-radius:4px;flex-shrink:0;"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="fw-500 text-truncate" style="font-size:.875rem;">
                                        {{ $session->user->name }}
                                    </div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ $session->scheduled_time }} WIB ·
                                        {{ $session->mataPelajaran->nama ?? '-' }}
                                    </div>
                                </div>
                                <span class="tk-badge tk-badge-confirmed">Terkonfirmasi</span>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0">Tidak ada jadwal mengajar hari ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Rating & Ulasan singkat --}}
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-star me-2 text-primary"></i>Rating Saya
                        </h2>
                    </div>
                    <div class="tk-card-body text-center">
                        <div style="font-size:3rem;font-weight:700;color:var(--tk-primary-dark);line-height:1;">
                            {{ number_format($stats['avg_rating'] ?? 0, 1) }}
                        </div>
                        <div class="tk-stars my-2" style="font-size:1.25rem;">
                            @php $r = round($stats['avg_rating'] ?? 0); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $r ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <div class="text-muted" style="font-size:.8125rem;">
                            Dari {{ $stats['total_reviews'] ?? 0 }} ulasan siswa
                        </div>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="tk-card"
                     style="background:var(--tk-primary-dark);border-color:transparent;">
                    <div class="tk-card-body">
                        <h3 class="text-white fw-500 mb-3" style="font-size:.9375rem;">
                            <i class="bi bi-lightning me-2" style="color:var(--tk-primary-300);"></i>
                            Aksi Cepat
                        </h3>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('tutor.jadwal') }}"
                               class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3 rounded"
                               style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.75);font-size:.875rem;transition:background .15s;"
                               onmouseover="this.style.background='rgba(255,255,255,.12)'"
                               onmouseout="this.style.background='rgba(255,255,255,.06)'">
                                <i class="bi bi-calendar-plus" style="color:var(--tk-primary-300);"></i>
                                Tambah slot jadwal
                            </a>
                            <a href="{{ route('tutor.profil') }}"
                               class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3 rounded"
                               style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.75);font-size:.875rem;transition:background .15s;"
                               onmouseover="this.style.background='rgba(255,255,255,.12)'"
                               onmouseout="this.style.background='rgba(255,255,255,.06)'">
                                <i class="bi bi-pencil-square" style="color:var(--tk-primary-300);"></i>
                                Update profil & tarif
                            </a>
                            <a href="{{ route('tutor.riwayat') }}"
                               class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3 rounded"
                               style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.75);font-size:.875rem;transition:background .15s;"
                               onmouseover="this.style.background='rgba(255,255,255,.12)'"
                               onmouseout="this.style.background='rgba(255,255,255,.06)'">
                                <i class="bi bi-clock-history" style="color:var(--tk-primary-300);"></i>
                                Riwayat mengajar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

{{-- MODAL TOLAK --}}
<div class="modal fade" id="tolakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-body p-4">
                <h5 class="fw-600 mb-2">Tolak Pemesanan?</h5>
                <p class="text-muted small mb-3">Berikan alasan penolakan (opsional):</p>
                <textarea id="tolakAlasan" class="tk-form-control mb-3" rows="2"
                          placeholder="Jadwal bentrok, dll..."></textarea>
                <input type="hidden" id="tolakBookingId">
                <div class="d-flex gap-2">
                    <button type="button" class="flex-1 btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="flex-1 tk-btn-primary" id="confirmTolak"
                            style="background:var(--tk-danger-text);">
                        Ya, Tolak
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

    const tolakModal = new bootstrap.Modal('#tolakModal');

    // ── TERIMA BOOKING ───────────────────────────────────────
    $(document).on('click', '.btn-terima-booking', function () {
        const bookingId = $(this).data('booking-id');
        const $btn = $(this);

        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-1" style="width:12px;height:12px;border-width:2px;"></span>...');

        $.ajax({
            url: `/api/booking/${bookingId}/terima`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                showToast('Booking berhasil diterima! Notifikasi email dikirim ke siswa.', 'success');
                // Ganti badge & hapus tombol aksi
                const $item = $btn.closest('.booking-request-item');
                $item.find('.tk-badge').removeClass('tk-badge-pending').addClass('tk-badge-confirmed').text('Dikonfirmasi');
                $item.find('.btn-terima-booking, .btn-tolak-booking').remove();

                // Update counter
                updatePendingCount(-1);
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal menerima booking.', 'error');
                $btn.prop('disabled', false).html('<i class="bi bi-check-lg"></i> Terima');
            }
        });
    });

    // ── TOLAK BOOKING ────────────────────────────────────────
    $(document).on('click', '.btn-tolak-booking', function () {
        $('#tolakBookingId').val($(this).data('booking-id'));
        $('#tolakAlasan').val('');
        tolakModal.show();
    });

    $('#confirmTolak').on('click', function () {
        const bookingId = $('#tolakBookingId').val();
        const alasan    = $('#tolakAlasan').val();
        const $btn      = $(this);

        $btn.prop('disabled', true).text('Menolak...');

        $.ajax({
            url: `/api/booking/${bookingId}/tolak`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                alasan: alasan
            },
            success: function () {
                tolakModal.hide();
                showToast('Booking ditolak. Notifikasi dikirim ke siswa.', 'info');

                // Hapus item dari list
                $(`.booking-request-item[data-booking-id="${bookingId}"]`).fadeOut(300, function () {
                    $(this).remove();
                    updatePendingCount(-1);
                    checkEmptyState();
                });
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal menolak booking.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Ya, Tolak');
            }
        });
    });

    function updatePendingCount(delta) {
        // Update badge di sidebar
        const $badge = $('.tk-sidebar-link .badge');
        if ($badge.length) {
            const curr = parseInt($badge.text()) || 0;
            const next = curr + delta;
            if (next <= 0) $badge.remove();
            else $badge.text(next);
        }
    }

    function checkEmptyState() {
        if ($('#pendingRequestsList .booking-request-item').length === 0) {
            $('#pendingRequestsList').html(`
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:.875rem;">
                        Tidak ada request booking baru.
                    </p>
                </div>`);
        }
    }

});
</script>
@endpush

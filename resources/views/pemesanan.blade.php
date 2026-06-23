@extends('layouts.app')

@section('title', 'Pemesanan Saya — TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">

{{-- SIDEBAR --}}
<aside class="tk-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 mb-3 text-center">
        <div class="tk-avatar-sm mx-auto mb-2"
             style="width:44px;height:44px;font-size:.9rem;background:var(--tk-primary-light);">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="text-white fw-500" style="font-size:.875rem;">{{ Auth::user()->name }}</div>
        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Siswa</div>
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">
    <div class="tk-sidebar-section">Menu</div>
    <a href="{{ route('siswa.dashboard') }}" class="tk-sidebar-link">
        <i class="bi bi-grid"></i> Dashboard
    </a>
    <a href="{{ route('home') }}" class="tk-sidebar-link">
        <i class="bi bi-search"></i> Cari Tutor
    </a>
    <a href="{{ route('siswa.pemesanan') }}" class="tk-sidebar-link active">
        <i class="bi bi-calendar-check"></i> Pemesanan Saya
    </a>
    <div class="tk-sidebar-section mt-2">Akun</div>
    <a href="{{ route('siswa.profil') }}" class="tk-sidebar-link">
        <i class="bi bi-person"></i> Profil Saya
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-auto mb-3">
        @csrf
        <button type="submit" class="tk-sidebar-link w-100 text-start border-0 bg-transparent"
                style="color:rgba(255,255,255,.4);">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</aside>

{{-- MAIN --}}
<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:860px;">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:2px;">Pemesanan Saya</h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    Kelola dan pantau semua pemesanan sesi privat Anda.
                </p>
            </div>
            <a href="{{ route('home') }}" class="tk-btn-primary"
               style="width:auto;padding:.5rem 1rem;font-size:.875rem;">
                <i class="bi bi-plus"></i> Pesan Baru
            </a>
        </div>

        {{-- ── FILTER TABS ──────────────────────────────────── --}}
        <div class="tk-card mb-4">
            <div class="tk-card-body py-2 px-3">
                <div class="d-flex align-items-center gap-1 overflow-auto" style="scrollbar-width:none;">
                    @php
                        $filters = [
                            'all'       => ['label' => 'Semua',         'count' => $counts['all']],
                            'pending'   => ['label' => 'Pending',        'count' => $counts['pending']],
                            'confirmed' => ['label' => 'Dikonfirmasi',   'count' => $counts['confirmed']],
                            'completed' => ['label' => 'Selesai',        'count' => $counts['completed']],
                            'cancelled' => ['label' => 'Dibatalkan',     'count' => $counts['cancelled']],
                        ];
                        $activeFilter = request('status', 'all');
                    @endphp
                    @foreach($filters as $key => $f)
                        <a href="{{ route('siswa.pemesanan', $key !== 'all' ? ['status' => $key] : []) }}"
                           class="flex-shrink-0 px-3 py-2 text-decoration-none rounded"
                           style="font-size:.8125rem;font-weight:{{ $activeFilter === $key ? '500' : '400' }};
                                  color:{{ $activeFilter === $key ? 'var(--tk-primary)' : 'var(--tk-text-muted)' }};
                                  background:{{ $activeFilter === $key ? 'var(--tk-primary-50)' : 'transparent' }};
                                  white-space:nowrap;transition:all .15s;">
                            {{ $f['label'] }}
                            @if($f['count'] > 0)
                                <span class="badge rounded-pill ms-1"
                                      style="background:{{ $activeFilter === $key ? 'var(--tk-primary)' : '#e2e8f0' }};
                                             color:{{ $activeFilter === $key ? '#fff' : 'var(--tk-text-muted)' }};
                                             font-size:.65rem;font-weight:500;">
                                    {{ $f['count'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── BOOKING LIST ─────────────────────────────────── --}}
        <div class="d-flex flex-column gap-3" id="bookingList">
            @forelse($bookings as $booking)
                <div class="tk-card tk-booking-item-card"
                     data-booking-id="{{ $booking->id }}"
                     data-status="{{ $booking->status }}">
                    <div class="tk-card-body">
                        <div class="d-flex align-items-start gap-3">

                            {{-- Avatar tutor --}}
                            <div class="tk-tutor-avatar flex-shrink-0"
                                 style="width:48px;height:48px;font-size:.9rem;">
                                {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'TK', 0, 2)) }}
                            </div>

                            {{-- Info utama --}}
                            <div class="flex-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                    <h3 style="font-size:.9375rem;font-weight:600;margin:0;">
                                        {{ $booking->tutorProfile->user->name ?? '-' }}
                                    </h3>
                                    @php
                                        $badgeClass = match($booking->status) {
                                            'pending'   => 'tk-badge-pending',
                                            'confirmed' => 'tk-badge-confirmed',
                                            'completed' => 'tk-badge-completed',
                                            default     => 'tk-badge-cancelled',
                                        };
                                        $statusLabel = [
                                            'pending'   => 'Menunggu Konfirmasi',
                                            'confirmed' => 'Dikonfirmasi',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                        ][$booking->status] ?? $booking->status;
                                    @endphp
                                    <span class="tk-badge {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <span class="d-flex align-items-center gap-1 text-muted"
                                          style="font-size:.8125rem;">
                                        <i class="bi bi-book"></i>
                                        {{ $booking->mataPelajaran->nama ?? '-' }}
                                    </span>
                                    <span class="d-flex align-items-center gap-1 text-muted"
                                          style="font-size:.8125rem;">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $booking->scheduled_day }}, {{ $booking->scheduled_time }} WIB
                                    </span>
                                    <span class="d-flex align-items-center gap-1 text-muted"
                                          style="font-size:.8125rem;">
                                        <i class="bi bi-clock"></i>
                                        {{ $booking->duration }} jam
                                    </span>
                                </div>

                                {{-- Info pembayaran --}}
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <span style="font-size:.875rem;font-weight:600;color:var(--tk-primary-dark);">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </span>
                                        @if($booking->payment_status === 'paid')
                                            <span class="tk-badge tk-badge-completed ms-2" style="font-size:.7rem;">
                                                <i class="bi bi-check-circle-fill"></i> Lunas
                                            </span>
                                        @elseif($booking->payment_status === 'unpaid' && $booking->status === 'confirmed')
                                            <span class="tk-badge tk-badge-pending ms-2" style="font-size:.7rem;">
                                                <i class="bi bi-exclamation-circle"></i> Belum bayar
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Action buttons --}}
                                    <div class="d-flex gap-2 flex-wrap">

                                        {{-- Bayar (jika confirmed & belum bayar) --}}
                                        @if($booking->status === 'confirmed' && $booking->payment_status !== 'paid')
                                            <a href="{{ route('siswa.pembayaran.show', $booking->id) }}"
                                               class="tk-btn-primary"
                                               style="width:auto;padding:.375rem .875rem;font-size:.8125rem;">
                                                <i class="bi bi-wallet2"></i> Bayar Sekarang
                                            </a>
                                        @endif

                                        {{-- Beri ulasan (jika completed & belum review) --}}
                                        @if($booking->status === 'completed' && !$booking->review)
                                            <button type="button"
                                                    class="tk-btn-outline-primary btn-beri-ulasan"
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-tutor-name="{{ $booking->tutorProfile->user->name }}"
                                                    style="padding:.375rem .875rem;font-size:.8125rem;">
                                                <i class="bi bi-star"></i> Beri Ulasan
                                            </button>
                                        @endif

                                        {{-- Batalkan (jika masih pending) --}}
                                        @if($booking->status === 'pending')
                                            <button type="button"
                                                    class="btn btn-sm btn-cancel-booking"
                                                    data-booking-id="{{ $booking->id }}"
                                                    style="border:1px solid var(--tk-danger-border);
                                                           background:var(--tk-danger-bg);
                                                           color:var(--tk-danger-text);
                                                           border-radius:var(--tk-radius);
                                                           padding:.375rem .875rem;font-size:.8125rem;">
                                                <i class="bi bi-x-circle"></i> Batalkan
                                            </button>
                                        @endif

                                        {{-- Pesan lagi --}}
                                        @if(in_array($booking->status, ['completed', 'cancelled']))
                                            <a href="{{ route('tutor.show', $booking->tutorProfile->id) }}"
                                               class="tk-btn-outline-primary"
                                               style="padding:.375rem .875rem;font-size:.8125rem;">
                                                <i class="bi bi-arrow-repeat"></i> Pesan Lagi
                                            </a>
                                        @endif

                                        {{-- Detail --}}
                                        <a href="{{ route('siswa.pemesanan.show', $booking->id) }}"
                                           class="d-flex align-items-center gap-1 text-muted text-decoration-none"
                                           style="font-size:.8125rem;padding:.375rem;">
                                            Detail <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Catatan jika ada --}}
                                @if($booking->catatan)
                                    <div class="mt-2 px-3 py-2 rounded"
                                         style="background:var(--tk-surface);font-size:.8rem;color:var(--tk-text-muted);">
                                        <i class="bi bi-chat-square-text me-1"></i>
                                        <em>{{ $booking->catatan }}</em>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="tk-card">
                    <div class="tk-card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size:3rem;opacity:.3;"></i>
                        <h5 class="mt-3 text-muted fw-500">Belum ada pemesanan</h5>
                        <p class="text-muted small mb-3">
                            @if($activeFilter !== 'all')
                                Tidak ada pemesanan dengan status "{{ $filters[$activeFilter]['label'] }}".
                            @else
                                Anda belum pernah melakukan pemesanan sesi privat.
                            @endif
                        </p>
                        <a href="{{ route('home') }}" class="tk-btn-primary"
                           style="width:auto;padding:.625rem 1.5rem;">
                            <i class="bi bi-search"></i> Cari Tutor Sekarang
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(isset($bookings) && $bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</main>
</div>

{{-- ================================================================
     MODAL ULASAN
================================================================ --}}
<div class="modal fade" id="ulasanModal" tabindex="-1" aria-labelledby="ulasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600" id="ulasanModalLabel">Beri Ulasan Tutor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3" id="ulasanTutorName"></p>

                {{-- Rating bintang --}}
                <div class="mb-3">
                    <label class="tk-form-label">Rating</label>
                    <div class="d-flex gap-2" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star star-btn"
                               data-value="{{ $i }}"
                               style="font-size:1.75rem;color:#e2e8f0;cursor:pointer;transition:color .1s;"></i>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingValue" value="0">
                </div>

                {{-- Komentar --}}
                <div class="mb-3">
                    <label class="tk-form-label" for="ulasanKomentar">Komentar</label>
                    <textarea id="ulasanKomentar" rows="3" class="tk-form-control"
                              placeholder="Ceritakan pengalaman belajar Anda bersama tutor ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="tk-btn-primary" id="submitUlasan"
                        style="width:auto;padding:.5rem 1.25rem;">
                    <i class="bi bi-send"></i> Kirim Ulasan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL KONFIRMASI BATALKAN
================================================================ --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-body text-center p-4">
                <div style="width:56px;height:56px;background:var(--tk-danger-bg);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;
                            margin:0 auto 1rem;font-size:1.5rem;">
                    <i class="bi bi-exclamation-triangle" style="color:var(--tk-danger-text);"></i>
                </div>
                <h5 class="fw-600 mb-2">Batalkan Pemesanan?</h5>
                <p class="text-muted small mb-4">
                    Pemesanan yang dibatalkan tidak dapat dikembalikan ke status semula.
                </p>
                <input type="hidden" id="cancelBookingId">
                <div class="d-flex gap-2">
                    <button type="button" class="flex-1 btn btn-light" data-bs-dismiss="modal">
                        Tidak
                    </button>
                    <button type="button" class="flex-1 tk-btn-primary" id="confirmCancel"
                            style="background:var(--tk-danger-text);border-color:var(--tk-danger-text);">
                        Ya, Batalkan
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

    let activeBookingId = null;
    const ulasanModal  = new bootstrap.Modal('#ulasanModal');
    const cancelModal  = new bootstrap.Modal('#cancelModal');

    // ── BERI ULASAN ──────────────────────────────────────────
    $(document).on('click', '.btn-beri-ulasan', function () {
        activeBookingId = $(this).data('booking-id');
        const tutorName = $(this).data('tutor-name');

        $('#ulasanTutorName').text('Tutor: ' + tutorName);
        $('#ratingValue').val(0);
        $('#ulasanKomentar').val('');
        resetStars(0);

        ulasanModal.show();
    });

    // Star picker hover & click
    $('#starPicker').on('mouseenter', '.star-btn', function () {
        const val = $(this).data('value');
        highlightStars(val);
    }).on('mouseleave', function () {
        highlightStars(parseInt($('#ratingValue').val()) || 0);
    }).on('click', '.star-btn', function () {
        const val = $(this).data('value');
        $('#ratingValue').val(val);
        highlightStars(val);
    });

    function highlightStars(val) {
        $('.star-btn').each(function () {
            const sv = parseInt($(this).data('value'));
            $(this)
                .removeClass('bi-star bi-star-fill')
                .addClass(sv <= val ? 'bi-star-fill' : 'bi-star')
                .css('color', sv <= val ? '#f59e0b' : '#e2e8f0');
        });
    }

    function resetStars(val) { highlightStars(val); }

    // Submit ulasan
    $('#submitUlasan').on('click', function () {
        const rating   = parseInt($('#ratingValue').val());
        const komentar = $('#ulasanKomentar').val().trim();

        if (rating === 0) { showToast('Pilih rating bintang terlebih dahulu.', 'warning'); return; }
        if (!komentar)    { showToast('Tulis komentar singkat tentang sesi ini.', 'warning'); return; }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Mengirim...');

        $.ajax({
            url: `/siswa/ulasan/${activeBookingId}`,
            method: 'POST',
            data: { rating, comment: komentar, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                ulasanModal.hide();
                showToast('Ulasan berhasil dikirim. Terima kasih!', 'success');
                // Sembunyikan tombol ulasan pada card
                $(`.btn-beri-ulasan[data-booking-id="${activeBookingId}"]`).remove();
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal mengirim ulasan.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-send"></i> Kirim Ulasan');
            }
        });
    });

    // ── BATALKAN PEMESANAN ───────────────────────────────────
    $(document).on('click', '.btn-cancel-booking', function () {
        activeBookingId = $(this).data('booking-id');
        $('#cancelBookingId').val(activeBookingId);
        cancelModal.show();
    });

    $('#confirmCancel').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Membatalkan...');

        $.ajax({
            url: `/siswa/pemesanan/${activeBookingId}/cancel`,
            method: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                cancelModal.hide();
                showToast('Pemesanan berhasil dibatalkan.', 'success');

                // Update badge status pada card
                const $card = $(`.tk-booking-item-card[data-booking-id="${activeBookingId}"]`);
                $card.find('.tk-badge')
                    .removeClass('tk-badge-pending tk-badge-confirmed')
                    .addClass('tk-badge-cancelled')
                    .text('Dibatalkan');
                $card.find('.btn-cancel-booking').remove();
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal membatalkan pemesanan.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Ya, Batalkan');
            }
        });
    });

});
</script>
@endpush

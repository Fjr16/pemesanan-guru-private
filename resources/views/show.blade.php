@extends('layouts.app')

@section('title', ($tutor->user->name ?? 'Tutor') . ' — TutorKu')

@section('content')

@php $rating = round($tutor->reviews_avg_rating ?? 0, 1); @endphp

{{-- ================================================================
     PROFIL HEADER
=============================================================== --}}
<div style="background:var(--tk-primary-dark);padding:2.5rem 0 0;">
    <div class="container">
        <div class="row align-items-end g-4 pb-0">

            {{-- Avatar + Info utama --}}
            <div class="col-lg-8">
                <a href="{{ route('home') }}"
                   class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"
                   style="color:rgba(255,255,255,.55);font-size:.8125rem;">
                    <i class="bi bi-arrow-left"></i> Kembali ke daftar tutor
                </a>

                <div class="d-flex align-items-start gap-4">
                    {{-- Avatar --}}
                    <div class="tk-tutor-avatar flex-shrink-0"
                         style="width:80px;height:80px;font-size:1.5rem;
                                background:var(--tk-primary-light);color:#fff;">
                        {{ strtoupper(substr($tutor->user->name ?? 'TK', 0, 2)) }}
                    </div>

                    <div>
                        <h1 class="text-white mb-1" style="font-size:1.5rem;font-weight:600;">
                            {{ $tutor->user->name }}
                        </h1>
                        <p class="mb-2" style="color:rgba(255,255,255,.6);font-size:.9375rem;">
                            Tutor Privat ·
                            {{ $tutor->mataPelajaran->pluck('nama')->join(', ') }}
                        </p>

                        {{-- Rating + stats --}}
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-1">
                                <span class="tk-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - $rating < 1)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star" style="color:rgba(255,255,255,.3);"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-white fw-500">{{ $rating }}</span>
                                <span style="color:rgba(255,255,255,.45);font-size:.8rem;">
                                    ({{ $tutor->review_count ?? 0 }} ulasan)
                                </span>
                            </div>
                            <span style="color:rgba(255,255,255,.3);">·</span>
                            <span style="color:rgba(255,255,255,.65);font-size:.875rem;">
                                <i class="bi bi-person-check me-1"></i>
                                {{ $tutor->session_count ?? 0 }} sesi selesai
                            </span>
                            <span style="color:rgba(255,255,255,.3);">·</span>
                            <span style="color:rgba(255,255,255,.65);font-size:.875rem;">
                                <i class="bi bi-briefcase me-1"></i>
                                {{ $tutor->experience_years ?? 0 }} tahun pengalaman
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarif + CTA --}}
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-block text-start"
                     style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                            border-radius:var(--tk-radius-xl);padding:1rem 1.25rem;">
                    <div style="color:rgba(255,255,255,.5);font-size:.8rem;margin-bottom:4px;">Tarif per jam</div>
                    <div class="text-white fw-600 mb-3" style="font-size:1.625rem;">
                        Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}
                    </div>
                    <a href="#jadwal-section" class="tk-btn-primary d-flex align-items-center justify-content-center gap-2"
                       style="width:100%;font-size:.9rem;">
                        <i class="bi bi-calendar-check"></i> Pilih Jadwal & Pesan
                    </a>
                </div>
            </div>

        </div>

        {{-- Tab nav --}}
        <div class="d-flex gap-0 mt-4" style="border-bottom:1px solid rgba(255,255,255,.1);">
            <a href="#profil-section" class="tk-profile-tab active" data-tab="profil">Profil</a>
            <a href="#jadwal-section" class="tk-profile-tab" data-tab="jadwal">Jadwal & Booking</a>
            <a href="#ulasan-section" class="tk-profile-tab" data-tab="ulasan">
                Ulasan ({{ $tutor->review_count ?? 0 }})
            </a>
        </div>
    </div>
</div>


{{-- ================================================================
     BODY CONTENT
================================================================ --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- LEFT: Detail profil --}}
        <div class="col-lg-8">

            {{-- PROFIL SECTION --}}
            <div id="profil-section" class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>Tentang Saya
                    </h2>
                </div>
                <div class="tk-card-body">
                    <p style="font-size:.9375rem;line-height:1.7;color:var(--tk-text-muted);">
                        {{ $tutor->bio ?? 'Tutor berpengalaman siap membantu belajarmu.' }}
                    </p>

                    <div class="row g-3 mt-1">
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-mortarboard me-1"></i>Target jenjang
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ ucfirst($tutor->education_level ?? 'Semua jenjang') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi mengajar
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    Online & Tatap Muka
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SPESIALISASI --}}
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-patch-check me-2 text-primary"></i>Spesialisasi
                    </h2>
                </div>
                <div class="tk-card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($tutor->mataPelajaran as $mp)
                            <span class="tk-badge-subject" style="font-size:.875rem;padding:.375rem .875rem;">
                                {{ $mp->icon ?? '📚' }} {{ $mp->nama }}
                            </span>
                        @empty
                            <span class="text-muted small">Belum ada spesialisasi.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- JADWAL & BOOKING ──────────────────────────────── --}}
            <div id="jadwal-section" class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Jadwal Ketersediaan
                    </h2>
                    <div class="d-flex gap-3 align-items-center">
                        <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:var(--tk-text-muted);">
                            <span style="width:10px;height:10px;background:var(--tk-success-bg);border:1px solid var(--tk-success-border);border-radius:2px;display:inline-block;"></span>Tersedia
                        </span>
                        <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:var(--tk-text-muted);">
                            <span style="width:10px;height:10px;background:var(--tk-danger-bg);border:1px solid var(--tk-danger-border);border-radius:2px;display:inline-block;"></span>Penuh
                        </span>
                        <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:var(--tk-text-muted);">
                            <span style="width:10px;height:10px;background:var(--tk-primary);border-radius:2px;display:inline-block;"></span>Dipilih
                        </span>
                    </div>
                </div>
                <div class="tk-card-body">

                    @guest
                        {{-- Guest: CTA login dulu --}}
                        <div class="text-center py-4"
                             style="background:var(--tk-primary-50);border-radius:var(--tk-radius-lg);
                                    border:1px dashed var(--tk-primary-100);">
                            <i class="bi bi-lock text-primary" style="font-size:2rem;"></i>
                            <h6 class="mt-2 mb-1">Login untuk melihat & memilih jadwal</h6>
                            <p class="text-muted small mb-3">Daftar gratis atau masuk ke akun Anda.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('login') }}" class="tk-btn-primary" style="width:auto;padding:.5rem 1.25rem;">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}" class="tk-btn-outline-primary">
                                    Daftar Gratis
                                </a>
                            </div>
                        </div>
                    @endguest

                    @auth
                        {{-- Loading state --}}
                        <div id="jadwalLoading" class="text-center py-4">
                            <div class="tk-spinner tk-spinner-dark mx-auto mb-2"></div>
                            <p class="text-muted small mb-0">Memuat jadwal...</p>
                        </div>

                        {{-- Jadwal table --}}
                        <div id="jadwalContainer" style="display:none;">
                            <div class="table-responsive">
                                <table class="tk-schedule-table" id="jadwalTable">
                                    <thead>
                                        <tr>
                                            <th style="width:70px;">Jam</th>
                                            {{-- Hari di-inject JS --}}
                                        </tr>
                                    </thead>
                                    <tbody id="jadwalBody">
                                        {{-- Diisi via JS --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- Slot terpilih --}}
                            <div id="selectedSlotInfo" class="mt-3"
                                 style="display:none;background:var(--tk-primary-50);
                                        border:1px solid var(--tk-primary-100);
                                        border-radius:var(--tk-radius-lg);padding:1rem;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div style="font-size:.875rem;font-weight:500;color:var(--tk-primary-dark);">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            <span id="selectedSlotText"></span>
                                        </div>
                                        <div style="font-size:.8125rem;color:var(--tk-primary-light);margin-top:2px;">
                                            Durasi: 1 jam ·
                                            Total: <strong id="selectedSlotTotal"></strong>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-close-sm" id="clearSlot"></button>
                                </div>
                            </div>

                            {{-- Durasi selector --}}
                            <div id="durasiSelector" class="mt-3" style="display:none;">
                                <label class="tk-form-label" for="durasiSelect">Durasi Sesi</label>
                                <select id="durasiSelect" class="tk-form-control" style="max-width:200px;">
                                    <option value="1">1 jam — Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}</option>
                                    <option value="1.5">1.5 jam — Rp {{ number_format(($tutor->hourly_rate ?? 0) * 1.5, 0, ',', '.') }}</option>
                                    <option value="2">2 jam — Rp {{ number_format(($tutor->hourly_rate ?? 0) * 2, 0, ',', '.') }}</option>
                                    <option value="3">3 jam — Rp {{ number_format(($tutor->hourly_rate ?? 0) * 3, 0, ',', '.') }}</option>
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div id="catatanWrapper" class="mt-3" style="display:none;">
                                <label class="tk-form-label" for="catatan">Catatan untuk Tutor (opsional)</label>
                                <textarea id="catatan" rows="2" class="tk-form-control"
                                          placeholder="cth: Saya butuh bantuan bab limit dan turunan..."></textarea>
                            </div>

                            {{-- Tombol pesan --}}
                            <div class="mt-3" id="pesanBtnWrapper" style="display:none;">
                                @if(Auth::user()?->role === 'siswa')
                                    <button type="button" class="tk-btn-primary" id="pesanBtn"
                                            style="width:auto;padding:.6875rem 1.75rem;">
                                        <i class="bi bi-calendar-plus"></i> Pesan Sekarang
                                    </button>
                                @else
                                    <div class="alert alert-info py-2 px-3 mb-0" style="font-size:.875rem;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Hanya akun siswa yang dapat melakukan pemesanan.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Empty state --}}
                        <div id="jadwalEmpty" style="display:none;">
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted" style="font-size:2.5rem;opacity:.4;"></i>
                                <h6 class="mt-2 text-muted">Belum ada jadwal tersedia</h6>
                                <p class="text-muted small mb-0">Tutor ini belum mengatur ketersediaan jadwal.</p>
                            </div>
                        </div>
                    @endauth

                </div>
            </div>

            {{-- ULASAN ─────────────────────────────────────────── --}}
            <div id="ulasan-section" class="tk-card">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-star me-2 text-primary"></i>
                        Ulasan Siswa
                    </h2>
                    <div style="font-size:.875rem;color:var(--tk-text-muted);">
                        Rata-rata {{ $rating }}/5
                    </div>
                </div>
                <div class="tk-card-body">
                    @forelse($reviews as $review)
                        <div class="d-flex gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="tk-tutor-avatar flex-shrink-0"
                                 style="width:36px;height:36px;font-size:.8rem;">
                                {{ strtoupper(substr($review->siswa->name ?? 'A', 0, 2)) }}
                            </div>
                            <div class="flex-1">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span style="font-size:.875rem;font-weight:500;">
                                        {{ $review->siswa->name ?? 'Siswa' }}
                                    </span>
                                    <span style="font-size:.75rem;color:var(--tk-text-muted);">
                                        {{ $review->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="tk-stars mb-1" style="font-size:.8rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-muted mb-0" style="font-size:.875rem;line-height:1.6;">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-chat-square-text text-muted" style="font-size:2rem;opacity:.4;"></i>
                            <p class="text-muted small mt-2 mb-0">Belum ada ulasan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>{{-- /col-lg-8 --}}


        {{-- RIGHT: Sticky sidebar ringkasan --}}
        <div class="col-lg-4">
            <div class="tk-card" style="position:sticky;top:76px;">
                <div class="tk-card-body">
                    <div class="text-center mb-4">
                        <div class="tk-tutor-avatar mx-auto mb-2"
                             style="width:64px;height:64px;font-size:1.25rem;
                                    background:var(--tk-primary-light);color:#fff;">
                            {{ strtoupper(substr($tutor->user->name ?? 'TK', 0, 2)) }}
                        </div>
                        <h3 style="font-size:1rem;font-weight:600;">{{ $tutor->user->name }}</h3>
                        <div class="tk-stars mb-1" style="font-size:.875rem;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($rating))
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                            <span class="text-muted ms-1" style="font-size:.75rem;">{{ $rating }}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Tarif/jam</span>
                        <span style="font-weight:600;color:var(--tk-primary-dark);">
                            Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Pengalaman</span>
                        <span style="font-weight:500;">{{ $tutor->experience_years ?? 0 }} tahun</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Sesi selesai</span>
                        <span style="font-weight:500;">{{ $tutor->session_count ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 mb-3" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Status</span>
                        <span class="tk-badge tk-badge-completed">
                            <i class="bi bi-patch-check-fill"></i> Terverifikasi
                        </span>
                    </div>

                    <a href="#jadwal-section" class="tk-btn-primary d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-calendar-check"></i> Pesan Sesi
                    </a>

                    <div class="mt-3 text-center">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tutor->user->phone ?? '') }}"
                           target="_blank"
                           class="d-inline-flex align-items-center gap-2 text-decoration-none"
                           style="font-size:.8125rem;color:var(--tk-success-text);">
                            <i class="bi bi-whatsapp"></i> Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


@push('styles')
<style>
.tk-profile-tab {
    padding: .75rem 1.25rem;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .875rem;
    border-bottom: 2px solid transparent;
    transition: all .15s;
    white-space: nowrap;
}
.tk-profile-tab:hover { color: rgba(255,255,255,.8); }
.tk-profile-tab.active { color: #fff; border-bottom-color: var(--tk-primary-300); }
</style>
@endpush


@push('scripts')
<script>
const TUTOR_ID    = {{ $tutor->id }};
const HOURLY_RATE = {{ $tutor->hourly_rate ?? 0 }};
let selectedSlot  = null;

$(document).ready(function () {

    @auth
    // ── Load jadwal via AJAX ─────────────────────────────────
    loadJadwal();

    // ── Clear pilihan ────────────────────────────────────────
    $('#clearSlot').on('click', clearSelection);

    // ── Durasi change → update total harga ──────────────────
    $('#durasiSelect').on('change', updateTotal);

    // ── Tombol Pesan Sekarang ────────────────────────────────
    $('#pesanBtn').on('click', submitBooking);
    @endauth

    // ── Smooth scroll tab ────────────────────────────────────
    $('.tk-profile-tab').on('click', function (e) {
        const target = $(this).attr('href');
        if (target.startsWith('#')) {
            e.preventDefault();
            const $target = $(target);
            if ($target.length) {
                $('html,body').animate({ scrollTop: $target.offset().top - 80 }, 350);
            }
        }
        $('.tk-profile-tab').removeClass('active');
        $(this).addClass('active');
    });

});

// ── Load jadwal dari API ─────────────────────────────────────
function loadJadwal() {
    $('#jadwalLoading').show();
    $('#jadwalContainer, #jadwalEmpty').hide();

    $.ajax({
        url: `/api/tutor/${TUTOR_ID}/jadwal`,
        method: 'GET',
        success: function (res) {
            const slots = res.data || [];
            if (!slots.length) {
                $('#jadwalLoading').hide();
                $('#jadwalEmpty').show();
                return;
            }
            buildTable(slots);
            $('#jadwalLoading').hide();
            $('#jadwalContainer').show();
        },
        error: function () {
            $('#jadwalLoading').hide();
            $('#jadwalEmpty').show();
        }
    });
}

// ── Bangun tabel jadwal ──────────────────────────────────────
function buildTable(slots) {
    // Kumpulkan hari & jam unik
    const hariOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    const hariList  = [...new Set(slots.map(s => s.hari))].sort((a,b) => hariOrder.indexOf(a) - hariOrder.indexOf(b));
    const jamList   = [...new Set(slots.map(s => s.jam_mulai))].sort();

    // Header
    let headHtml = '<tr><th>Jam</th>';
    hariList.forEach(h => { headHtml += `<th>${h}</th>`; });
    headHtml += '</tr>';
    $('#jadwalTable thead').html(headHtml);

    // Body
    let bodyHtml = '';
    jamList.forEach(function (jam) {
        bodyHtml += `<tr><td>${jam}</td>`;
        hariList.forEach(function (hari) {
            const slot = slots.find(s => s.hari === hari && s.jam_mulai === jam);
            if (!slot) {
                bodyHtml += '<td><span style="color:var(--tk-border);font-size:.75rem;">—</span></td>';
            } else if (slot.is_booked) {
                bodyHtml += `<td><span class="tk-slot tk-slot-booked">Penuh</span></td>`;
            } else {
                bodyHtml += `<td>
                    <span class="tk-slot tk-slot-free"
                          data-schedule-id="${slot.id}"
                          data-hari="${slot.hari}"
                          data-jam="${slot.jam_mulai}"
                          onclick="selectSlot(this)">
                        Pilih
                    </span>
                </td>`;
            }
        });
        bodyHtml += '</tr>';
    });
    $('#jadwalBody').html(bodyHtml);
}

// ── Pilih slot ───────────────────────────────────────────────
function selectSlot(el) {
    const $el = $(el);

    // Reset semua ke free
    $('.tk-slot-free, .tk-slot-selected').each(function () {
        if (!$(this).hasClass('tk-slot-booked')) {
            $(this).removeClass('tk-slot-selected').addClass('tk-slot-free').text('Pilih');
        }
    });

    // Set yang dipilih
    $el.removeClass('tk-slot-free').addClass('tk-slot-selected').text('✓ Dipilih');

    selectedSlot = {
        scheduleId: $el.data('schedule-id'),
        hari: $el.data('hari'),
        jam:  $el.data('jam'),
    };

    updateTotal();
    $('#selectedSlotInfo, #durasiSelector, #catatanWrapper, #pesanBtnWrapper').show();
}

function updateTotal() {
    if (!selectedSlot) return;
    const durasi  = parseFloat($('#durasiSelect').val()) || 1;
    const total   = HOURLY_RATE * durasi;
    const durasiText = durasi === 1 ? '1 jam' : durasi + ' jam';

    $('#selectedSlotText').text(`${selectedSlot.hari}, ${selectedSlot.jam} WIB — ${durasiText}`);
    $('#selectedSlotTotal').text('Rp ' + total.toLocaleString('id-ID'));
}

function clearSelection() {
    selectedSlot = null;
    $('.tk-slot-selected').removeClass('tk-slot-selected').addClass('tk-slot-free').text('Pilih');
    $('#selectedSlotInfo, #durasiSelector, #catatanWrapper, #pesanBtnWrapper').hide();
}

// ── Submit booking via AJAX ──────────────────────────────────
function submitBooking() {
    if (!selectedSlot) return;

    const $btn = $('#pesanBtn');
    $btn.prop('disabled', true)
        .html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Memproses...');

    $.ajax({
        url: '/api/booking',
        method: 'POST',
        data: {
            tutor_profile_id: TUTOR_ID,
            schedule_id:      selectedSlot.scheduleId,
            durasi:           parseFloat($('#durasiSelect').val()) || 1,
            catatan:          $('#catatan').val(),
            _token:           $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (res) {
            showToast('Pemesanan berhasil! Menunggu konfirmasi tutor.', 'success');
            // Tandai slot jadi booked
            $(`.tk-slot-selected`)
                .removeClass('tk-slot-selected tk-slot-free')
                .addClass('tk-slot-booked')
                .text('Penuh')
                .attr('onclick', '');

            clearSelection();

            // Redirect ke halaman riwayat pemesanan
            setTimeout(() => {
                window.location.href = res.redirect || '/siswa/pemesanan';
            }, 1500);
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal melakukan pemesanan.';
            showToast(msg, 'error');
            $btn.prop('disabled', false)
                .html('<i class="bi bi-calendar-plus"></i> Pesan Sekarang');
        }
    });
}
</script>
@endpush

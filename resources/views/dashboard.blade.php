@extends('layouts.app')

@section('title', 'Dashboard Siswa — TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">

{{-- ================================================================
     SIDEBAR
================================================================ --}}
<aside class="tk-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 mb-3">
        <div class="tk-avatar-sm mx-auto mb-2"
             style="width:44px;height:44px;font-size:.9rem;background:var(--tk-primary-light);">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="text-center">
            <div class="text-white fw-500" style="font-size:.875rem;">{{ Auth::user()->name }}</div>
            <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Siswa</div>
        </div>
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">

    <div class="tk-sidebar-section">Menu</div>
    <a href="{{ route('siswa.dashboard') }}"
       class="tk-sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i> Dashboard
    </a>
    <a href="{{ route('home') }}" class="tk-sidebar-link">
        <i class="bi bi-search"></i> Cari Tutor
    </a>
    <a href="{{ route('siswa.pemesanan') }}"
       class="tk-sidebar-link {{ request()->routeIs('siswa.pemesanan*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Pemesanan Saya
        @if($pendingCount > 0)
            <span class="badge rounded-pill bg-warning text-dark ms-auto" style="font-size:.65rem;">
                {{ $pendingCount }}
            </span>
        @endif
    </a>

    <div class="tk-sidebar-section mt-2">Akun</div>
    <a href="{{ route('siswa.profil') }}" class="tk-sidebar-link">
        <i class="bi bi-person"></i> Profil Saya
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mt-auto mx-3 mb-3">
        @csrf
        <button type="submit" class="tk-sidebar-link w-100 text-start border-0 bg-transparent"
                style="color:rgba(255,255,255,.4);margin-top:auto;">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</aside>

{{-- ================================================================
     MAIN CONTENT
================================================================ --}}
<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:960px;">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;color:var(--tk-text);margin-bottom:2px;">
                    Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋
                </h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ route('home') }}" class="tk-btn-primary"
               style="width:auto;padding:.5rem 1rem;font-size:.875rem;">
                <i class="bi bi-search"></i> Cari Tutor
            </a>
        </div>

        {{-- ── STAT CARDS ─────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['total_booking'] }}</div>
                        <div class="tk-stat-label">Total Booking</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-yellow">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['pending'] }}</div>
                        <div class="tk-stat-label">Menunggu</div>
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
                        <div class="tk-stat-label">Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-red">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['cancelled'] }}</div>
                        <div class="tk-stat-label">Dibatalkan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── UPCOMING SESSIONS ──────────────────────────── --}}
            <div class="col-lg-7">
                <div class="tk-card h-100">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>Sesi Mendatang
                        </h2>
                        <a href="{{ route('siswa.pemesanan') }}" class="tk-link" style="font-size:.8125rem;">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="tk-card-body p-0">
                        @forelse($upcomingSessions as $booking)
                            <div class="d-flex align-items-center gap-3 p-3
                                {{ !$loop->last ? 'border-bottom' : '' }}"
                                 style="border-color:var(--tk-border-light)!important;">
                                <div class="tk-tutor-avatar flex-shrink-0"
                                     style="width:40px;height:40px;font-size:.85rem;">
                                    {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'TK', 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="fw-500 text-truncate" style="font-size:.9rem;">
                                        {{ $booking->tutorProfile->user->name ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        <i class="bi bi-book me-1"></i>
                                        {{ $booking->mataPelajaran->nama ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $booking->scheduled_day }},
                                        {{ $booking->scheduled_time }} WIB
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    @php
                                        $badgeClass = match($booking->status) {
                                            'pending'   => 'tk-badge-pending',
                                            'confirmed' => 'tk-badge-confirmed',
                                            'completed' => 'tk-badge-completed',
                                            default     => 'tk-badge-cancelled',
                                        };
                                        $statusLabel = match($booking->status) {
                                            'pending'   => 'Pending',
                                            'confirmed' => 'Dikonfirmasi',
                                            'completed' => 'Selesai',
                                            default     => 'Dibatalkan',
                                        };
                                    @endphp
                                    <span class="tk-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    @if($booking->status === 'confirmed')
                                        <div class="mt-1">
                                            <a href="{{ route('siswa.pembayaran.show', $booking->id) }}"
                                               class="tk-link" style="font-size:.75rem;">
                                                Bayar →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                <p class="text-muted mt-2 mb-3" style="font-size:.875rem;">
                                    Belum ada sesi yang dijadwalkan.
                                </p>
                                <a href="{{ route('home') }}" class="tk-btn-primary"
                                   style="width:auto;padding:.5rem 1.25rem;font-size:.875rem;">
                                    <i class="bi bi-search"></i> Cari Tutor
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Tutor favorit + tips ─────────────────── --}}
            <div class="col-lg-5 d-flex flex-column gap-4">

                {{-- Tutor yang pernah dipesan --}}
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title">
                            <i class="bi bi-heart me-2 text-primary"></i>Tutor Sering Dipesan
                        </h2>
                    </div>
                    <div class="tk-card-body p-0">
                        @forelse($favoriteTutors as $tutor)
                            <a href="{{ route('tutor.show', $tutor->tutor_profile_id) }}"
                               class="d-flex align-items-center gap-3 p-3 text-decoration-none
                               {{ !$loop->last ? 'border-bottom' : '' }}"
                               style="border-color:var(--tk-border-light)!important;
                                      transition:background .1s;"
                               onmouseover="this.style.background='var(--tk-primary-50)'"
                               onmouseout="this.style.background=''">
                                <div class="tk-tutor-avatar flex-shrink-0"
                                     style="width:36px;height:36px;font-size:.8rem;">
                                    {{ strtoupper(substr($tutor->tutor_name ?? 'TK', 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="fw-500 text-truncate" style="font-size:.875rem;color:var(--tk-text);">
                                        {{ $tutor->tutor_name ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $tutor->subjects ?? '-' }} ·
                                        {{ $tutor->session_count }} sesi
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted" style="font-size:.75rem;"></i>
                            </a>
                        @empty
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0">Belum ada tutor yang dipesan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tips belajar --}}
                <div class="tk-card"
                     style="background:var(--tk-primary-dark);border-color:transparent;">
                    <div class="tk-card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div style="font-size:1.5rem;">💡</div>
                            <div>
                                <h3 class="text-white fw-500 mb-1" style="font-size:.9375rem;">
                                    Tips Belajar
                                </h3>
                                <p style="color:rgba(255,255,255,.6);font-size:.8125rem;margin-bottom:.75rem;">
                                    Persiapkan topik atau soal yang ingin dibahas sebelum sesi dimulai
                                    agar waktu belajar lebih efektif.
                                </p>
                                <a href="{{ route('home') }}"
                                   style="color:var(--tk-primary-300);font-size:.8125rem;
                                          font-weight:500;text-decoration:none;">
                                    Cari tutor sekarang →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>
</div>

@endsection

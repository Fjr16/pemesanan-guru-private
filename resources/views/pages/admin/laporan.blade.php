@extends('layouts.app')

@section('title', 'Laporan — Admin TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">
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
    <a href="{{ route('admin.dashboard') }}" class="tk-sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <div class="tk-sidebar-section mt-2">Manajemen</div>
    <a href="{{ route('admin.tutor') }}" class="tk-sidebar-link"><i class="bi bi-mortarboard"></i> Tutor</a>
    <a href="{{ route('admin.siswa') }}" class="tk-sidebar-link"><i class="bi bi-people"></i> Siswa</a>
    <a href="{{ route('admin.mapel.index') }}" class="tk-sidebar-link"><i class="bi bi-book"></i> Mata Pelajaran</a>
    <a href="{{ route('admin.transaksi') }}" class="tk-sidebar-link"><i class="bi bi-receipt"></i> Transaksi</a>
    <a href="{{ route('admin.laporan') }}" class="tk-sidebar-link active"><i class="bi bi-bar-chart-line"></i> Laporan</a>
    <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-auto mb-3">
        @csrf
        <button type="submit" class="tk-sidebar-link w-100 text-start border-0 bg-transparent"
                style="color:rgba(255,255,255,.4);"><i class="bi bi-box-arrow-right"></i> Keluar</button>
    </form>
</aside>

<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:1000px;">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:2px;">Laporan Platform</h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">Ringkasan aktivitas dan performa TutorKu.</p>
            </div>
            <div class="d-flex gap-2">
                <select class="tk-form-control" id="periodSelect" style="width:auto;font-size:.875rem;">
                    <option value="month">Bulan Ini</option>
                    <option value="quarter">3 Bulan</option>
                    <option value="year">Tahun Ini</option>
                </select>
            </div>
        </div>

        {{-- Metrik utama --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue"><i class="bi bi-calendar-check"></i></div>
                    <div><div class="tk-stat-num">{{ $stats['total_booking'] }}</div><div class="tk-stat-label">Total Booking</div></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-green"><i class="bi bi-patch-check"></i></div>
                    <div><div class="tk-stat-num">{{ $stats['completed'] }}</div><div class="tk-stat-label">Sesi Selesai</div></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-green"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="tk-stat-num">{{ $stats['new_users'] }}</div>
                        <div class="tk-stat-label">Pengguna Baru</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="tk-stat-card">
                    <div class="tk-stat-icon tk-stat-icon-blue"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="tk-stat-num" style="font-size:1rem;">
                            Rp {{ number_format($stats['total_transaksi'], 0, ',', '.') }}
                        </div>
                        <div class="tk-stat-label">Total Transaksi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Top tutor --}}
            <div class="col-lg-6">
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title"><i class="bi bi-trophy me-2 text-primary"></i>Top Tutor</h2>
                    </div>
                    <div class="tk-card-body p-0">
                        @foreach($topTutors as $i => $tutor)
                            <div class="d-flex align-items-center gap-3 p-3
                                 {{ !$loop->last ? 'border-bottom' : '' }}"
                                 style="border-color:var(--tk-border-light)!important;">
                                <div style="width:24px;height:24px;border-radius:50%;
                                            background:{{ $i === 0 ? '#f59e0b' : ($i === 1 ? '#94a3b8' : '#cd7c2c') }};
                                            color:#fff;display:flex;align-items:center;justify-content:center;
                                            font-size:.75rem;font-weight:600;flex-shrink:0;">
                                    {{ $i + 1 }}
                                </div>
                                <div class="tk-tutor-avatar flex-shrink-0"
                                     style="width:32px;height:32px;font-size:.75rem;">
                                    {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="fw-500 text-truncate" style="font-size:.875rem;">
                                        {{ $tutor->user->name ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $tutor->bookings_count }} sesi
                                    </div>
                                </div>
                                <div class="tk-stars" style="font-size:.8rem;">
                                    @php $r = round($tutor->reviews_avg_rating ?? 0); @endphp
                                    @for($j = 1; $j <= 5; $j++)
                                        <i class="bi bi-star{{ $j <= $r ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Booking per status pie --}}
            <div class="col-lg-6">
                <div class="tk-card">
                    <div class="tk-card-header">
                        <h2 class="tk-card-title"><i class="bi bi-pie-chart me-2 text-primary"></i>Status Booking</h2>
                    </div>
                    <div class="tk-card-body">
                        @php
                            $statusData = [
                                ['label'=>'Completed', 'val'=>$stats['completed'],        'color'=>'var(--tk-success-text)'],
                                ['label'=>'Confirmed', 'val'=>$stats['confirmed'],        'color'=>'var(--tk-primary)'],
                                ['label'=>'Pending',   'val'=>$stats['pending'],          'color'=>'var(--tk-warning-text)'],
                                ['label'=>'Cancelled', 'val'=>$stats['cancelled'],        'color'=>'var(--tk-danger-text)'],
                            ];
                            $total = array_sum(array_column($statusData, 'val')) ?: 1;
                        @endphp
                        @foreach($statusData as $s)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:.8375rem;font-weight:500;">{{ $s['label'] }}</span>
                                    <span style="font-size:.8rem;color:var(--tk-text-muted);">
                                        {{ $s['val'] }} ({{ round($s['val'] / $total * 100) }}%)
                                    </span>
                                </div>
                                <div style="height:8px;background:var(--tk-border);border-radius:100px;overflow:hidden;">
                                    <div style="height:100%;background:{{ $s['color'] }};border-radius:100px;
                                                width:{{ round($s['val'] / $total * 100) }}%;
                                                transition:width .5s ease;"></div>
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

@endsection

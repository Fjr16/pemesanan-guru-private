@extends('layouts.app')

@section('title', 'Transaksi — Admin TutorKu')

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
        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Super Admin</div>
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">
    <div class="tk-sidebar-section">Dashboard</div>
    <a href="{{ route('admin.dashboard') }}" class="tk-sidebar-link">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <div class="tk-sidebar-section mt-2">Manajemen</div>
    <a href="{{ route('admin.tutor') }}" class="tk-sidebar-link">
        <i class="bi bi-mortarboard"></i> Tutor
    </a>
    <a href="{{ route('admin.siswa') }}" class="tk-sidebar-link">
        <i class="bi bi-people"></i> Siswa
    </a>
    <a href="{{ route('admin.mapel.index') }}" class="tk-sidebar-link">
        <i class="bi bi-book"></i> Mata Pelajaran
    </a>
    <a href="{{ route('admin.transaksi') }}" class="tk-sidebar-link active">
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

{{-- MAIN --}}
<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:1100px;">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:2px;">Rekap Transaksi</h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    Pantau semua pemesanan dan pembayaran di platform.
                </p>
            </div>
            <a href="{{ route('admin.laporan') }}" class="tk-btn-outline-primary"
               style="font-size:.8125rem;padding:.4375rem .875rem;">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
        </div>

        {{-- STAT RINGKAS --}}
        <div class="row g-3 mb-4">
            @php
                $sumStats = [
                    ['label'=>'Total Booking',    'val'=>$stats['total'],     'icon'=>'bi-calendar-check',  'color'=>'tk-stat-icon-blue'],
                    ['label'=>'Pending',          'val'=>$stats['pending'],   'icon'=>'bi-hourglass-split', 'color'=>'tk-stat-icon-yellow'],
                    ['label'=>'Confirmed',        'val'=>$stats['confirmed'], 'icon'=>'bi-check-circle',    'color'=>'tk-stat-icon-blue'],
                    ['label'=>'Completed',        'val'=>$stats['completed'], 'icon'=>'bi-patch-check',     'color'=>'tk-stat-icon-green'],
                ];
            @endphp
            @foreach($sumStats as $s)
                <div class="col-6 col-lg-3">
                    <div class="tk-stat-card">
                        <div class="tk-stat-icon {{ $s['color'] }}"><i class="bi {{ $s['icon'] }}"></i></div>
                        <div><div class="tk-stat-num">{{ $s['val'] }}</div><div class="tk-stat-label">{{ $s['label'] }}</div></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FILTER BAR --}}
        <div class="tk-card mb-4">
            <div class="tk-card-body">
                <form method="GET" action="{{ route('admin.transaksi') }}"
                      class="row g-2 align-items-end" id="filterForm">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="tk-form-label">Cari siswa / tutor</label>
                        <div class="tk-input-group">
                            <i class="bi bi-search tk-input-icon"></i>
                            <input type="text" name="q" class="tk-form-control"
                                   value="{{ request('q') }}"
                                   placeholder="Nama atau email...">
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-2">
                        <label class="tk-form-label">Status</label>
                        <select name="status" class="tk-form-control">
                            <option value="">Semua</option>
                            @foreach(['pending','confirmed','completed','cancelled'] as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-2">
                        <label class="tk-form-label">Mata Pelajaran</label>
                        <select name="mapel_id" class="tk-form-control">
                            <option value="">Semua</option>
                            @foreach($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" {{ request('mapel_id') == $mp->id ? 'selected' : '' }}>
                                    {{ $mp->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-2">
                        <label class="tk-form-label">Dari tanggal</label>
                        <input type="date" name="from" class="tk-form-control"
                               value="{{ request('from') }}">
                    </div>
                    <div class="col-6 col-sm-4 col-lg-2">
                        <label class="tk-form-label">Sampai</label>
                        <input type="date" name="to" class="tk-form-control"
                               value="{{ request('to') }}">
                    </div>
                    <div class="col-12 col-lg-1 d-flex gap-2">
                        <button type="submit" class="tk-btn-primary flex-1"
                                style="padding:.625rem .5rem;font-size:.8rem;">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['q','status','mapel_id','from','to']))
                            <a href="{{ route('admin.transaksi') }}"
                               class="tk-btn-outline-primary flex-1 text-center"
                               style="padding:.625rem .5rem;font-size:.8rem;">
                                <i class="bi bi-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="tk-card">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.8375rem;">
                    <thead>
                        <tr style="background:var(--tk-surface);">
                            @php
                                $cols = ['ID','Siswa','Tutor','Mata Pelajaran','Jadwal','Durasi','Total','Status','Pembayaran','Tgl Booking'];
                            @endphp
                            @foreach($cols as $col)
                                <th style="padding:.625rem {{ $loop->first ? '1.25rem' : '.875rem' }};
                                           font-weight:500;color:var(--tk-text-muted);
                                           border-bottom:1px solid var(--tk-border);white-space:nowrap;">
                                    {{ $col }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr style="border-bottom:1px solid var(--tk-border-light);">
                                <td style="padding:.75rem 1.25rem;font-family:var(--bs-font-monospace);
                                           font-size:.75rem;color:var(--tk-text-muted);">
                                    #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="padding:.75rem .875rem;">
                                    <div class="fw-500">{{ $booking->user->name ?? '-' }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $booking->user->email ?? '' }}
                                    </div>
                                </td>
                                <td style="padding:.75rem .875rem;">
                                    <div class="fw-500">{{ $booking->tutorProfile->user->name ?? '-' }}</div>
                                </td>
                                <td style="padding:.75rem .875rem;">
                                    {{ $booking->mataPelajaran->nama ?? '-' }}
                                </td>
                                <td style="padding:.75rem .875rem;white-space:nowrap;">
                                    <div>{{ $booking->scheduled_day }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $booking->scheduled_time }} WIB
                                    </div>
                                </td>
                                <td style="padding:.75rem .875rem;">{{ $booking->duration }} jam</td>
                                <td style="padding:.75rem .875rem;white-space:nowrap;">
                                    <span class="fw-500">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </td>
                                <td style="padding:.75rem .875rem;">
                                    <span class="tk-badge tk-badge-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td style="padding:.75rem .875rem;">
                                    @if($booking->payment_status === 'paid')
                                        <span class="tk-badge tk-badge-completed">
                                            <i class="bi bi-check-circle-fill me-1"></i>Lunas
                                        </span>
                                    @elseif($booking->payment_status === 'pending_verification')
                                        <span class="tk-badge tk-badge-pending">Verifikasi</span>
                                    @else
                                        <span class="tk-badge tk-badge-cancelled">Belum</span>
                                    @endif
                                </td>
                                <td style="padding:.75rem .875rem;white-space:nowrap;color:var(--tk-text-muted);font-size:.75rem;">
                                    {{ $booking->created_at->format('d M Y') }}<br>
                                    {{ $booking->created_at->format('H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size:2rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0 small">Belum ada transaksi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-3 d-flex justify-content-between align-items-center border-top"
                     style="border-color:var(--tk-border-light)!important;">
                    <div class="text-muted" style="font-size:.8125rem;">
                        Menampilkan {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }}
                        dari {{ $bookings->total() }} transaksi
                    </div>
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
</div>

@endsection

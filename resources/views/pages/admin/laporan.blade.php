@extends('layouts.admin')

@section('title', 'Laporan — Admin TutorKu')
@section('page-title', 'Laporan Platform')

@section('content')

{{-- ── Header ───────────────────────────────────────────────── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px;flex-wrap:wrap;">
    <div>
        <h1 style="font-size:16px;font-weight:600;margin:0 0 2px;color:#1a1a2e">Laporan Platform</h1>
        <p style="font-size:13px;color:#8890a8;margin:0">Ringkasan aktivitas dan performa TutorKu.</p>
    </div>
    <select id="periodSelect"
            style="height:34px;padding:0 28px 0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;cursor:pointer;appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238890a8' d='M6 8L1 3h10z'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;">
        <option value="month">Bulan Ini</option>
        <option value="quarter">3 Bulan</option>
        <option value="year">Tahun Ini</option>
    </select>
</div>

{{-- ── Metrik utama ─────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-calendar-check-fill" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Booking</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total_booking'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-patch-check-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Sesi Selesai</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['completed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-people-fill" style="color:#1e40af;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Pengguna Baru</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['new_users'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-wallet2" style="color:#b45309;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Transaksi</div>
            <div style="font-size:16px;font-weight:600;color:#1a1a2e;">Rp {{ number_format($stats['total_transaksi'] ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- ── Bottom panels ───────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

    {{-- Top tutor --}}
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
            <i class="bi bi-trophy" style="font-size:14px;color:#1e2d6b;"></i>
            <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Top Tutor</span>
        </div>
        @forelse($topTutors as $i => $tutor)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                <div style="width:22px;height:22px;border-radius:50%;background:{{ $i === 0 ? '#f59e0b' : ($i === 1 ? '#94a3b8' : '#cd7c2c') }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                    <div style="width:32px;height:32px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0;">
                    {{ strtoupper(substr($tutor->name ?? 'T', 0, 2)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:500;font-size:13px;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $tutor->name ?? '-' }}</div>
                    <div style="font-size:11px;color:#8890a8;">{{ $tutor->bookings_count ?? 0 }} sesi</div>
                </div>
                <div style="display:flex;gap:1px;color:#f59e0b;font-size:11px;">
                    @php $r = round($tutor->rating ?? 0); @endphp
                    @for($j = 1; $j <= 5; $j++)
                        <i class="bi bi-star{{ $j <= $r ? '-fill' : '' }}"></i>
                    @endfor
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:20px 0;color:#8890a8;font-size:13px;">
                <i class="bi bi-trophy" style="font-size:24px;opacity:.3;display:block;margin-bottom:6px;"></i>
                Belum ada data.
            </div>
        @endforelse
    </div>

    {{-- Status booking --}}
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
            <i class="bi bi-pie-chart" style="font-size:14px;color:#1e2d6b;"></i>
            <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Status Booking</span>
        </div>
        @php
            $statusData = [
                ['label'=>'Completed', 'val'=>$stats['completed'] ?? 0,  'color'=>'#15803d'],
                ['label'=>'Confirmed', 'val'=>$stats['confirmed'] ?? 0,  'color'=>'#1e40af'],
                ['label'=>'Pending',   'val'=>$stats['pending'] ?? 0,    'color'=>'#b45309'],
                ['label'=>'Cancelled', 'val'=>$stats['cancelled'] ?? 0,  'color'=>'#991b1b'],
            ];
            $total = array_sum(array_column($statusData, 'val')) ?: 1;
        @endphp
        @foreach($statusData as $s)
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:13px;font-weight:500;">{{ $s['label'] }}</span>
                    <span style="font-size:12px;color:#8890a8;">{{ $s['val'] }} ({{ round($s['val'] / $total * 100) }}%)</span>
                </div>
                <div style="height:8px;background:#f0f2f8;border-radius:100px;overflow:hidden;">
                    <div style="height:100%;background:{{ $s['color'] }};border-radius:100px;width:{{ round($s['val'] / $total * 100) }}%;transition:width .5s ease;"></div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection

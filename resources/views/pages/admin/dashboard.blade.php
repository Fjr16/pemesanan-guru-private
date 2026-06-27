@extends('layouts.admin')

@section('title', 'Dashboard — Admin TutorKu')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Stat cards ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-people-fill" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Siswa</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total_siswa'] ?? 0 }}</div>
            <div style="font-size:11px;color:#8890a8;">terdaftar</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-mortarboard-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Tutor Aktif</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total_tutor_aktif'] ?? 0 }}</div>
            <div style="font-size:11px;color:#8890a8;">terverifikasi</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-hourglass-split" style="color:#b45309;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Tutor Pending</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['tutor_pending'] ?? 0 }}</div>
            <div style="font-size:11px;color:#b45309;">perlu verifikasi</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-calendar2-check-fill" style="color:#1e40af;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Booking</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total_booking'] ?? 0 }}</div>
            <div style="font-size:11px;color:#8890a8;">sepanjang waktu</div>
        </div>
    </div>
</div>

{{-- ── Mid cards ────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:20px;">
    <div style="background:#1e2d6b;border-radius:12px;padding:20px 22px;">
        <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:10px;font-weight:500;">Booking Bulan Ini</div>
        <div style="font-size:32px;font-weight:700;color:#fff;line-height:1;margin-bottom:8px;">{{ $stats['booking_bulan_ini'] ?? 0 }}</div>
        <div style="font-size:12px;color:rgba(255,255,255,.45);">
            <span style="color:#86efac;">↑ +{{ $stats['growth_booking'] ?? 0 }}%</span> dari bulan lalu
        </div>
    </div>
    <div style="background:#064e3b;border-radius:12px;padding:20px 22px;">
        <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:10px;font-weight:500;">Sesi Selesai</div>
        <div style="font-size:32px;font-weight:700;color:#fff;line-height:1;margin-bottom:8px;">{{ $stats['sesi_selesai'] ?? 0 }}</div>
        <div style="font-size:12px;color:rgba(255,255,255,.45);">Bulan {{ now()->translatedFormat('F Y') }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
        <div style="font-size:12px;color:#8890a8;margin-bottom:10px;font-weight:500;">Total Transaksi Platform</div>
        <div style="font-size:22px;font-weight:700;color:#1e2d6b;line-height:1;margin-bottom:8px;">Rp {{ number_format($stats['total_transaksi'] ?? 0, 0, ',', '.') }}</div>
        <div style="font-size:12px;color:#8890a8;">Akumulasi semua pembayaran</div>
    </div>
</div>

{{-- ── Bottom panels ───────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

    {{-- Verifikasi tutor --}}
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;">
                <i class="bi bi-shield-check" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Verifikasi Tutor Baru</span>
            </div>
            <a href="{{ route('admin.tutor') }}" style="font-size:12px;color:#1e2d6b;text-decoration:none;font-weight:500;">Lihat semua →</a>
        </div>
        <table style="width:100%;font-size:12px;border-collapse:collapse">
            <thead>
                <tr>
                    <th style="color:#8890a8;font-weight:500;padding:0 8px 10px;text-align:left;border-bottom:1px solid #e8eaf0;">Tutor</th>
                    <th style="color:#8890a8;font-weight:500;padding:0 8px 10px;text-align:left;border-bottom:1px solid #e8eaf0;">Spesialisasi</th>
                    <th style="color:#8890a8;font-weight:500;padding:0 8px 10px;text-align:left;border-bottom:1px solid #e8eaf0;">Daftar</th>
                    <th style="color:#8890a8;font-weight:500;padding:0 8px 10px;text-align:left;border-bottom:1px solid #e8eaf0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingTutors ?? [] as $tutor)
                    <tr class="pending-tutor-row" data-tutor-id="{{ $tutor->id }}">
                        <td style="padding:10px 8px;border-bottom:1px solid #f0f2f8;">
                            <span style="font-weight:500;">{{ $tutor->user->name ?? '-' }}</span>
                        </td>
                        <td style="padding:10px 8px;border-bottom:1px solid #f0f2f8;color:#8890a8;">
                            {{ $tutor->mataPelajaran->pluck('nama')->join(', ') ?? '-' }}
                        </td>
                        <td style="padding:10px 8px;border-bottom:1px solid #f0f2f8;color:#8890a8;white-space:nowrap;">
                            {{ $tutor->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td style="padding:10px 8px;border-bottom:1px solid #f0f2f8;">
                            <a href="{{ route('admin.tutor') }}" style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:500;border:1px solid #d0d5e8;background:#fff;color:#1e2d6b;text-decoration:none;">
                                <i class="bi bi-eye" style="font-size:10px;"></i> Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:24px 0;text-align:center;color:#8890a8;font-size:13px;">
                            <i class="bi bi-check-circle-fill" style="font-size:20px;color:#15803d;display:block;margin-bottom:6px;"></i>
                            Semua tutor sudah diverifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- Booking terkini --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-activity" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Booking Terkini</span>
                </div>
                <a href="{{ route('admin.transaksi') }}" style="font-size:12px;color:#1e2d6b;text-decoration:none;font-weight:500;">Lihat semua →</a>
            </div>
            @forelse($recentBookings ?? [] as $booking)
                <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:500;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $booking->user->name ?? '-' }}</div>
                        <div style="font-size:11px;color:#8890a8;">{{ $booking->mataPelajaran->nama ?? '-' }}</div>
                    </div>
                    <span style="font-size:11px;font-weight:500;padding:2px 8px;border-radius:12px;{{ $booking->status === 'pending' ? 'background:#fffbeb;color:#92400e;' : ($booking->status === 'confirmed' ? 'background:#eff6ff;color:#1e40af;' : ($booking->status === 'expired' ? 'background:#f0f2f8;color:#6b7280;' : 'background:#f0fdf4;color:#15803d;')) }}">
                        {{ ucfirst($booking->status ?? 'pending') }}
                    </span>
                </div>
            @empty
                <div style="text-align:center;padding:24px 0;color:#8890a8;font-size:13px;">
                    <i class="bi bi-calendar-x" style="font-size:24px;opacity:.3;display:block;margin-bottom:6px;"></i>
                    Belum ada booking.
                </div>
            @endforelse
        </div>

        {{-- Mapel terpopuler --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-graph-up" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Mapel Terpopuler</span>
            </div>
            @php $maxBooking = $popularMapel->max('bookings_count') ?: 1; @endphp
            @forelse($popularMapel ?? [] as $i => $mp)
                <div style="display:flex;align-items:center;gap:10px;padding:8px 0;">
                    <div style="width:22px;height:22px;border-radius:50%;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        {{ $i + 1 }}
                    </div>
                    <span style="flex:1;font-size:13px;">{{ $mp->nama ?? '-' }}</span>
                    <span style="font-size:12px;color:#8890a8;">{{ $mp->bookings_count ?? 0 }} booking</span>
                    <div style="width:60px;height:5px;border-radius:3px;background:#f0f2f8;overflow:hidden;flex-shrink:0;">
                        <div style="height:100%;border-radius:3px;background:#1e2d6b;width:{{ round(($mp->bookings_count ?? 0) / $maxBooking * 100) }}%;"></div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:20px 0;color:#8890a8;font-size:13px;">
                    <i class="bi bi-book" style="font-size:24px;opacity:.3;display:block;margin-bottom:6px;"></i>
                    Belum ada data.
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

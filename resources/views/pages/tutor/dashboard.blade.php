@extends('layouts.tutor')

@section('title', 'Dashboard — TutorKu')
@section('page-title', 'Dashboard')

@section('topbar-actions')
<a href="{{ route('tutor.jadwal') }}" class="tk-topbar-btn">
    <i class="bi bi-calendar-plus"></i> Atur Jadwal
</a>
@endsection

@section('content')

{{-- ── Sapaan ───────────────────────────────────────────────── --}}
<div style="margin-bottom:20px;">
    <h1 style="font-size:18px;font-weight:600;margin:0 0 2px;color:#1a1a2e;">
        Halo, {{ explode(' ', Auth::user()->tutor->name ?? Auth::user()->username)[0] }} 👋
    </h1>
    <p style="font-size:13px;color:#8890a8;margin:0;">{{ now()->translatedFormat('l, d F Y') }} · Berikut ringkasan aktivitas Anda hari ini.</p>
</div>

{{-- ── Stat cards ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-hourglass-split" style="color:#b45309;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:2px;">Request Masuk</div>
            <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['pending'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-calendar-check-fill" style="color:#3730a3;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:2px;">Sesi Terjadwal</div>
            <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['confirmed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-check-circle-fill" style="color:#15803d;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:2px;">Sesi Selesai</div>
            <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['completed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-wallet2" style="color:#1e40af;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:12px;color:#8890a8;margin-bottom:2px;">Pendapatan Bulan Ini</div>
            <div style="font-size:18px;font-weight:700;color:#1a1a2e;line-height:1.2;">Rp {{ number_format($stats['pendapatan'] ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- ── Content grid ─────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

    {{-- ── KIRI: Request booking masuk ───────────────────────── --}}
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;display:flex;flex-direction:column;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-inbox" style="font-size:15px;color:#1e2d6b;"></i>
                <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Request Booking Masuk</span>
                @if(($pendingCount ?? 0) > 0)
                    <span style="background:#fffbeb;color:#92400e;font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;">{{ $pendingCount }} baru</span>
                @endif
            </div>
            <a href="{{ route('tutor.pemesanan') }}" style="font-size:12px;color:#1e2d6b;text-decoration:none;font-weight:500;">Lihat semua →</a>
        </div>

        @forelse($pendingBookings ?? [] as $booking)
            <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                <div style="width:42px;height:42px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;flex-shrink:0;">
                    {{ strtoupper(substr($booking->siswa ?? 'S', 0, 2)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <span style="font-weight:600;font-size:14px;color:#1a1a2e;">{{ $booking->siswa ?? '-' }}</span>
                        <span style="background:#fffbeb;color:#92400e;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Pending</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:8px;">
                        <span style="font-size:12px;color:#8890a8;"><i class="bi bi-book" style="margin-right:4px;"></i>{{ $booking->mapel ?? '-' }}</span>
                        <span style="font-size:12px;color:#8890a8;"><i class="bi bi-calendar3" style="margin-right:4px;"></i>{{ $booking->hari ?? '-' }}, {{ $booking->jam ?? '-' }} WIB</span>
                        <span style="font-size:12px;color:#8890a8;"><i class="bi bi-clock" style="margin-right:4px;"></i>{{ $booking->durasi ?? 0 }} jam</span>
                    </div>
                    @if(!empty($booking->catatan))
                        <div style="font-size:12px;color:#4b5574;background:#f8f9fc;padding:8px 12px;border-radius:8px;margin-bottom:8px;line-height:1.5;">
                            <i class="bi bi-chat-text" style="margin-right:4px;color:#8890a8;"></i>{{ $booking->catatan }}
                        </div>
                    @endif
                    <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                        <a href="{{ route('tutor.pemesanan.show', $booking->id ?? 0) }}"
                           style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:500;background:#1e2d6b;color:#fff;text-decoration:none;transition:background .15s;">
                            <i class="bi bi-eye" style="font-size:11px;"></i> Detail
                        </a>
                        <span style="font-size:11px;color:#b0b8cc;">{{ $booking->waktu ?? '' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:40px 0;color:#8890a8;">
                <i class="bi bi-inbox" style="font-size:32px;opacity:.3;display:block;margin-bottom:10px;"></i>
                <p style="font-size:13px;margin:0;">Tidak ada request booking baru.</p>
            </div>
        @endforelse
    </div>

    {{-- ── KANAN: Jadwal + Rating + Aksi ─────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- Jadwal hari ini --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-calendar-day" style="font-size:15px;color:#1e2d6b;"></i>
                    <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Jadwal Hari Ini</span>
                </div>
                <span style="font-size:12px;color:#8890a8;">{{ now()->translatedFormat('l') }}</span>
            </div>

            @forelse($todaySessions ?? [] as $session)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                    <div style="width:4px;height:40px;background:#1e2d6b;border-radius:4px;flex-shrink:0;"></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:14px;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $session->siswa ?? '-' }}</div>
                        <div style="font-size:12px;color:#8890a8;margin-top:2px;">{{ $session->jam ?? '-' }} WIB · {{ $session->mapel ?? '-' }}</div>
                    </div>
                    <span style="background:#eff6ff;color:#1e40af;font-size:10px;font-weight:500;padding:3px 10px;border-radius:12px;">Terjadwal</span>
                </div>
            @empty
                <div style="text-align:center;padding:28px 0;color:#8890a8;">
                    <i class="bi bi-calendar-x" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
                    <p style="font-size:13px;margin:0;">Tidak ada jadwal mengajar hari ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Rating --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;text-align:center;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;justify-content:center;">
                <i class="bi bi-star-fill" style="font-size:15px;color:#f59e0b;"></i>
                <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Rating Saya</span>
            </div>
            <div style="font-size:40px;font-weight:700;color:#1e2d6b;line-height:1;">
                {{ number_format($stats['avg_rating'] ?? 4.8, 1) }}
            </div>
            <div style="display:flex;gap:3px;justify-content:center;margin:10px 0;color:#f59e0b;font-size:18px;">
                @php $r = round($stats['avg_rating'] ?? 4.8); @endphp
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $r ? '-fill' : '' }}"></i>
                @endfor
            </div>
            <div style="font-size:13px;color:#8890a8;">Rating dari siswa</div>
        </div>

        {{-- Aksi cepat --}}
        <div style="background:#1e2d6b;border-radius:12px;padding:20px 22px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                <i class="bi bi-lightning-charge-fill" style="font-size:15px;color:#86efac;"></i>
                <span style="font-size:14px;font-weight:600;color:#fff;">Aksi Cepat</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('tutor.jadwal') }}"
                   style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.85);font-size:13px;font-weight:500;text-decoration:none;transition:background .15s;">
                    <i class="bi bi-calendar-plus" style="color:#86efac;font-size:15px;"></i> Tambah slot jadwal
                </a>
                <a href="{{ route('tutor.pemesanan') }}"
                   style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.85);font-size:13px;font-weight:500;text-decoration:none;transition:background .15s;">
                    <i class="bi bi-inbox" style="color:#86efac;font-size:15px;"></i> Lihat pesanan masuk
                </a>
                <a href="#"
                   style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.85);font-size:13px;font-weight:500;text-decoration:none;transition:background .15s;">
                    <i class="bi bi-pencil-square" style="color:#86efac;font-size:15px;"></i> Update profil & tarif
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

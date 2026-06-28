@extends('layouts.tutor')

@section('title', 'Pesanan Masuk — TutorKu')
@section('page-title', 'Pesanan Masuk')

@section('content')

{{-- ── Stats row ─────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-hourglass-split" style="color:#b45309;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Pending</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['pending'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-check-lg" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Dikonfirmasi</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['confirmed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-patch-check-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Selesai</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['completed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-x-circle-fill" style="color:#991b1b;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Ditolak</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['rejected'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-arrow-counterclockwise" style="color:#dc2626;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Dibatalkan</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['canceled'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0f2f8;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-clock-history" style="color:#6b7280;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Kedaluwarsa</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $counts['expired'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ── Filter tabs ───────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;gap:6px;margin-bottom:14px;flex-wrap:wrap;">
    @foreach(['' => 'Semua', 'pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'complete' => 'Selesai', 'rejected' => 'Ditolak', 'canceled' => 'Dibatalkan', 'expired' => 'Kedaluwarsa'] as $val => $label)
        <a href="?status={{ $val }}"
           style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:500;text-decoration:none;transition:all .15s;{{ request('status', '') === $val ? 'background:#1e2d6b;color:#fff;' : 'background:#fff;color:#4b5574;border:1px solid #e8eaf0;' }}">
            {{ $label }}
        </a>
    @endforeach
    <div style="flex:1;"></div>
    <p style="font-size:12px;color:#8890a8;margin:0;">{{ $orders->total() }} pesanan</p>
</div>

{{-- ── Order list ───────────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:10px;">
    @forelse($orders as $order)
        @php
            $st = $order->effective_status;
            $siswaName = $order->student->user->username ?? $order->student->name ?? '-';
            $hari = $order->day_name ?? '-';
            $jam = $order->jam_range ?? '-';
            $durasi = $order->jumlah_jam ?? 0;
        @endphp
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;transition:box-shadow .15s;"
             onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'"
             onmouseout="this.style.boxShadow='none'">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                <div style="display:flex;align-items:flex-start;gap:12px;flex:1;min-width:0;">
                    <div style="width:42px;height:42px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;flex-shrink:0;">
                        {{ strtoupper(substr($siswaName, 0, 2)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <span style="font-weight:600;font-size:14px;color:#1a1a2e;">{{ $siswaName }}</span>
                            @if($st === 'pending')
                                <span style="background:#fffbeb;color:#92400e;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Pending</span>
                            @elseif($st === 'confirmed')
                                @if($order->payments()->where('status', 'paid')->exists())
                                    <span style="background:#f0fdf4;color:#15803d;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Sudah Dibayar</span>
                                @else
                                    <span style="background:#eff6ff;color:#1e40af;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Menunggu Bayar</span>
                                @endif
                            @elseif($st === 'complete')
                                <span style="background:#f0fdf4;color:#15803d;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Selesai</span>
                            @elseif($st === 'rejected')
                                <span style="background:#fef2f2;color:#991b1b;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Ditolak</span>
                            @elseif($st === 'canceled')
                                <span style="background:#fef2f2;color:#dc2626;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Dibatalkan</span>
                            @elseif($st === 'expired')
                                <span style="background:#f0f2f8;color:#6b7280;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Kedaluwarsa</span>
                            @endif
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:6px;">
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-calendar3" style="margin-right:3px;"></i>{{ $hari }}</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-clock" style="margin-right:3px;"></i>{{ $jam }}</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-hourglass-split" style="margin-right:3px;"></i>{{ $durasi }} jam</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-cash" style="margin-right:3px;"></i>Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
                        </div>
                        @if($order->catatan)
                            <div style="font-size:12px;color:#8890a8;background:#f8f9fc;padding:6px 10px;border-radius:6px;margin-bottom:6px;">
                                <i class="bi bi-chat-text" style="margin-right:4px;"></i>{{ $order->catatan }}
                            </div>
                        @endif
                        @if($st === 'pending' && $order->expired_at)
                            @php $isUrgent = $order->expired_at->diffInHours(now()) < 4; @endphp
                            <div style="display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:6px;background:{{ $isUrgent ? '#fef2f2' : '#fffbeb' }};border:1px solid {{ $isUrgent ? '#fecaca' : '#fde68a' }};margin-bottom:6px;">
                                <i class="bi bi-alarm" style="font-size:12px;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};"></i>
                                <span style="font-size:11px;font-weight:500;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};">Segera konfirmasi</span>
                                <span style="font-size:11px;color:{{ $isUrgent ? '#b91c1c' : '#a16207' }};">&middot; Batas: {{ $order->expired_at->translatedFormat('d M, H:i') }} WIB</span>
                                <span class="countdown-tutor" data-expired="{{ $order->expired_at->timestamp }}" style="font-size:11px;font-weight:600;font-family:monospace;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};margin-left:auto;"></span>
                            </div>
                        @endif
                        <span style="font-size:11px;color:#b0b8cc;">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                    <a href="{{ route('tutor.pemesanan.show', $order->id) }}"
                       style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:500;border:1px solid #d0d5e8;background:#fff;color:#1e2d6b;text-decoration:none;transition:background .15s;white-space:nowrap;"
                       onmouseover="this.style.background='#f0f3ff'"
                       onmouseout="this.style.background='#fff'">
                        <i class="bi bi-eye" style="font-size:11px;"></i> Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:52px 0;text-align:center;color:#8890a8;">
            <i class="bi bi-inbox" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px;"></i>
            <p style="font-size:13px;margin:0;">Belum ada pesanan.</p>
        </div>
    @endforelse
</div>

@if($orders->hasPages())
    <div style="margin-top:14px;display:flex;justify-content:center;">
        {{ $orders->appends(request()->query())->links() }}
    </div>
@endif

@push('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-tutor').forEach(function(el) {
        var expired = parseInt(el.dataset.expired);
        var now = Math.floor(Date.now() / 1000);
        var diff = expired - now;
        if (diff <= 0) { el.textContent = 'Kedaluwarsa'; return; }
        var h = Math.floor(diff / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;
        el.textContent = (h > 0 ? h + 'j ' : '') + m + 'm ' + s + 'd';
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush

@endsection

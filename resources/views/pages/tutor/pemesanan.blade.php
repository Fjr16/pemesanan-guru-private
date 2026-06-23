@extends('layouts.tutor')

@section('title', 'Pesanan Masuk — TutorKu')
@section('page-title', 'Pesanan Masuk')

@section('content')

{{-- ── Stats row ─────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-hourglass-split" style="color:#b45309;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Pending</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['pending'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-check-lg" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Dikonfirmasi</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['confirmed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-patch-check-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Selesai</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['completed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-x-circle-fill" style="color:#991b1b;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Ditolak</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['cancelled'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ── Filter tabs ───────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;gap:6px;margin-bottom:14px;flex-wrap:wrap;">
    @foreach(['' => 'Semua', 'pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Ditolak'] as $val => $label)
        <a href="?status={{ $val }}"
           style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:500;text-decoration:none;transition:all .15s;{{ request('status', '') === $val ? 'background:#1e2d6b;color:#fff;' : 'background:#fff;color:#4b5574;border:1px solid #e8eaf0;' }}">
            {{ $label }}
        </a>
    @endforeach
    <div style="flex:1;"></div>
    <p style="font-size:12px;color:#8890a8;margin:0;">{{ $orders->total() ?? 0 }} pesanan</p>
</div>

{{-- ── Order list ───────────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:10px;">
    @forelse($orders ?? [] as $order)
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;transition:box-shadow .15s;"
             onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'"
             onmouseout="this.style.boxShadow='none'">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                <div style="display:flex;align-items:flex-start;gap:12px;flex:1;min-width:0;">
                    <div style="width:42px;height:42px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;flex-shrink:0;">
                        {{ strtoupper(substr($order->siswa ?? 'S', 0, 2)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <span style="font-weight:600;font-size:14px;color:#1a1a2e;">{{ $order->siswa ?? '-' }}</span>
                            @php $st = $order->status ?? 'pending'; @endphp
                            @if($st === 'pending')
                                <span style="background:#fffbeb;color:#92400e;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Pending</span>
                            @elseif($st === 'confirmed')
                                <span style="background:#eff6ff;color:#1e40af;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Dikonfirmasi</span>
                            @elseif($st === 'completed')
                                <span style="background:#f0fdf4;color:#15803d;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Selesai</span>
                            @else
                                <span style="background:#fef2f2;color:#991b1b;font-size:10px;font-weight:500;padding:2px 8px;border-radius:12px;">Ditolak</span>
                            @endif
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:6px;">
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-book" style="margin-right:3px;"></i>{{ $order->mapel ?? '-' }}</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-calendar3" style="margin-right:3px;"></i>{{ $order->hari ?? '-' }}, {{ $order->jam ?? '-' }} WIB</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-clock" style="margin-right:3px;"></i>{{ $order->durasi ?? 0 }} jam</span>
                            <span style="font-size:12px;color:#8890a8;"><i class="bi bi-cash" style="margin-right:3px;"></i>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if(!empty($order->catatan))
                            <div style="font-size:12px;color:#8890a8;background:#f8f9fc;padding:6px 10px;border-radius:6px;margin-bottom:6px;">
                                <i class="bi bi-chat-text" style="margin-right:4px;"></i>{{ $order->catatan }}
                            </div>
                        @endif
                        <span style="font-size:11px;color:#b0b8cc;">{{ $order->waktu ?? '' }}</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                    <a href="{{ route('tutor.pemesanan.show', $order->id ?? 0) }}"
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

@if(isset($orders) && $orders->hasPages())
    <div style="margin-top:14px;display:flex;justify-content:center;">
        {{ $orders->appends(request()->query())->links() }}
    </div>
@endif

@endsection

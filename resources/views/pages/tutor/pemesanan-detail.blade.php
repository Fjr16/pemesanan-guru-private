@extends('layouts.tutor')

@section('title', 'Detail Pesanan #' . $order->id . ' — TutorKu')
@section('page-title', 'Detail Pesanan')

@section('topbar-actions')
<a href="{{ route('tutor.pemesanan') }}" class="tk-topbar-btn">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@push('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-tutor-detail').forEach(function(el) {
        var expired = parseInt(el.dataset.expired);
        var now = Math.floor(Date.now() / 1000);
        var diff = expired - now;
        if (diff <= 0) { el.textContent = 'Kedaluwarsa'; el.style.color = '#dc2626'; return; }
        var h = Math.floor(diff / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;
        el.textContent = (h > 0 ? h + 'j ' : '') + m + 'menit ' + s + 'detik';
        if (diff < 14400) el.style.color = '#dc2626';
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush

@endsection

@section('content')

@php
    $st = $order->effective_status;
    $siswaName = $order->student->user->username ?? $order->student->name ?? '-';
    $siswaEmail = $order->student->user->email ?? '-';
    $siswaHp = $order->student->user->no_hp ?? '-';
    $hari = $order->day_name ?? '-';
    $jam = $order->jam_range ?? '-';
    $durasi = $order->jumlah_jam ?? 0;
@endphp

<div style="display:grid;grid-template-columns:2fr 1fr;gap:14px;">

    {{-- ── Kiri: Detail ────────────────────────────────────── --}}
    <div>

        {{-- Siswa info --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-person" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Data Siswa</span>
                </div>
                @if($st === 'pending')
                    <span style="background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Menunggu Konfirmasi</span>
                @elseif($st === 'confirmed')
                    <span style="background:#eff6ff;color:#1e40af;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Dikonfirmasi</span>
                @elseif($st === 'complete')
                    <span style="background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Selesai</span>
                @elseif($st === 'rejected')
                    <span style="background:#fef2f2;color:#991b1b;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Ditolak</span>
                @elseif($st === 'canceled')
                    <span style="background:#fef2f2;color:#dc2626;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Dibatalkan</span>
                @elseif($st === 'expired')
                    <span style="background:#f0f2f8;color:#6b7280;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Kedaluwarsa</span>
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:48px;height:48px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;">
                    {{ strtoupper(substr($siswaName, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;color:#1a1a2e;">{{ $siswaName }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $siswaEmail }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $siswaHp }}</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Order ID</div>
                    <div style="font-size:13px;font-weight:500;font-family:monospace;">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Tanggal Pesanan</div>
                    <div style="font-size:13px;font-weight:500;">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Jadwal --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-calendar-event" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Jadwal yang Dipesan</span>
            </div>

            @if($order->orderDetails->isNotEmpty())
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($order->orderDetails->sortBy('jam_start') as $detail)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:8px;background:#f8f9fc;border:1px solid #e8eaf0;">
                            <div>
                                <span style="font-size:13px;font-weight:500;color:#1a1a2e;">
                                    {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('l, d F Y') }}
                                </span>
                            </div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <span style="font-size:13px;color:#4b5574;">
                                    {{ $detail->jam_start }} – {{ $detail->jam_end }} WIB
                                </span>
                                <span style="font-size:12px;font-weight:600;color:#1e2d6b;">
                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($order->catatan)
                <div style="margin-top:14px;padding:10px 14px;border-radius:8px;background:#fffbeb;border:1px solid #fde68a;">
                    <div style="font-size:11px;color:#92400e;font-weight:500;margin-bottom:4px;">CATATAN SISWA</div>
                    <div style="font-size:13px;color:#78350f;line-height:1.6;">{{ $order->catatan }}</div>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Kanan: Ringkasan + Aksi ─────────────────────────── --}}
    <div>

        {{-- Ringkasan --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-receipt" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Ringkasan</span>
            </div>

            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Tarif per jam</span>
                <span style="font-size:13px;font-weight:500;">Rp {{ number_format($order->tutor->hourly_rate ?? 0, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Durasi</span>
                <span style="font-size:13px;font-weight:500;">{{ $durasi }} jam</span>
            </div>

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:12px 0;">

            <div style="display:flex;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Total</span>
                <span style="font-size:16px;font-weight:600;color:#1e2d6b;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Aksi --}}
        @if($st === 'pending')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                    <i class="bi bi-check2-square" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Konfirmasi Pesanan</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    <form method="POST" action="{{ route('tutor.pemesanan.terima', $order->id) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:inherit;background:#15803d;color:#fff;transition:background .15s;"
                                onmouseover="this.style.background='#166534'"
                                onmouseout="this.style.background='#15803d'">
                            <i class="bi bi-check-lg"></i> Terima Pesanan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('tutor.pemesanan.tolak', $order->id) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid #fecaca;font-family:inherit;background:#fef2f2;color:#991b1b;transition:background .15s;"
                                onmouseover="this.style.background='#fee2e2'"
                                onmouseout="this.style.background='#fef2f2'">
                            <i class="bi bi-x-lg"></i> Tolak Pesanan
                        </button>
                    </form>
                </div>

                @if($order->expired_at)
                    @php $isUrgent = $order->expired_at->diffInHours(now()) < 4; @endphp
                    <div style="margin-top:12px;display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;border-radius:6px;background:{{ $isUrgent ? '#fef2f2' : '#fffbeb' }};border:1px solid {{ $isUrgent ? '#fecaca' : '#fde68a' }};">
                        <i class="bi bi-alarm" style="font-size:13px;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};"></i>
                        <span style="font-size:12px;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};">Batas konfirmasi:</span>
                        <span class="countdown-tutor-detail" data-expired="{{ $order->expired_at->timestamp }}" style="font-size:12px;font-weight:700;font-family:monospace;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};"></span>
                    </div>
                @endif
                <p style="font-size:11px;color:#8890a8;margin:12px 0 0;text-align:center;">
                    Siswa akan menerima notifikasi email atas keputusan Anda.
                </p>
            </div>
        @elseif($st === 'confirmed')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-check-circle-fill" style="font-size:22px;color:#15803d;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Pesanan Dikonfirmasi</div>
                    <div style="font-size:12px;color:#8890a8;margin-bottom:8px;">Menunggu pembayaran dari siswa.</div>
                    @if($order->expired_at)
                        @php $isUrgent = $order->expired_at->diffInHours(now()) < 4; @endphp
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;border-radius:6px;background:{{ $isUrgent ? '#fef2f2' : '#fffbeb' }};border:1px solid {{ $isUrgent ? '#fecaca' : '#fde68a' }};margin-bottom:14px;">
                            <i class="bi bi-alarm" style="font-size:13px;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};"></i>
                            <span style="font-size:12px;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};">Batas pembayaran:</span>
                            <span class="countdown-tutor-detail" data-expired="{{ $order->expired_at->timestamp }}" style="font-size:12px;font-weight:700;font-family:monospace;color:{{ $isUrgent ? '#dc2626' : '#92400e' }};"></span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tutor.pemesanan.selesai', $order->id) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;"
                                onmouseover="this.style.background='#162252'"
                                onmouseout="this.style.background='#1e2d6b'">
                            <i class="bi bi-patch-check"></i> Tandai Selesai
                        </button>
                    </form>
                </div>
            </div>
        @elseif($st === 'complete')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-patch-check-fill" style="font-size:22px;color:#15803d;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Sesi Selesai</div>
                    <div style="font-size:12px;color:#8890a8;">Pembayaran telah diterima.</div>
                </div>
            </div>
        @elseif($st === 'rejected')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-x-circle-fill" style="font-size:22px;color:#991b1b;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Pesanan Ditolak</div>
                    <div style="font-size:12px;color:#8890a8;">Anda telah menolak pesanan ini.</div>
                </div>
            </div>
        @elseif($st === 'canceled')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-arrow-counterclockwise" style="font-size:22px;color:#dc2626;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Pesanan Dibatalkan</div>
                    <div style="font-size:12px;color:#8890a8;">Siswa telah membatalkan pesanan ini.</div>
                </div>
            </div>
        @elseif($st === 'expired')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#f0f2f8;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-clock-history" style="font-size:22px;color:#6b7280;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Pesanan Kedaluwarsa</div>
                    <div style="font-size:12px;color:#8890a8;">Pesanan tidak diproses sebelum batas waktu habis.</div>
                </div>
            </div>
        @endif

        {{-- Timeline --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-top:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-clock-history" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Timeline</span>
            </div>

            @php
                $steps = [
                    ['icon' => 'bi-plus-circle', 'color' => '#3730a3', 'label' => 'Pesanan dibuat', 'time' => $order->created_at, 'done' => true],
                    ['icon' => 'bi-check-lg', 'color' => '#15803d', 'label' => 'Tutor mengkonfirmasi', 'time' => $st === 'confirmed' || $st === 'complete' ? $order->updated_at : null, 'done' => in_array($st, ['confirmed', 'complete'])],
                    ['icon' => 'bi-patch-check', 'color' => '#1e40af', 'label' => 'Sesi selesai', 'time' => $st === 'complete' ? $order->updated_at : null, 'done' => $st === 'complete'],
                ];

                if (in_array($st, ['rejected', 'canceled', 'expired'])) {
                    $label = match($st) {
                        'rejected' => 'Pesanan ditolak',
                        'canceled' => 'Pesanan dibatalkan siswa',
                        'expired'  => 'Pesanan kedaluwarsa',
                    };
                    $steps[] = ['icon' => 'bi-x-circle', 'color' => '#dc2626', 'label' => $label, 'time' => $order->updated_at, 'done' => true];
                }
            @endphp

            @foreach($steps as $item)
                <div style="display:flex;gap:12px;{{ !$loop->last ? 'padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid #f0f2f8;' : '' }}">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $item['done'] ? '#f0f3ff' : '#f8f9fc' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $item['icon'] }}" style="font-size:12px;color:{{ $item['done'] ? $item['color'] : '#b0b8cc' }};"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:500;color:{{ $item['done'] ? '#1a1a2e' : '#b0b8cc' }};">{{ $item['label'] }}</div>
                        <div style="font-size:11px;color:#8890a8;">{{ $item['time'] ? $item['time']->translatedFormat('d F Y, H:i') : '-' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@push('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-tutor-detail').forEach(function(el) {
        var expired = parseInt(el.dataset.expired);
        var now = Math.floor(Date.now() / 1000);
        var diff = expired - now;
        if (diff <= 0) { el.textContent = 'Kedaluwarsa'; el.style.color = '#dc2626'; return; }
        var h = Math.floor(diff / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;
        el.textContent = (h > 0 ? h + 'j ' : '') + m + 'menit ' + s + 'detik';
        if (diff < 14400) el.style.color = '#dc2626';
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush

@endsection

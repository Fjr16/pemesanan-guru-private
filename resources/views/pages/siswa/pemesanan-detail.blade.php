@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . $order->id . ' — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:860px;margin:0 auto;padding:0 20px;">

        {{-- Breadcrumb --}}
        <nav style="margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;">
                <a href="{{ route('siswa.dashboard') }}" style="color:#1e2d6b;text-decoration:none;">Dashboard</a>
                <span style="color:#b0b8cc;">/</span>
                <a href="{{ route('siswa.pemesanan') }}" style="color:#1e2d6b;text-decoration:none;">Pemesanan</a>
                <span style="color:#b0b8cc;">/</span>
                <span style="color:#8890a8;">Detail #{{ $order->id }}</span>
            </div>
        </nav>

        @php
            $statusBg = match($order->effective_status) {
                'pending' => '#fffbeb',
                'confirmed' => '#eff6ff',
                'complete' => '#f0fdf4',
                'canceled' => '#fef2f2',
                'rejected' => '#fef2f2',
                'expired' => '#f0f2f8',
                default => '#f0f2f8',
            };
            $statusClr = match($order->effective_status) {
                'pending' => '#92400e',
                'confirmed' => '#1e40af',
                'complete' => '#15803d',
                'canceled' => '#dc2626',
                'rejected' => '#dc2626',
                'expired' => '#6b7280',
                default => '#4b5574',
            };
            $statusIcon = match($order->effective_status) {
                'pending' => 'bi-hourglass-split',
                'confirmed' => 'bi-patch-check-fill',
                'complete' => 'bi-check-circle-fill',
                'canceled' => 'bi-x-circle-fill',
                'rejected' => 'bi-x-circle-fill',
                'expired' => 'bi-clock-history',
                default => 'bi-circle',
            };
            $statusLbl = match($order->effective_status) {
                'pending' => 'Menunggu Konfirmasi Tutor',
                'confirmed' => 'Tutor Telah Mengkonfirmasi',
                'complete' => 'Sesi Selesai',
                'canceled' => 'Pemesanan Dibatalkan',
                'rejected' => 'Pemesanan Ditolak Tutor',
                'expired' => 'Pemesanan Kedaluwarsa',
                default => ucfirst($order->effective_status),
            };
            if ($order->effective_status === 'confirmed' && $order->payments()->where('status', 'paid')->exists()) {
                $statusBg = '#f0fdf4';
                $statusClr = '#15803d';
                $statusIcon = 'bi-wallet2';
                $statusLbl = 'Pembayaran Diterima';
            }
        @endphp

        <div style="display:grid;grid-template-columns:1fr 340px;gap:14px;align-items:start;">

            {{-- LEFT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Info Tutor --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-person-circle" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Informasi Tutor</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;">
                        <div style="width:52px;height:52px;border-radius:50%;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($order->tutor->name ?? 'TK', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:16px;font-weight:600;color:#1a1a2e;">{{ $order->tutor->name ?? '-' }}</div>
                            <div style="font-size:13px;color:#8890a8;margin-top:2px;">{{ $order->tutor->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Detail Jadwal --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-calendar3" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Detail Jadwal</span>
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
                            <div style="font-size:11px;color:#92400e;font-weight:500;margin-bottom:4px;">CATATAN</div>
                            <div style="font-size:13px;color:#78350f;line-height:1.6;">{{ $order->catatan }}</div>
                        </div>
                    @endif
                </div>

                {{-- Status Pesanan --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-flag" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Status Pesanan</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;padding:14px;border-radius:10px;background:{{ $statusBg }};">
                        <i class="bi {{ $statusIcon }}" style="font-size:22px;color:{{ $statusClr }};"></i>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:{{ $statusClr }};">{{ $statusLbl }}</div>
                            <div style="font-size:12px;color:{{ $statusClr }};opacity:.7;margin-top:2px;">
                                @if($order->effective_status === 'pending')
                                    Menunggu tutor mengkonfirmasi jadwal Anda.
                                @elseif($order->effective_status === 'confirmed')
                                    @if($order->payments()->where('status', 'paid')->exists())
                                        Pembayaran telah diterima. Sesi siap dimulai sesuai jadwal.
                                    @else
                                        Tutor telah mengkonfirmasi. Silakan lakukan pembayaran.
                                    @endif
                                @elseif($order->effective_status === 'complete')
                                    Sesi telah selesai. Terima kasih!
                                @elseif($order->effective_status === 'canceled')
                                    Pemesanan ini telah dibatalkan.
                                @elseif($order->effective_status === 'rejected')
                                    Tutor menolak pemesanan ini.
                                @elseif($order->effective_status === 'expired')
                                    Pesanan tidak diproses sebelum batas waktu habis.
                                @endif
                                @if(in_array($order->effective_status, ['pending', 'confirmed']) && $order->expired_at && !$order->payments()->where('status', 'paid')->exists())
                                    <div style="margin-top:10px;display:flex;align-items:center;gap:8px;">
                                        <i class="bi bi-alarm" style="font-size:14px;color:#92400e;"></i>
                                        <span style="font-size:12px;color:#92400e;font-weight:500;">Batas waktu:</span>
                                        <span class="countdown-detail" data-expired="{{ $order->expired_at->timestamp }}" style="font-size:13px;font-weight:700;font-family:monospace;color:#92400e;"></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Ringkasan --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-receipt" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Ringkasan</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Tutor</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->tutor->name ?? '-' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Tanggal</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->tanggal ?? '-' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Jam</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->jam_range ?? '-' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Jumlah Jam</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->jumlah_jam }} jam</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:15px;margin-top:4px;">
                        <span style="font-weight:600;color:#1a1a2e;">Total</span>
                        <span style="font-weight:700;color:#1e2d6b;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Aksi --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-lightning" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Aksi</span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @if($order->effective_status === 'confirmed' && !$order->payments()->where('status', 'paid')->exists())
                            <a href="{{ route('siswa.pembayaran.show', $order->id) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;background:#1e2d6b;color:#fff;font-size:13px;font-weight:500;text-decoration:none;">
                                <i class="bi bi-wallet2"></i> Bayar Sekarang
                            </a>
                        @endif

                        @if($order->effective_status === 'confirmed' && $order->payments()->where('status', 'paid')->exists())
                            <div style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;background:#f0fdf4;border:1px solid #86efac;color:#15803d;font-size:13px;font-weight:500;">
                                <i class="bi bi-check-circle"></i> Sesi Siap Dimulai
                            </div>
                        @endif

                        @if($order->effective_status === 'pending')
                            <form id="cancel-form" method="POST" action="{{ route('siswa.pemesanan.cancel', $order->id) }}">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button"
                                onclick="confirmCancel()"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;border:1px solid #fca5a5;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:500;cursor:pointer;">
                                <i class="bi bi-x-circle"></i> Batalkan Pemesanan
                            </button>
                        @endif

                        <a href="{{ route('siswa.pemesanan') }}"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;border:1px solid #d0d5e8;background:#fff;color:#1e2d6b;font-size:13px;text-decoration:none;">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                {{-- Timeline --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-clock-history" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Timeline</span>
                    </div>
                    @php
                        $st = $order->effective_status;
                        $isPaid = $order->payments()->where('status', 'paid')->exists();
                        $steps = [
                            ['label' => 'Pemesanan dibuat', 'done' => true, 'icon' => 'bi-plus-circle-fill', 'color' => '#1e2d6b'],
                            ['label' => 'Menunggu konfirmasi tutor', 'done' => in_array($st, ['confirmed', 'complete']), 'icon' => 'bi-hourglass-split', 'color' => '#b45309', 'active' => $st === 'pending'],
                        ];

                        if (in_array($st, ['confirmed', 'complete'])) {
                            $steps[] = ['label' => 'Tutor mengkonfirmasi', 'done' => true, 'icon' => 'bi-patch-check-fill', 'color' => '#1e40af', 'active' => false];
                            $steps[] = ['label' => $isPaid ? 'Pembayaran berhasil' : 'Menunggu pembayaran', 'done' => $isPaid, 'icon' => 'bi-wallet2', 'color' => $isPaid ? '#15803d' : '#b45309', 'active' => !$isPaid && $st === 'confirmed'];
                        }

                        $steps[] = ['label' => 'Sesi selesai', 'done' => $st === 'complete', 'icon' => 'bi-check-circle-fill', 'color' => '#15803d', 'active' => false];

                        if (in_array($st, ['canceled', 'rejected', 'expired'])) {
                            $label = match($st) {
                                'canceled' => 'Dibatalkan',
                                'rejected' => 'Ditolak tutor',
                                'expired'  => 'Kedaluwarsa',
                            };
                            $steps[] = ['label' => $label, 'done' => true, 'icon' => 'bi-x-circle-fill', 'color' => '#dc2626'];
                        }
                    @endphp
                    @foreach($steps as $step)
                        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;">
                            <i class="bi {{ $step['icon'] }}" style="font-size:16px;color:{{ $step['done'] ? $step['color'] : '#d0d5e8' }};"></i>
                            <span style="font-size:13px;color:{{ $step['done'] ? '#1a1a2e' : '#b0b8cc' }};font-weight:{{ !empty($step['active']) && $step['active'] ? '600' : '400' }};">
                                {{ $step['label'] }}
                                @if(!empty($step['active']) && $step['active'])
                                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#f59e0b;margin-left:6px;animation:pulse 1.5s infinite;"></span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .3; }
}
</style>

@endsection

@push('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-detail').forEach(function(el) {
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

function confirmCancel() {
    Swal.fire({
        title: 'Batalkan Pemesanan?',
        text: 'Pemesanan yang dibatalkan tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form').submit();
        }
    });
}
</script>
@endpush

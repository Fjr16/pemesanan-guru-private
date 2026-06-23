@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . $order['id'] . ' — TutorKu')

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
                <span style="color:#8890a8;">Detail #{{ $order['id'] }}</span>
            </div>
        </nav>

        <div style="display:grid;grid-template-columns:1fr 340px;gap:14px;align-items:start;">

            {{-- LEFT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Info Tutor & Mapel --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-person-circle" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Informasi Tutor & Mata Pelajaran</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;margin-bottom:16px;">
                        <div style="width:52px;height:52px;border-radius:50%;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($order['tutor_name'], 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:16px;font-weight:600;color:#1a1a2e;">{{ $order['tutor_name'] }}</div>
                            <div style="font-size:13px;color:#8890a8;margin-top:2px;">{{ $order['tutor_email'] }}</div>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <div style="font-size:11px;color:#8890a8;font-weight:500;margin-bottom:4px;">MATA PELAJARAN</div>
                            <div style="font-size:14px;font-weight:500;color:#1a1a2e;">{{ $order['mapel'] }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:#8890a8;font-weight:500;margin-bottom:4px;">TANGGAL PEMESANAN</div>
                            <div style="font-size:14px;font-weight:500;color:#1a1a2e;">{{ $order['created_at'] }}</div>
                        </div>
                    </div>
                </div>

                {{-- Detail Jadwal --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-calendar3" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Detail Jadwal</span>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                        <div style="padding:12px;border-radius:10px;background:#f8f9fc;text-align:center;">
                            <div style="font-size:11px;color:#8890a8;margin-bottom:6px;">Hari</div>
                            <div style="font-size:15px;font-weight:600;color:#1a1a2e;">{{ $order['hari'] }}</div>
                        </div>
                        <div style="padding:12px;border-radius:10px;background:#f8f9fc;text-align:center;">
                            <div style="font-size:11px;color:#8890a8;margin-bottom:6px;">Jam</div>
                            <div style="font-size:15px;font-weight:600;color:#1a1a2e;">{{ $order['jam'] }} WIB</div>
                        </div>
                        <div style="padding:12px;border-radius:10px;background:#f8f9fc;text-align:center;">
                            <div style="font-size:11px;color:#8890a8;margin-bottom:6px;">Durasi</div>
                            <div style="font-size:15px;font-weight:600;color:#1a1a2e;">{{ $order['durasi'] }} jam</div>
                        </div>
                    </div>

                    @if(!empty($order['catatan']))
                        <div style="margin-top:14px;padding:10px 14px;border-radius:8px;background:#fffbeb;border:1px solid #fde68a;">
                            <div style="font-size:11px;color:#92400e;font-weight:500;margin-bottom:4px;">CATATAN</div>
                            <div style="font-size:13px;color:#78350f;line-height:1.6;">{{ $order['catatan'] }}</div>
                        </div>
                    @endif
                </div>

                {{-- Status Pesanan --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:16px;">
                        <i class="bi bi-flag" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Status Pesanan</span>
                    </div>
                    @php
                        $statusBg = match($order['status']) {
                            'pending' => '#fffbeb',
                            'confirmed' => '#eff6ff',
                            'completed' => '#f0fdf4',
                            'cancelled' => '#fef2f2',
                            default => '#f0f2f8',
                        };
                        $statusClr = match($order['status']) {
                            'pending' => '#92400e',
                            'confirmed' => '#1e40af',
                            'completed' => '#15803d',
                            'cancelled' => '#dc2626',
                            default => '#4b5574',
                        };
                        $statusIcon = match($order['status']) {
                            'pending' => 'bi-hourglass-split',
                            'confirmed' => 'bi-patch-check-fill',
                            'completed' => 'bi-check-circle-fill',
                            'cancelled' => 'bi-x-circle-fill',
                            default => 'bi-circle',
                        };
                        $statusLbl = match($order['status']) {
                            'pending' => 'Menunggu Konfirmasi Tutor',
                            'confirmed' => 'Tutor Telah Mengkonfirmasi',
                            'completed' => 'Sesi Selesai',
                            'cancelled' => 'Pemesanan Dibatalkan',
                            default => ucfirst($order['status']),
                        };
                    @endphp
                    <div style="display:flex;align-items:center;gap:12px;padding:14px;border-radius:10px;background:{{ $statusBg }};">
                        <i class="bi {{ $statusIcon }}" style="font-size:22px;color:{{ $statusClr }};"></i>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:{{ $statusClr }};">{{ $statusLbl }}</div>
                            <div style="font-size:12px;color:{{ $statusClr }};opacity:.7;margin-top:2px;">
                                @if($order['status'] === 'pending')
                                    Menunggu tutor mengkonfirmasi jadwal Anda.
                                @elseif($order['status'] === 'confirmed')
                                    Silakan lakukan pembayaran untuk mengunci sesi.
                                @elseif($order['status'] === 'completed')
                                    Sesi telah selesai. Terima kasih!
                                @elseif($order['status'] === 'cancelled')
                                    Pemesanan ini telah dibatalkan.
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
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['tutor_name'] }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Mapel</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['mapel'] }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Jadwal</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['hari'] }}, {{ $order['jam'] }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Durasi</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['durasi'] }} jam</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:15px;margin-top:4px;">
                        <span style="font-weight:600;color:#1a1a2e;">Total</span>
                        <span style="font-weight:700;color:#1e2d6b;">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Aksi --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-lightning" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Aksi</span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @if($order['status'] === 'confirmed')
                            <a href="{{ route('siswa.pembayaran.show', $order['id']) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;background:#1e2d6b;color:#fff;font-size:13px;font-weight:500;text-decoration:none;">
                                <i class="bi bi-wallet2"></i> Bayar Sekarang
                            </a>
                        @endif

                        @if($order['status'] === 'pending')
                            <form method="POST" action="{{ route('siswa.pemesanan.cancel', $order['id']) }}" onsubmit="return confirm('Batalkan pemesanan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;border:1px solid #fca5a5;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:500;cursor:pointer;">
                                    <i class="bi bi-x-circle"></i> Batalkan Pemesanan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('siswa.pemesanan') }}"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;border:1px solid #d0d5e8;background:#fff;color:#1e2d6b;font-size:13px;text-decoration:none;">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                {{-- Timeline Status --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-clock-history" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Timeline</span>
                    </div>
                    @php
                        $steps = [
                            ['label' => 'Pemesanan dibuat', 'done' => true, 'icon' => 'bi-plus-circle-fill', 'color' => '#1e2d6b'],
                            ['label' => 'Menunggu konfirmasi', 'done' => in_array($order['status'], ['confirmed', 'completed']), 'icon' => 'bi-hourglass-split', 'color' => '#b45309', 'active' => $order['status'] === 'pending'],
                            ['label' => 'Tutor mengkonfirmasi', 'done' => in_array($order['status'], ['completed']), 'icon' => 'bi-patch-check-fill', 'color' => '#1e40af', 'active' => $order['status'] === 'confirmed'],
                            ['label' => 'Sesi selesai', 'done' => $order['status'] === 'completed', 'icon' => 'bi-check-circle-fill', 'color' => '#15803d', 'active' => false],
                        ];

                        if ($order['status'] === 'cancelled') {
                            $steps[] = ['label' => 'Dibatalkan', 'done' => true, 'icon' => 'bi-x-circle-fill', 'color' => '#dc2626'];
                        }
                    @endphp
                    @foreach($steps as $step)
                        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? '' : '' }}">
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

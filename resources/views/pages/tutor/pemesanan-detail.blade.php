@extends('layouts.tutor')

@section('title', 'Detail Pesanan — TutorKu')
@section('page-title', 'Detail Pesanan')

@section('topbar-actions')
<a href="{{ route('tutor.pemesanan') }}" class="tk-topbar-btn">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')

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
                @php $st = $order->status ?? 'pending'; @endphp
                @if($st === 'pending')
                    <span style="background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Menunggu Konfirmasi</span>
                @elseif($st === 'confirmed')
                    <span style="background:#eff6ff;color:#1e40af;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Dikonfirmasi</span>
                @elseif($st === 'completed')
                    <span style="background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Selesai</span>
                @else
                    <span style="background:#fef2f2;color:#991b1b;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Ditolak</span>
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:48px;height:48px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;">
                    {{ strtoupper(substr($order->siswa ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;color:#1a1a2e;">{{ $order->siswa ?? '-' }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $order->email ?? '' }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $order->no_hp ?? '' }}</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Mata Pelajaran</div>
                    <div style="font-size:13px;font-weight:500;">{{ $order->mapel ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Order ID</div>
                    <div style="font-size:13px;font-weight:500;font-family:monospace;">#{{ str_pad($order->id ?? 0, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        {{-- Jadwal --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-calendar-event" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Jadwal yang Dipesan</span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;padding:14px;background:#f8f9fc;border-radius:8px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Hari</div>
                    <div style="font-size:14px;font-weight:600;color:#1a1a2e;">{{ $order->hari ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Jam</div>
                    <div style="font-size:14px;font-weight:600;color:#1a1a2e;">{{ $order->jam ?? '-' }} WIB</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Durasi</div>
                    <div style="font-size:14px;font-weight:600;color:#1a1a2e;">{{ $order->durasi ?? 0 }} jam</div>
                </div>
            </div>
        </div>

        {{-- Catatan siswa --}}
        @if(!empty($order->catatan))
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
                    <i class="bi bi-chat-text" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Catatan Siswa</span>
                </div>
                <p style="font-size:13px;color:#4b5574;margin:0;line-height:1.6;background:#f8f9fc;padding:12px;border-radius:8px;">{{ $order->catatan }}</p>
            </div>
        @endif

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
                <span style="font-size:13px;font-weight:500;">Rp {{ number_format($order->tarif ?? 0, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Durasi</span>
                <span style="font-size:13px;font-weight:500;">{{ $order->durasi ?? 0 }} jam</span>
            </div>

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:12px 0;">

            <div style="display:flex;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Total</span>
                <span style="font-size:16px;font-weight:600;color:#1e2d6b;">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Aksi (hanya jika pending) --}}
        @if($st === 'pending')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                    <i class="bi bi-check2-square" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Konfirmasi Pesanan</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    <form method="POST" action="{{ route('tutor.pemesanan.terima', $order->id ?? 0) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:inherit;background:#15803d;color:#fff;transition:background .15s;"
                                onmouseover="this.style.background='#166534'"
                                onmouseout="this.style.background='#15803d'">
                            <i class="bi bi-check-lg"></i> Terima Pesanan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('tutor.pemesanan.tolak', $order->id ?? 0) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid #fecaca;font-family:inherit;background:#fef2f2;color:#991b1b;transition:background .15s;"
                                onmouseover="this.style.background='#fee2e2'"
                                onmouseout="this.style.background='#fef2f2'">
                            <i class="bi bi-x-lg"></i> Tolak Pesanan
                        </button>
                    </form>
                </div>

                <p style="font-size:11px;color:#8890a8;margin:12px 0 0;text-align:center;">
                    Siswa akan menerima notifikasi email atas keputusan Anda.
                </p>
            </div>
        @elseif($st === 'confirmed')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-check-circle-fill" style="font-size:22px;color:#15803d;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Pesanan Dikonfirmasi</div>
                    <div style="font-size:12px;color:#8890a8;">Menunggu pembayaran dari siswa.</div>
                </div>
            </div>
        @elseif($st === 'completed')
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="text-align:center;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-patch-check-fill" style="font-size:22px;color:#15803d;"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">Sesi Selesai</div>
                    <div style="font-size:12px;color:#8890a8;">Pembayaran telah diterima.</div>
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
                $timeline = [
                    ['icon'=>'bi-plus-circle','color'=>'#3730a3','label'=>'Pesanan dibuat','time'=>$order->created_at ?? null],
                    ['icon'=>'bi-check-lg','color'=>'#15803d','label'=>'Anda konfirmasi','time'=>$order->confirmed_at ?? null],
                    ['icon'=>'bi-wallet2','color'=>'#b45309','label'=>'Pembayaran diterima','time'=>$order->paid_at ?? null],
                    ['icon'=>'bi-patch-check','color'=>'#1e40af','label'=>'Sesi selesai','time'=>$order->completed_at ?? null],
                ];
            @endphp

            @foreach($timeline as $item)
                <div style="display:flex;gap:12px;{{ !$loop->last ? 'padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid #f0f2f8;' : '' }}">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $item['time'] ? '#f0f3ff' : '#f8f9fc' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $item['icon'] }}" style="font-size:12px;color:{{ $item['time'] ? $item['color'] : '#b0b8cc' }};"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:500;color:{{ $item['time'] ? '#1a1a2e' : '#b0b8cc' }};">{{ $item['label'] }}</div>
                        <div style="font-size:11px;color:#8890a8;">{{ $item['time'] ? $item['time'] : '-' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection

@extends('layouts.admin')

@section('title', 'Detail Transaksi — Admin TutorKu')
@section('page-title', 'Detail Transaksi')

@section('topbar-actions')
<a href="{{ route('admin.transaksi') }}" class="tk-topbar-btn">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:2fr 1fr;gap:14px;">

    {{-- ── Kiri: Detail order ─────────────────────────────── --}}
    <div>

        {{-- Order info --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Order ID</div>
                    <div style="font-size:15px;font-weight:600;color:#1a1a2e;font-family:monospace;">
                        #{{ str_pad($order->id ?? 0, 5, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                @php $st = $order->status ?? 'pending'; @endphp
                @if($st === 'completed')
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:12px;font-weight:500;padding:4px 12px;border-radius:20px;">
                        <i class="bi bi-check-circle-fill" style="font-size:11px;"></i> Completed
                    </span>
                @elseif($st === 'confirmed')
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#eff6ff;color:#1e40af;font-size:12px;font-weight:500;padding:4px 12px;border-radius:20px;">
                        <i class="bi bi-check-lg" style="font-size:11px;"></i> Confirmed
                    </span>
                @elseif($st === 'cancelled')
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#991b1b;font-size:12px;font-weight:500;padding:4px 12px;border-radius:20px;">
                        <i class="bi bi-x-circle-fill" style="font-size:11px;"></i> Cancelled
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:4px;background:#fffbeb;color:#92400e;font-size:12px;font-weight:500;padding:4px 12px;border-radius:20px;">
                        <i class="bi bi-hourglass-split" style="font-size:11px;"></i> Pending
                    </span>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:4px;">Siswa</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;">
                            {{ strtoupper(substr($order->student->name ?? 'S', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-weight:500;font-size:13px;">{{ $order->student->name ?? '-' }}</div>
                            <div style="font-size:11px;color:#8890a8;">{{ $order->student->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:4px;">Tutor</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;">
                            {{ strtoupper(substr($order->tutor->name ?? 'T', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-weight:500;font-size:13px;">{{ $order->tutor->name ?? '-' }}</div>
                            <div style="font-size:11px;color:#8890a8;">{{ $order->tutor->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order details --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-calendar-event" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Detail Jadwal</span>
            </div>
            <div class="table-responsive">
                <table style="width:100%;font-size:13px;border-collapse:collapse">
                    <thead>
                        <tr>
                            @foreach(['Tanggal','Jam','Durasi','Harga'] as $col)
                                <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">
                                    {{ $col }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->details ?? [] as $detail)
                            <tr>
                                <td style="padding:10px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                                    {{ $detail->tanggal ?? '-' }}
                                </td>
                                <td style="padding:10px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                                    {{ $detail->jam_start ?? '-' }} - {{ $detail->jam_end ?? '-' }}
                                </td>
                                <td style="padding:10px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                                    {{ $detail->durasi ?? '-' }} jam
                                </td>
                                <td style="padding:10px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;white-space:nowrap;">
                                    Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:24px 0;text-align:center;color:#8890a8;font-size:13px;">
                                    Belum ada detail jadwal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Catatan --}}
        @if($order->catatan ?? false)
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
                    <i class="bi bi-chat-text" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Catatan Siswa</span>
                </div>
                <p style="font-size:13px;color:#4b5574;margin:0;line-height:1.6;">{{ $order->catatan }}</p>
            </div>
        @endif

    </div>

    {{-- ── Kanan: Ringkasan pembayaran ───────────────────── --}}
    <div>

        {{-- Payment summary --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-wallet2" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Pembayaran</span>
            </div>

            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Metode</span>
                <span style="font-size:13px;font-weight:500;">{{ ucfirst($order->payment->metode ?? '-') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Status</span>
                @php $pstat = $order->payment->status ?? 'unpaid'; @endphp
                @if($pstat === 'paid')
                    <span style="font-size:11px;font-weight:500;padding:2px 8px;border-radius:12px;background:#f0fdf4;color:#15803d;">Lunas</span>
                @elseif($pstat === 'pending')
                    <span style="font-size:11px;font-weight:500;padding:2px 8px;border-radius:12px;background:#fffbeb;color:#92400e;">Pending</span>
                @else
                    <span style="font-size:11px;font-weight:500;padding:2px 8px;border-radius:12px;background:#f8f9fc;color:#8890a8;">Belum Bayar</span>
                @endif
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:13px;color:#8890a8;">Transaction ID</span>
                <span style="font-size:12px;font-family:monospace;color:#4b5574;">{{ $order->payment->transactionId ?? '-' }}</span>
            </div>

            <hr style="border:none;border-top:1px solid #f0f2f8;margin:12px 0;">

            <div style="display:flex;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Total</span>
                <span style="font-size:16px;font-weight:600;color:#1e2d6b;">Rp {{ number_format($order->total_payment ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Timeline --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                <i class="bi bi-clock-history" style="font-size:14px;color:#1e2d6b;"></i>
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Timeline</span>
            </div>

            @php
                $timeline = [
                    ['icon'=>'bi-plus-circle','color'=>'#3730a3','label'=>'Order dibuat','time'=>$order->created_at ?? null],
                    ['icon'=>'bi-check-lg','color'=>'#1e40af','label'=>'Tutor konfirmasi','time'=>$order->confirmed_at ?? null],
                    ['icon'=>'bi-wallet2','color'=>'#b45309','label'=>'Pembayaran diterima','time'=>$order->payment->paid_at ?? null],
                    ['icon'=>'bi-patch-check','color'=>'#15803d','label'=>'Selesai','time'=>$order->completed_at ?? null],
                ];
            @endphp

            @foreach($timeline as $item)
                <div style="display:flex;gap:12px;{{ !$loop->last ? 'padding-bottom:16px;margin-bottom:16px;border-bottom:1px solid #f0f2f8;' : '' }}">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $item['time'] ? '#f0f3ff' : '#f8f9fc' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $item['icon'] }}" style="font-size:12px;color:{{ $item['time'] ? $item['color'] : '#b0b8cc' }};"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:500;color:{{ $item['time'] ? '#1a1a2e' : '#b0b8cc' }};">{{ $item['label'] }}</div>
                        <div style="font-size:11px;color:#8890a8;">{{ $item['time'] ? $item['time']->translatedFormat('d F Y H:i') : '-' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>

@endsection

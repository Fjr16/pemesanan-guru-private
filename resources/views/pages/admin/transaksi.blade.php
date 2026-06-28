@extends('layouts.admin')

@section('title', 'Transaksi — Admin TutorKu')
@section('page-title', 'Rekap Transaksi')

@section('content')

{{-- ── Stat cards ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-calendar-check-fill" style="color:#3730a3;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Total Booking</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['total'] ?? 0 }}</div>
        </div>
    </div>
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
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-check-circle-fill" style="color:#15803d;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Confirmed</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['confirmed'] ?? 0 }}</div>
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-patch-check-fill" style="color:#1e40af;font-size:16px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:#8890a8;">Completed</div>
            <div style="font-size:20px;font-weight:600;color:#1a1a2e;">{{ $stats['completed'] ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ── Filter bar ───────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 18px;margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.transaksi') }}" style="display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap;">
        <div style="flex:1;min-width:180px;">
            <label style="font-size:11px;font-weight:500;color:#8890a8;margin-bottom:4px;display:block;">Cari siswa / tutor</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8890a8;font-size:12px;pointer-events:none"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama atau email..."
                       style="width:100%;height:34px;padding:0 12px 0 30px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
            </div>
        </div>
        <div style="width:140px;">
            <label style="font-size:11px;font-weight:500;color:#8890a8;margin-bottom:4px;display:block;">Status</label>
            <select name="status" style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                <option value="">Semua</option>
                @foreach(['pending','confirmed','complete','canceled','rejected','expired'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div style="width:140px;">
            <label style="font-size:11px;font-weight:500;color:#8890a8;margin-bottom:4px;display:block;">Dari tanggal</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
        </div>
        <div style="width:140px;">
            <label style="font-size:11px;font-weight:500;color:#8890a8;margin-bottom:4px;display:block;">Sampai</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
        </div>
        <div style="display:flex;gap:6px;">
            <button type="submit"
                    style="height:34px;padding:0 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;display:inline-flex;align-items:center;gap:4px;">
                <i class="bi bi-funnel" style="font-size:12px;"></i> Filter
            </button>
            @if(request()->hasAny(['q','status','from','to']))
                <a href="{{ route('admin.transaksi') }}"
                   style="height:34px;padding:0 14px;border-radius:8px;font-size:13px;font-weight:500;border:1px solid #d0d5e8;background:#fff;color:#4b5574;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    <i class="bi bi-x" style="font-size:12px;"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ── Table ─────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;overflow:hidden">
    <div class="table-responsive">
        <table style="width:100%;font-size:13px;border-collapse:collapse">
            <thead>
                <tr>
                    @foreach(['ID','Siswa','Tutor','Jadwal','Total','Status','Pembayaran','Tgl Booking'] as $col)
                        <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">
                            {{ $col }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $order)
                    @php
                        $payment = $order->payments->last();
                        $paymentStatus = $payment->status ?? 'unpaid';
                    @endphp
                    <tr style="transition:background .1s;cursor:pointer" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''" onclick="window.location='{{ route('admin.transaksi.show', $order->id) }}'">
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;font-family:monospace;font-size:12px;color:#8890a8;vertical-align:middle;">
                            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                            <div style="font-weight:500;font-size:13px;">{{ $order->student->name ?? '-' }}</div>
                            <div style="font-size:11px;color:#8890a8;">{{ $order->student->user->email ?? '' }}</div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                            <div style="font-weight:500;font-size:13px;">{{ $order->tutor->name ?? '-' }}</div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;white-space:nowrap;">
                            <div style="font-size:13px;">{{ $order->day_name ?? '-' }}</div>
                            <div style="font-size:11px;color:#8890a8;">{{ $order->jam_range ?? '' }} WIB</div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;white-space:nowrap;">
                            <span style="font-weight:500;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                            @if($order->status === 'complete')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Selesai
                                </span>
                            @elseif($order->status === 'confirmed')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#eff6ff;color:#1e40af;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-check-lg" style="font-size:10px;"></i> Dikonfirmasi
                                </span>
                            @elseif($order->status === 'canceled')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#991b1b;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Dibatalkan
                                </span>
                            @elseif($order->status === 'rejected')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#991b1b;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Ditolak
                                </span>
                            @elseif($order->status === 'expired')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0f2f8;color:#6b7280;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-clock-history" style="font-size:10px;"></i> Kedaluwarsa
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-hourglass-split" style="font-size:10px;"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;">
                            @if($paymentStatus === 'paid')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Lunas
                                </span>
                            @elseif($paymentStatus === 'pending')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    Pending
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f8f9fc;color:#8890a8;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;">
                                    Belum
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;font-size:12px;color:#8890a8;white-space:nowrap;">
                            {{ $order->created_at->translatedFormat('d F Y') }}<br>
                            <span style="font-size:11px;">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:52px 0;text-align:center;color:#8890a8">
                            <i class="bi bi-inbox" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px"></i>
                            <p style="font-size:13px;margin:0">Belum ada transaksi.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div style="padding:14px 16px;border-top:1px solid #f0f2f8;display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:12px;color:#8890a8;">
                Menampilkan {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} dari {{ $bookings->total() }} transaksi
            </div>
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection

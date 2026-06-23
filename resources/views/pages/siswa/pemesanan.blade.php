@extends('layouts.app')

@section('title', 'Pemesanan Saya — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:860px;margin:0 auto;padding:0 20px;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div>
                <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">Pemesanan Saya</h1>
                <p style="font-size:13px;color:#8890a8;margin:0;">
                    Kelola dan pantau semua pemesanan sesi privat Anda.
                </p>
            </div>
            <a href="{{ route('home') }}"
               style="background:#1e2d6b;color:#fff;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-plus"></i> Pesan Baru
            </a>
        </div>

        {{-- Filter Tabs --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:10px 14px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:4px;overflow-x:auto;">
                @php
                    $filters = [
                        'all'       => ['label' => 'Semua',         'count' => $counts['all']],
                        'pending'   => ['label' => 'Pending',       'count' => $counts['pending']],
                        'confirmed' => ['label' => 'Dikonfirmasi',  'count' => $counts['confirmed']],
                        'completed' => ['label' => 'Selesai',       'count' => $counts['completed']],
                        'cancelled' => ['label' => 'Dibatalkan',    'count' => $counts['cancelled']],
                    ];
                    $activeFilter = request('status', 'all');
                @endphp
                @foreach($filters as $key => $f)
                    <a href="{{ route('siswa.pemesanan', $key !== 'all' ? ['status' => $key] : []) }}"
                       style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:{{ $activeFilter === $key ? '600' : '400' }};color:{{ $activeFilter === $key ? '#1e2d6b' : '#8890a8' }};background:{{ $activeFilter === $key ? '#eef2ff' : 'transparent' }};text-decoration:none;white-space:nowrap;transition:all .15s;">
                        {{ $f['label'] }}
                        @if($f['count'] > 0)
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;border-radius:9px;font-size:10px;font-weight:600;padding:0 5px;background:{{ $activeFilter === $key ? '#1e2d6b' : '#e8eaf0' }};color:{{ $activeFilter === $key ? '#fff' : '#4b5574' }};">
                                {{ $f['count'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Order List --}}
        <div style="display:flex;flex-direction:column;gap:12px;">
            @forelse($orders as $order)
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:flex-start;gap:14px;">

                        {{-- Avatar --}}
                        <div style="width:48px;height:48px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($order['tutor_name'], 0, 2)) }}
                        </div>

                        {{-- Info --}}
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:6px;">
                                <h3 style="font-size:15px;font-weight:600;color:#1a1a2e;margin:0;">{{ $order['tutor_name'] }}</h3>
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
                                    $statusLbl = match($order['status']) {
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Dikonfirmasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => ucfirst($order['status']),
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:{{ $statusBg }};color:{{ $statusClr }};">
                                    {{ $statusLbl }}
                                </span>
                            </div>

                            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:10px;">
                                <span style="font-size:12px;color:#8890a8;display:inline-flex;align-items:center;gap:4px;">
                                    <i class="bi bi-book"></i> {{ $order['mapel'] }}
                                </span>
                                <span style="font-size:12px;color:#8890a8;display:inline-flex;align-items:center;gap:4px;">
                                    <i class="bi bi-calendar3"></i> {{ $order['hari'] }}, {{ $order['jam'] }} WIB
                                </span>
                                <span style="font-size:12px;color:#8890a8;display:inline-flex;align-items:center;gap:4px;">
                                    <i class="bi bi-clock"></i> {{ $order['durasi'] }} jam
                                </span>
                            </div>

                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                <div>
                                    <span style="font-size:15px;font-weight:700;color:#1e2d6b;">
                                        Rp {{ number_format($order['total'], 0, ',', '.') }}
                                    </span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    @if($order['status'] === 'pending')
                                        <form method="POST" action="{{ route('siswa.pemesanan.cancel', $order['id']) }}" style="display:inline;" onsubmit="return confirm('Batalkan pemesanan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="border:1px solid #fca5a5;background:#fef2f2;color:#dc2626;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                                <i class="bi bi-x-circle"></i> Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    @if($order['status'] === 'confirmed')
                                        <a href="{{ route('siswa.pembayaran.show', $order['id']) }}"
                                           style="background:#1e2d6b;color:#fff;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                            <i class="bi bi-wallet2"></i> Bayar
                                        </a>
                                    @endif

                                    <a href="{{ route('siswa.pemesanan.show', $order['id']) }}"
                                       style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;font-size:12px;color:#8890a8;text-decoration:none;font-weight:500;">
                                        Detail <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                                    </a>
                                </div>
                            </div>

                            @if(!empty($order['catatan']))
                                <div style="margin-top:10px;padding:8px 12px;border-radius:8px;background:#f8f9fc;font-size:12px;color:#4b5574;line-height:1.5;">
                                    <i class="bi bi-chat-square-text" style="margin-right:4px;color:#8890a8;"></i>
                                    <em>{{ $order['catatan'] }}</em>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:48px 20px;text-align:center;">
                    <i class="bi bi-calendar-x" style="font-size:40px;color:#8890a8;opacity:.3;display:block;margin-bottom:12px;"></i>
                    <h5 style="font-size:15px;font-weight:500;color:#8890a8;margin:0 0 6px;">Belum ada pemesanan</h5>
                    <p style="font-size:13px;color:#b0b8cc;margin:0 0 16px;">
                        @if($activeFilter !== 'all')
                            Tidak ada pemesanan dengan status "{{ $filters[$activeFilter]['label'] }}".
                        @else
                            Anda belum pernah melakukan pemesanan sesi privat.
                        @endif
                    </p>
                    <a href="{{ route('home') }}"
                       style="background:#1e2d6b;color:#fff;border-radius:8px;padding:10px 20px;font-size:13px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-search"></i> Cari Tutor Sekarang
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

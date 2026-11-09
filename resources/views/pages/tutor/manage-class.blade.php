@extends('layouts.tutor')

@section('title', 'Manajemen Kelas — TutorKu')
@section('page-title', 'Manajemen Kelas')

@push('styles')
<style>
    .tk-class-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .tk-class-card {
        display: block;
        background: #fff;
        border: 1px solid #e8eaf0;
        border-radius: 12px;
        padding: 18px 20px;
        text-decoration: none;
        color: inherit;
        transition: box-shadow 0.15s ease, background 0.15s ease, transform 0.15s ease;
    }

    .tk-class-card:hover {
        background: #f8f9fc;
        box-shadow: 0 6px 18px rgba(30, 45, 107, 0.06);
        text-decoration: none;
        color: inherit;
    }

    .tk-class-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .tk-class-main {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .tk-class-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #eef2ff;
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .tk-class-info {
        flex: 1;
        min-width: 0;
    }

    .tk-class-student {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        font-weight: 600;
        font-size: 14px;
        color: #1a1a2e;
        text-transform: uppercase;
    }

    .tk-class-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 6px;
    }

    .tk-class-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #8890a8;
    }

    .tk-class-meta-item i {
        font-size: 12px;
    }

    .tk-class-time {
        display: block;
        font-size: 11px;
        color: #b0b8cc;
    }

    .tk-class-status-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex-shrink: 0;
    }

    .tk-class-status {
        display: inline-block;
        background: #f0fdf4;
        color: #15803d;
        font-size: 10px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .tk-class-empty {
        background: #fff;
        border: 1px solid #e8eaf0;
        border-radius: 12px;
        padding: 52px 20px;
        text-align: center;
        color: #8890a8;
    }

    .tk-class-empty i {
        font-size: 36px;
        opacity: 0.25;
        display: block;
        margin-bottom: 10px;
    }

    .tk-class-empty p {
        font-size: 13px;
        margin: 0;
    }

    @media (max-width: 576px) {
        .tk-content {
            padding: 16px 14px;
        }

        .tk-class-list {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
        }

        .tk-class-card {
            flex: 0 0 280px;
            min-width: 280px;
            padding: 14px 14px;
            border-radius: 10px;
        }

        .tk-class-row {
            flex-direction: row;
            gap: 10px;
            align-items: flex-start;
        }

        .tk-class-main {
            width: auto;
            min-width: 0;
            flex: 1;
        }

        .tk-class-avatar {
            width: 52px;
            height: 52px;
            font-size: 18px;
        }

        .tk-class-student {
            font-size: 13px;
        }

        .tk-class-meta {
            gap: 8px 12px;
        }

        .tk-class-meta-item {
            font-size: 11px;
        }

        .tk-class-status-group {
            width: auto;
            align-self: flex-start;
        }

        .tk-class-status {
            align-self: flex-start;
        }

        .tk-class-empty {
            width: 100%;
            min-width: 100%;
            padding: 36px 16px;
        }

        .tk-class-empty p {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')

{{-- ── Order list ───────────────────────────────────────────── --}}
<div class="tk-class-list">
    @forelse($orders as $order)
        @php
            $siswaName = $order->student->user->username ?? $order->student->name ?? '-';
            $hari = $order->day_name ?? '-';
            $jam = $order->jam_range ?? '-';
        @endphp
        <a href="{{ route('tutor.kelas.show', $order->id) }}" class="tk-class-card">
            <div class="tk-class-row">
                <div class="tk-class-main">
                    <div class="tk-class-avatar">
                        {{ strtoupper(substr($siswaName, 0, 2)) }}
                    </div>
                    <div class="tk-class-info">
                        <div class="tk-class-student">{{ strtoupper($siswaName) }}</div>
                        <div class="tk-class-meta">
                            <span class="tk-class-meta-item"><i class="bi bi-hash"></i>{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="tk-class-meta-item"><i class="bi bi-calendar3"></i>{{ $hari }}</span>
                            <span class="tk-class-meta-item"><i class="bi bi-clock"></i>{{ $jam }}</span>
                        </div>
                        <span class="tk-class-time">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="tk-class-status-group">
                    @if($order->status === 'confirmed' && $order->payments()->where('status', 'paid')->exists())
                        <span class="tk-class-status text-primary">Kelas Dijadwalkan</span>
                    @elseif($order->status === 'complete')
                        <span class="tk-class-status text-success">Kelas Selesai</span>
                    @else
                        <span class="tk-class-status">-</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="tk-class-empty">
            <i class="bi bi-inbox"></i>
            <p>Belum ada kelas yang aktif maupun selesai.</p>
        </div>
    @endforelse
</div>

@endsection

@extends('layouts.tutor')

@section('title', 'Detail Kelas #' . $order->id . ' — TutorKu')
@section('page-title', 'Detail Kelas')

@section('topbar-actions')
<a href="{{ route('tutor.kelas') }}" class="tk-topbar-btn">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')

@php
    $st = $order->effective_status;
    $siswaName = $order->student->user->username ?? $order->student->name ?? '-';
    $siswaEmail = $order->student->user->email ?? '-';
    $siswaHp = $order->student->user->no_hp ?? '-';
@endphp

<div style="display:grid;grid-template-columns:2fr 1fr;gap:14px;">

    {{-- ── Kiri: Detail ────────────────────────────────────── --}}
    <div>
        {{-- Jadwal --}}
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;margin-bottom:14px;">
            <div style="display:flex;flex-direction:column;gap:12px;">
                @forelse($order->orderDetails->sortBy('jam_start') as $detail)
                    <form method="POST" action="{{ route('tutor.kelas.selesai', $detail->id) }}" class="tk-session-card">
                        @csrf
                        <div class="tk-session-header">
                            <div class="tk-session-header-title">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="tk-session-header-meta">
                                <span>{{ $detail->jam_start }} – {{ $detail->jam_end }} WIB</span>
                            </div>
                        </div>

                        <div class="tk-session-body">
                            <div class="tk-form-grid">
                                <div class="tk-form-group">
                                    <label for="materi_{{ $detail->id }}" class="tk-form-label">Materi Pembelajaran</label>
                                    <input id="materi_{{ $detail->id }}" type="text" name="materi" class="tk-form-input" value="{{ old('materi', $detail->materi) }}" placeholder="Contoh: Operasi hitung dasar, persamaan linear, dll" />
                                </div>

                                <div class="tk-form-group">
                                    <label for="tujuan_{{ $detail->id }}" class="tk-form-label">Tujuan Pembelajaran</label>
                                    <textarea id="tujuan_{{ $detail->id }}" name="tujuan_pembelajaran" class="tk-form-textarea" placeholder="Tuliskan tujuan yang ingin dicapai pada sesi ini...">{{ old('tujuan_pembelajaran', $detail->tujuan_pembelajaran) }}</textarea>
                                </div>

                                <div class="tk-form-group">
                                    <label for="penilaian_{{ $detail->id }}" class="tk-form-label">Penilaian</label>
                                    <textarea id="penilaian_{{ $detail->id }}" name="penilaian" class="tk-form-textarea" placeholder="Diisi dengan CP yang diharapkan pada materi + indikator apakah siswa memenuhi CP. Contoh: Siswa mampu menyelesaikan soal perkalian dengan benar dan menjelaskan langkah penyelesaian; indikator: siswa sudah mampu memahami konsep dan mengerjakan soal dengan tepat.">{{ old('penilaian', $detail->penilaian) }}</textarea>
                                    <small class="tk-form-hint">
                                        Diisi dengan CP yang diharapkan pada materi + indikator apakah siswa memenuhi CP. Ini menjelaskan penilaian tutor terhadap siswa dalam kelas ini.
                                    </small>
                                </div>

                                <div class="tk-form-group">
                                    <label class="tk-form-label">Kehadiran</label>
                                    <div class="tk-switch-group">
                                        <label class="tk-switch-option">
                                            <input type="radio" name="kehadiran" id="kehadiran_{{ $detail->id }}" value="hadir" {{ old('kehadiran_' . $detail->id, $detail->kehadiran) === 'hadir' ? 'checked' : '' }}>
                                            <span class="tk-switch-indicator"></span>
                                            <span class="tk-switch-text">Hadir</span>
                                        </label>
                                        <label class="tk-switch-option">
                                            <input type="radio" name="kehadiran" id="kehadiran_{{ $detail->id }}" value="alpha" {{ old('kehadiran_' . $detail->id, $detail->kehadiran) === 'alpha' ? 'checked' : '' }} >
                                            <span class="tk-switch-indicator"></span>
                                            <span class="tk-switch-text">Alpha</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="tk-submit-btn" {{ $detail->status === 'completed' ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle"></i>
                                    {{ $detail->status === 'completed' ? 'Sesi Selesai' : 'Tandai Selesai' }}
                                </button>
                            </div>
                        </div>
                    </form>
                @empty
                    <div style="text-align:center;color:#8890a8;padding:12px 0;">
                        <i class="bi bi-inbox" style="font-size:24px;opacity:.25;display:block;margin-bottom:6px;"></i>
                        <span style="font-size:13px;">Tidak ada jadwal yang dipesan.</span>
                    </div>
                @endforelse
            </div>
        </div>

        

    </div>

    {{-- ── Kanan: Ringkasan + Aksi ─────────────────────────── --}}
    <div>
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:20px 22px;">
             <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-list" style="font-size:14px;color:#1e2d6b;"></i>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Detail Pesanan</span>
                </div>
                @if($st === 'pending')
                    <span style="background:#fffbeb;color:#92400e;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Menunggu Konfirmasi</span>
                @elseif($st === 'confirmed')
                    @if($order->payments()->where('status', 'paid')->exists())
                        <span style="background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Sudah Dibayar</span>
                    @else
                        <span style="background:#eff6ff;color:#1e40af;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Menunggu Bayar</span>
                    @endif
                @elseif($st === 'complete')
                    <span style="background:#f0fdf4;color:#15803d;font-size:11px;font-weight:500;padding:3px 10px;border-radius:16px;">Selesai</span>
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
                <div style="width:48px;height:48px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;">
                    {{ strtoupper(substr($siswaName, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;color:#1a1a2e;">{{ $siswaName }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $siswaEmail }}</div>
                    <div style="font-size:12px;color:#8890a8;">{{ $siswaHp }}</div>
                </div>
            </div>

            <div style="font-weight:600;font-size:15px;color:#1a1a2e;margin-bottom:8px;">
                {{ $order?->alamat_order ?? '-' }}
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Latitude</div>
                    <div class="badge bg-dark" style="font-size:13px;font-weight:500;font-family:monospace;">{{ $order?->latitude_order ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#8890a8;margin-bottom:2px;">Langitude</div>
                    <div class="badge bg-dark" style="font-size:13px;font-weight:500;font-family:monospace;">{{ $order?->langitude_order ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .tk-session-card {
        background: #fff;
        border: 1px solid #e8eaf0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 14px;
    }

    .tk-session-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #f8f9fc;
        border-bottom: 1px solid #e8eaf0;
        padding: 14px 18px;
    }

    .tk-session-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .tk-session-header-title i {
        color: #1e2d6b;
        font-size: 14px;
    }

    .tk-session-header-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
        font-size: 12px;
        color: #4b5574;
    }

    .tk-session-body {
        padding: 18px;
    }

    .tk-form-grid {
        display: grid;
        gap: 16px;
    }

    .tk-form-group {
        display: grid;
        gap: 8px;
    }

    .tk-form-label {
        font-size: 12px;
        font-weight: 600;
        color: #303a56;
        margin: 0;
    }

    .tk-form-input,
    .tk-form-textarea {
        width: 100%;
        border: 1px solid #dfe5f1;
        background: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        color: #1a1a2e;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        font-family: inherit;
    }

    .tk-form-input:focus,
    .tk-form-textarea:focus {
        outline: none;
        border-color: #8fa6ff;
        box-shadow: 0 0 0 4px rgba(127, 156, 245, 0.12);
    }

    .tk-form-textarea {
        min-height: 110px;
        resize: vertical;
    }

    .tk-form-hint {
        font-size: 11px;
        line-height: 1.5;
        color: #75809a;
    }

    .tk-switch-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tk-switch-option {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 110px;
        padding: 9px 12px;
        border: 1px solid #dfe5f1;
        border-radius: 999px;
        background: #f8f9fc;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }

    .tk-switch-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .tk-switch-indicator {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #c2cce2;
        background: #fff;
        position: relative;
        transition: all 0.15s ease;
    }

    .tk-switch-option input:checked + .tk-switch-indicator {
        border-color: #1e2d6b;
        background: #1e2d6b;
    }

    .tk-switch-option input:checked + .tk-switch-indicator::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        background: #fff;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .tk-switch-option input:checked ~ .tk-switch-text {
        color: #1e2d6b;
        font-weight: 600;
    }

    .tk-switch-option:has(input:checked) {
        background: #eef2ff;
        border-color: #c7d4fd;
    }

    .tk-submit-btn {
        margin-top: 8px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 14px;
        border: none;
        border-radius: 10px;
        background: #1e2d6b;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .tk-submit-btn:hover {
        background: #162252;
    }

    .tk-submit-btn:active {
        transform: translateY(1px);
    }

    .tk-submit-btn:disabled {
        background: #bbb9b9;
        cursor: not-allowed;
    }

    @media (max-width: 576px) {
        .tk-session-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .tk-session-header-meta {
            justify-content: flex-start;
        }

        .tk-switch-group {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .tk-switch-option {
            width: 100%;
        }
    }
</style>
@endpush

@endsection

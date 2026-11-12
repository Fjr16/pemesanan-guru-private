@extends('layouts.app')

@section('title', 'Rekap Kelas — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:1200px;margin:0 auto;padding:0 20px;">

        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
            <div>
                <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">Rekap Kelas</h1>
                <p style="font-size:13px;color:#8890a8;margin:0;">
                    Menampilkan jadwal kelas, detail materi, dan evaluasi tutor untuk sesi yang sudah dijalani atau akan datang.
                </p>
            </div>
            <a href="{{ route('siswa.dashboard') }}"
               style="background:#1e2d6b;color:#fff;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:14px;padding:18px 20px;margin-bottom:18px;">
            <form method="GET" action="{{ route('siswa.rekap-kelas') }}" style="display:grid;grid-template-columns: repeat(5, minmax(0, 1fr));gap:12px;align-items:end;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#4b5574;margin-bottom:6px;">Status</label>
                    <select name="status" style="width:100%;border:1px solid #dfe5f1;border-radius:10px;padding:9px 12px;background:#fff;color:#1a1a2e;font-size:13px;">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="menunggu" {{ $statusFilter === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#4b5574;margin-bottom:6px;">Nama Tutor</label>
                    <input type="text" name="tutor" value="{{ old('tutor', $tutorName) }}" placeholder="Cari tutor" style="width:100%;border:1px solid #dfe5f1;border-radius:10px;padding:9px 12px;background:#fff;color:#1a1a2e;font-size:13px;">
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#4b5574;margin-bottom:6px;">Tanggal Awal</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $startDate) }}" style="width:100%;border:1px solid #dfe5f1;border-radius:10px;padding:9px 12px;background:#fff;color:#1a1a2e;font-size:13px;">
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#4b5574;margin-bottom:6px;">Tanggal Akhir</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $endDate) }}" style="width:100%;border:1px solid #dfe5f1;border-radius:10px;padding:9px 12px;background:#fff;color:#1a1a2e;font-size:13px;">
                </div>

                <div style="display:flex;gap:8px;align-items:end;">
                    <button type="submit" style="flex:1;background:#1e2d6b;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-size:13px;font-weight:600;">
                        Terapkan
                    </button>
                    <a href="{{ route('siswa.rekap-kelas') }}" style="display:inline-flex;align-items:center;justify-content:center;flex:1;border:1px solid #dfe5f1;background:#fff;color:#1a1a2e;border-radius:10px;padding:10px 14px;font-size:13px;font-weight:500;text-decoration:none;">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:14px;overflow:hidden;">
            <div style="border-bottom:1px solid #edf0f7;padding:14px 16px;background:#f9fbff;display:flex;align-items:center;justify-content:space-between;gap:12px;">
                <div style="font-size:13px;font-weight:600;color:#1a1a2e;">Daftar Kelas</div>
                <span style="font-size:11px;color:#4b5574;background:#eef2ff;border-radius:999px;padding:4px 10px;display:inline-flex;align-items:center;justify-content:center;">
                    {{ $sessions->count() }} data
                </span>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:1100px;">
                    <thead>
                        <tr style="background:#f8f9fc;">
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Tutor</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Materi</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Tujuan Pembelajaran</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Tanggal Kelas</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Waktu</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Kehadiran</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Status</th>
                            <th style="padding:12px 14px;text-align:left;font-size:12px;font-weight:700;color:#4b5574;border-bottom:1px solid #edf0f7;">Penilaian Tutor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $detail)
                            @php
                                $statusBg = $detail->status === 'completed' ? '#f0fdf4' : '#fffbeb';
                                $statusColor = $detail->status === 'completed' ? '#15803d' : '#92400e';
                                $kehadiran = $detail->kehadiran === 'hadir';
                            @endphp
                            <tr style="border-bottom:1px solid #edf0f7;vertical-align:top;">
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:36px;height:36px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($detail->display_tutor, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $detail->display_tutor }}</div>
                                            <div style="font-size:11px;color:#8890a8;">{{ $detail->order->tutor->job ?? 'Tutor Privat' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <span style="font-size:13px;color:#1a1a2e;">{{ $detail->display_materi }}</span>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <span style="font-size:13px;color:#4b5574;line-height:1.5;">{{ $detail->display_tujuan }}</span>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <div style="font-size:13px;color:#1a1a2e;font-weight:500;">
                                        {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#f3f6ff;color:#1e2d6b;font-size:12px;font-weight:600;">
                                        <i class="bi bi-clock"></i>
                                        {{ $detail->display_time }}
                                    </span>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    @if($kehadiran)
                                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#f0fdf4;color:#15803d;font-size:12px;font-weight:600;">
                                            <i class="bi bi-check-circle-fill"></i> Hadir
                                        </span>
                                    @elseif($detail->kehadiran === 'alpha')
                                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#fef2f2;color:#dc2626;font-size:12px;font-weight:600;">
                                            <i class="bi bi-x-circle-fill"></i> Tidak Hadir
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#fef3c7;color:#b45309;font-size:12px;font-weight:600;">
                                            <i class="bi bi-exclamation-circle-fill"></i> Belum Dikonfirmasi
                                        </span>
                                    @endif
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <span style="display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:600;background:{{ $statusBg }};color:{{ $statusColor }};">
                                        {{ $detail->display_status }}
                                    </span>
                                </td>
                                <td style="padding:14px;border-bottom:1px solid #edf0f7;">
                                    <div style="font-size:13px;color:#4b5574;line-height:1.6;max-width:360px;">
                                        {{ $detail->display_penilaian }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding:32px 20px;text-align:center;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#8a93aa;">
                                        <i class="bi bi-calendar-x" style="font-size:28px;opacity:.4;"></i>
                                        <div style="font-size:14px;font-weight:500;">Belum ada data kelas sesuai filter.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.app')

@section('title', 'Dashboard Siswa — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:960px;margin:0 auto;padding:0 20px;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div>
                <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">
                    Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋
                </h1>
                <p style="font-size:13px;color:#8890a8;margin:0;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ route('home') }}"
               style="background:#1e2d6b;color:#fff;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;border:none;">
                <i class="bi bi-search"></i> Cari Tutor
            </a>
        </div>

        {{-- Stat Cards --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-check-fill" style="color:#3730a3;font-size:18px;"></i>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['total_booking'] }}</div>
                    <div style="font-size:12px;color:#8890a8;margin-top:2px;">Total Booking</div>
                </div>
            </div>
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-hourglass-split" style="color:#b45309;font-size:18px;"></i>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['pending'] }}</div>
                    <div style="font-size:12px;color:#8890a8;margin-top:2px;">Menunggu</div>
                </div>
            </div>
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-check-circle-fill" style="color:#15803d;font-size:18px;"></i>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['completed'] }}</div>
                    <div style="font-size:12px;color:#8890a8;margin-top:2px;">Selesai</div>
                </div>
            </div>
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-x-circle-fill" style="color:#dc2626;font-size:18px;"></i>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:700;color:#1a1a2e;line-height:1.2;">{{ $stats['cancelled'] }}</div>
                    <div style="font-size:12px;color:#8890a8;margin-top:2px;">Dibatalkan</div>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

            {{-- Sesi Mendatang --}}
            <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-calendar-event" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Sesi Mendatang</span>
                    </div>
                    <a href="{{ route('siswa.pemesanan') }}" style="font-size:12px;color:#1e2d6b;text-decoration:none;font-weight:500;">Lihat semua →</a>
                </div>

                @forelse($upcomingSessions as $session)
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                        <div style="width:40px;height:40px;border-radius:50%;background:#eef2ff;color:#3730a3;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($session['tutor_name'], 0, 2)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:13px;color:#1a1a2e;">{{ $session['tutor_name'] }}</div>
                            <div style="font-size:12px;color:#8890a8;margin-top:2px;">
                                <i class="bi bi-book" style="margin-right:4px;"></i>{{ $session['mapel'] }}
                            </div>
                            <div style="font-size:12px;color:#8890a8;">
                                <i class="bi bi-calendar3" style="margin-right:4px;"></i>{{ $session['hari'] }}, {{ $session['jam'] }} WIB
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            @php
                                $bg = match($session['status']) {
                                    'pending' => '#fffbeb',
                                    'confirmed' => '#eff6ff',
                                    default => '#f0f2f8',
                                };
                                $clr = match($session['status']) {
                                    'pending' => '#92400e',
                                    'confirmed' => '#1e40af',
                                    default => '#4b5574',
                                };
                                $lbl = match($session['status']) {
                                    'pending' => 'Pending',
                                    'confirmed' => 'Dikonfirmasi',
                                    default => ucfirst($session['status']),
                                };
                            @endphp
                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:12px;font-size:10px;font-weight:600;background:{{ $bg }};color:{{ $clr }};">
                                {{ $lbl }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:32px 0;color:#8890a8;">
                        <i class="bi bi-calendar-x" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:13px;margin:0;">Belum ada sesi mendatang.</p>
                    </div>
                @endforelse
            </div>

            {{-- Tutor Favorit --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <i class="bi bi-heart" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Tutor Favorit</span>
                    </div>

                    @forelse($favoriteTutors as $tutor)
                        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f8;' : '' }}">
                            <div style="width:36px;height:36px;border-radius:50%;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
                                {{ strtoupper(substr($tutor['name'], 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:500;font-size:13px;color:#1a1a2e;">{{ $tutor['name'] }}</div>
                                <div style="font-size:12px;color:#8890a8;">{{ $tutor['mapel'] }} · {{ $tutor['total_sesi'] }} sesi</div>
                            </div>
                            <i class="bi bi-chevron-right" style="color:#b0b8cc;font-size:12px;"></i>
                        </div>
                    @empty
                        <div style="text-align:center;padding:24px 0;color:#8890a8;">
                            <p style="font-size:13px;margin:0;">Belum ada tutor favorit.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Tips --}}
                <div style="background:#1e2d6b;border-radius:12px;padding:20px 22px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <span style="font-size:24px;">💡</span>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#fff;margin-bottom:6px;">Tips Belajar</div>
                            <p style="font-size:13px;color:rgba(255,255,255,.6);margin:0 0 10px;line-height:1.6;">
                                Persiapkan topik atau soal yang ingin dibahas sebelum sesi dimulai agar waktu belajar lebih efektif.
                            </p>
                            <a href="{{ route('home') }}" style="color:#86efac;font-size:13px;font-weight:500;text-decoration:none;">
                                Cari tutor sekarang →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

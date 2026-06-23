@extends('layouts.app')

@section('title', 'Pembayaran Berhasil — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:48px 0;">
    <div style="max-width:520px;margin:0 auto;padding:0 20px;">

        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:40px 32px;text-align:center;">

            {{-- Icon sukses --}}
            <div style="width:72px;height:72px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="bi bi-check-lg" style="font-size:32px;color:#15803d;"></i>
            </div>

            <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 8px;">Bukti Pembayaran Terkirim!</h2>
            <p style="font-size:14px;color:#8890a8;margin:0 0 24px;line-height:1.6;">
                Bukti pembayaran Anda sedang diverifikasi admin.<br>
                Konfirmasi detail sesi akan dikirim via <strong>email</strong> dan <strong>WhatsApp</strong>.
            </p>

            {{-- Ringkasan --}}
            <div style="background:#f8f9fc;border-radius:10px;border:1px solid #e8eaf0;padding:16px;text-align:left;margin-bottom:24px;">
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Tutor</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order['tutor_name'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Mata Pelajaran</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order['mapel'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Jadwal</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order['hari'] }}, {{ $order['jam'] }} WIB</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:15px;">
                    <span style="font-weight:600;color:#1a1a2e;">Total</span>
                    <span style="font-weight:700;color:#1e2d6b;">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('siswa.pemesanan') }}"
               style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 20px;border-radius:8px;background:#1e2d6b;color:#fff;font-size:14px;font-weight:500;text-decoration:none;">
                <i class="bi bi-calendar-check"></i> Lihat Pemesanan Saya
            </a>
            <a href="{{ route('home') }}"
               style="display:block;margin-top:14px;font-size:13px;color:#1e2d6b;text-decoration:none;font-weight:500;">
                Kembali ke beranda
            </a>
        </div>

    </div>
</div>

@endsection

@extends('layouts.app')

@section('title', 'Pembayaran Berhasil — TutorKu')

@section('content')
<div class="container py-5" style="max-width:520px;">
    <div class="tk-card text-center">
        <div class="tk-card-body py-5 px-4">

            {{-- Icon sukses --}}
            <div style="width:72px;height:72px;background:var(--tk-success-bg);border-radius:50%;
                        display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <i class="bi bi-check-lg" style="font-size:2rem;color:var(--tk-success-text);"></i>
            </div>

            <h2 class="fw-600 mb-2" style="font-size:1.25rem;">Bukti Pembayaran Terkirim!</h2>
            <p class="text-muted mb-4" style="font-size:.9rem;">
                Bukti pembayaran Anda sedang diverifikasi admin.<br>
                Konfirmasi detail sesi akan dikirim via <strong>email</strong> dan <strong>WhatsApp</strong>.
            </p>

            {{-- Ringkasan --}}
            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);
                        border:1px solid var(--tk-border);padding:1rem;text-align:left;margin-bottom:1.5rem;">
                <div class="d-flex justify-content-between py-1" style="font-size:.875rem;">
                    <span class="text-muted">Tutor</span>
                    <span class="fw-500">{{ $booking->tutorProfile->user->name }}</span>
                </div>
                <div class="d-flex justify-content-between py-1" style="font-size:.875rem;">
                    <span class="text-muted">Mata Pelajaran</span>
                    <span class="fw-500">{{ $booking->mataPelajaran->nama ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1" style="font-size:.875rem;">
                    <span class="text-muted">Jadwal</span>
                    <span class="fw-500">{{ $booking->scheduled_day }}, {{ $booking->scheduled_time }} WIB</span>
                </div>
                <div class="d-flex justify-content-between py-1 mt-1"
                     style="font-size:1rem;border-top:1px solid var(--tk-border);padding-top:.5rem;">
                    <span class="fw-500">Total</span>
                    <span class="fw-600" style="color:var(--tk-primary-dark);">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <a href="{{ route('siswa.pemesanan') }}" class="tk-btn-primary d-flex justify-content-center">
                <i class="bi bi-calendar-check me-2"></i> Lihat Pemesanan Saya
            </a>
            <a href="{{ route('home') }}" class="tk-link d-block mt-3" style="font-size:.875rem;">
                Kembali ke beranda
            </a>
        </div>
    </div>
</div>
@endsection

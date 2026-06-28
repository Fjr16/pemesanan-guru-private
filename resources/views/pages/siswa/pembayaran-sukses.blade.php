@extends('layouts.app')

@section('title', 'Pembayaran — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:48px 0;">
    <div style="max-width:520px;margin:0 auto;padding:0 20px;">

        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:40px 32px;text-align:center;">

            @php
                $isPaid = $payment && $payment->status === 'paid';
                $isPending = $payment && $payment->status === 'pending';
            @endphp

            {{-- Icon --}}
            <div style="width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:{{ $isPaid ? '#f0fdf4' : '#fffbeb' }};">
                @if($isPaid)
                    <i class="bi bi-check-lg" style="font-size:32px;color:#15803d;"></i>
                @elseif($isPending)
                    <i class="bi bi-hourglass-split" style="font-size:28px;color:#b45309;"></i>
                @else
                    <i class="bi bi-clock-history" style="font-size:28px;color:#6b7280;"></i>
                @endif
            </div>

            @if($isPaid)
                <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 8px;">Pembayaran Berhasil!</h2>
                <p style="font-size:14px;color:#8890a8;margin:0 0 24px;line-height:1.6;">
                    Pembayaran Anda telah terverifikasi otomatis.<br>
                    Sesi belajar Anda siap dimulai sesuai jadwal.
                </p>
            @elseif($isPending)
                <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 8px;">Menunggu Pembayaran</h2>
                <p style="font-size:14px;color:#8890a8;margin:0 0 24px;line-height:1.6;">
                    Pembayaran Anda sedang diproses. Jika sudah melakukan pembayaran,<br>
                    status akan terupdate otomatis dalam beberapa saat.
                </p>
            @else
                <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0 0 8px;">Status Pembayaran</h2>
                <p style="font-size:14px;color:#8890a8;margin:0 0 24px;line-height:1.6;">
                    Silakan cek kembali status pemesanan Anda.
                </p>
            @endif

            {{-- Ringkasan --}}
            <div style="background:#f8f9fc;border-radius:10px;border:1px solid #e8eaf0;padding:16px;text-align:left;margin-bottom:24px;">
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Tutor</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order->tutor->name ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Tanggal</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order->tanggal ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Jam</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $order->jam_range ?? '-' }} WIB</span>
                </div>
                @if($payment)
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Metode</span>
                    <span style="font-weight:500;color:#1a1a2e;">{{ $payment->metode ? ucfirst($payment->metode) : '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #e8eaf0;">
                    <span style="color:#8890a8;">Status</span>
                    @php
                        $payStatusBg = match($payment->status) {
                            'paid' => '#f0fdf4',
                            'pending' => '#fffbeb',
                            default => '#fef2f2',
                        };
                        $payStatusClr = match($payment->status) {
                            'paid' => '#15803d',
                            'pending' => '#b45309',
                            default => '#dc2626',
                        };
                        $payStatusLbl = match($payment->status) {
                            'paid' => 'Berhasil',
                            'pending' => 'Menunggu',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst($payment->status),
                        };
                    @endphp
                    <span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:10px;font-size:12px;font-weight:600;background:{{ $payStatusBg }};color:{{ $payStatusClr }};">{{ $payStatusLbl }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:15px;">
                    <span style="font-weight:600;color:#1a1a2e;">Total</span>
                    <span style="font-weight:700;color:#1e2d6b;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($isPending)
                <a href="{{ route('siswa.pembayaran.show', $order->id) }}"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 20px;border-radius:8px;background:#1e2d6b;color:#fff;font-size:14px;font-weight:500;text-decoration:none;margin-bottom:10px;">
                    <i class="bi bi-wallet2"></i> Lanjutkan Pembayaran
                </a>
            @endif

            <a href="{{ route('siswa.pemesanan') }}"
               style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 20px;border-radius:8px;background:{{ $isPaid ? '#1e2d6b' : '#fff' }};color:{{ $isPaid ? '#fff' : '#1e2d6b' }};font-size:14px;font-weight:500;text-decoration:none;{{ $isPaid ? '' : 'border:1px solid #d0d5e8;' }}">
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

@push('scripts')
@if($isPending)
<script>
$(document).ready(function () {
    const ORDER_ID = {{ $order->id }};
    let pollInterval = setInterval(function () {
        $.get('{{ route("siswa.pemesanan.show", $order->id) }}', function (html) {
            if (html.includes('Selesai') || html.includes('complete')) {
                clearInterval(pollInterval);
                window.location.reload();
            }
        }).fail(function () {});
    }, 10000);

    setTimeout(function () { clearInterval(pollInterval); }, 600000);
});
</script>
@endif
@endpush

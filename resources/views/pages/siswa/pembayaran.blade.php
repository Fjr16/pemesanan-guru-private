@extends('layouts.app')

@section('title', 'Pembayaran #' . $order->id . ' — TutorKu')

@section('content')

<div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
    <div style="max-width:860px;margin:0 auto;padding:0 20px;">

        {{-- Breadcrumb --}}
        <nav style="margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;">
                <a href="{{ route('siswa.dashboard') }}" style="color:#1e2d6b;text-decoration:none;">Dashboard</a>
                <span style="color:#b0b8cc;">/</span>
                <a href="{{ route('siswa.pemesanan') }}" style="color:#1e2d6b;text-decoration:none;">Pemesanan</a>
                <span style="color:#b0b8cc;">/</span>
                <span style="color:#8890a8;">Pembayaran #{{ $order->id }}</span>
            </div>
        </nav>

        {{-- Header --}}
        <div style="margin-bottom:24px;">
            <h1 style="font-size:22px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">Konfirmasi Pembayaran</h1>
            <p style="font-size:13px;color:#8890a8;margin:0;">
                Booking <strong>#BK-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong> &middot; {{ $order->tutor->name ?? '-' }}
            </p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 340px;gap:14px;align-items:start;">

            {{-- LEFT: Ringkasan & Bayar --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Ringkasan Pesanan --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-receipt" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Ringkasan Pesanan</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;margin-bottom:14px;">
                        <div style="width:44px;height:44px;border-radius:50%;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($order->tutor->name ?? 'TK', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:600;color:#1a1a2e;">{{ $order->tutor->name ?? '-' }}</div>
                            <div style="font-size:13px;color:#8890a8;">{{ $order->tutor->tutorSubjects->pluck('subjectCategory.name')->implode(', ') ?: '-' }}</div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Tanggal</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->tanggal ?? '-' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Jam</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->jam_range ?? '-' }} WIB</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Durasi</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order->jumlah_jam }} jam</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px 0 0;font-size:16px;margin-top:4px;">
                        <span style="font-weight:600;color:#1a1a2e;">Total Pembayaran</span>
                        <span style="font-weight:700;color:#1e2d6b;font-size:18px;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Info Metode Pembayaran --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-credit-card" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Metode Pembayaran</span>
                    </div>
                    <p style="font-size:13px;color:#8890a8;margin:0 0 12px;line-height:1.6;">
                        Setelah klik <strong>"Bayar Sekarang"</strong>, popup Midtrans akan muncul. Anda dapat memilih:
                    </p>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#1a1a2e;">Transfer Bank (Virtual Account)</div>
                                <div style="font-size:12px;color:#8890a8;">BCA, BNI, BRI — Virtual Account otomatis</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-qr-code"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#1a1a2e;">QRIS</div>
                                <div style="font-size:12px;color:#8890a8;">Scan QR dari aplikasi bank atau e-wallet apapun</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Bayar --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <button type="button" id="btnBayar"
                        style="width:100%;background:#1e2d6b;color:#fff;border-radius:10px;padding:14px 20px;font-size:15px;font-weight:600;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:background .2s;">
                        <i class="bi bi-wallet2" style="font-size:18px;"></i> Bayar Sekarang
                    </button>
                    <p style="font-size:12px;color:#b0b8cc;margin:10px 0 0;text-align:center;">
                        Pembayaran diproses secara aman melalui Midtrans
                    </p>
                </div>
            </div>

            {{-- RIGHT: Status & Timeline --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Status Booking --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:12px;background:#eff6ff;color:#1e40af;font-size:12px;font-weight:600;margin-bottom:12px;">
                        <i class="bi bi-patch-check-fill"></i> Dikonfirmasi Tutor
                    </div>
                    <p style="font-size:13px;color:#8890a8;margin:0;">
                        Tutor telah mengkonfirmasi jadwal ini. Selesaikan pembayaran untuk mengunci sesi Anda.
                    </p>
                </div>

                {{-- Info Payment --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-info-circle" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Detail Transaksi</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Order ID</span>
                        <span style="font-weight:500;color:#1a1a2e;font-family:monospace;font-size:11px;">{{ $payment->transactionId }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Status</span>
                        <span style="font-weight:600;color:#b45309;">Menunggu Pembayaran</span>
                    </div>
                    @if($payment->expired_at)
                    <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px;">
                        <span style="color:#8890a8;">Batas Waktu</span>
                        <span style="font-weight:500;color:#92400e;">{{ $payment->expired_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>
                    @endif
                </div>

                {{-- Timeline --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-list-check" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Alur Pembayaran</span>
                    </div>
                    @php
                        $steps = [
                            ['icon'=>'bi-check-circle-fill', 'color'=>'#1e2d6b', 'label'=>'Booking dibuat', 'done'=>true],
                            ['icon'=>'bi-check-circle-fill', 'color'=>'#15803d', 'label'=>'Dikonfirmasi tutor', 'done'=>true],
                            ['icon'=>'bi-circle-half', 'color'=>'#b45309', 'label'=>'Menunggu pembayaran', 'done'=>false, 'active'=>true],
                            ['icon'=>'bi-circle', 'color'=>'#d0d5e8', 'label'=>'Pembayaran terverifikasi', 'done'=>false],
                            ['icon'=>'bi-circle', 'color'=>'#d0d5e8', 'label'=>'Sesi siap dimulai', 'done'=>false],
                        ];
                    @endphp
                    @foreach($steps as $step)
                        <div style="display:flex;align-items:center;gap:10px;padding:7px 0;">
                            <i class="bi {{ $step['icon'] }}" style="font-size:16px;color:{{ $step['color'] }};"></i>
                            <span style="font-size:13px;color:{{ $step['done'] || !empty($step['active']) ? '#1a1a2e' : '#b0b8cc' }};font-weight:{{ !empty($step['active']) ? '600' : '400' }};">
                                {{ $step['label'] }}
                                @if(!empty($step['active']))
                                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#f59e0b;margin-left:6px;animation:pulse 1.5s infinite;"></span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .3; }
}
</style>

@endsection

@push('scripts')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
$(document).ready(function () {

    const ORDER_ID = {{ $order->id }};

    $('#btnBayar').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true)
            .css('opacity', '0.7')
            .html('<span style="display:inline-block;width:16px;height:16px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;"></span> Memproses...');

        $.ajax({
            url: '{{ route("siswa.pembayaran.process", $order->id) }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                window.snap.pay(res.snap_token, {
                    onSuccess: function () {
                        window.location.href = '{{ route("siswa.pembayaran.sukses", $order->id) }}';
                    },
                    onPending: function () {
                        window.location.href = '{{ route("siswa.pembayaran.sukses", $order->id) }}';
                    },
                    onError: function () {
                        Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.' });
                        resetBtn($btn);
                    },
                    onClose: function () {
                        Swal.fire({ icon: 'info', title: 'Pembayaran Dibatalkan', text: 'Anda menutup halaman pembayaran. Anda dapat mencoba kembali kapan saja.' });
                        resetBtn($btn);
                    }
                });
            },
            error: function (xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan. Silakan coba lagi.' });
                resetBtn($btn);
            }
        });
    });

    function resetBtn($btn) {
        $btn.prop('disabled', false)
            .css('opacity', '1')
            .html('<i class="bi bi-wallet2" style="font-size:18px;"></i> Bayar Sekarang');
    }

});
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

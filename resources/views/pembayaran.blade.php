@extends('layouts.app')

@section('title', 'Pembayaran #' . $booking->id . ' — TutorKu')

@section('content')

<div class="container py-5" style="max-width:860px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="tk-link">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siswa.pemesanan') }}" class="tk-link">Pemesanan</a></li>
            <li class="breadcrumb-item active">Pembayaran #{{ $booking->id }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-4">
        <h1 style="font-size:1.375rem;font-weight:600;margin-bottom:4px;">Konfirmasi Pembayaran</h1>
        <p class="text-muted mb-0" style="font-size:.875rem;">
            Booking <strong>#BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong> ·
            {{ $booking->tutorProfile->user->name }}
        </p>
    </div>

    <div class="row g-4">

        {{-- LEFT: Metode pembayaran --}}
        <div class="col-lg-7">

            {{-- Ringkasan pesanan --}}
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-receipt me-2 text-primary"></i>Ringkasan Pesanan
                    </h2>
                </div>
                <div class="tk-card-body">
                    <div class="d-flex align-items-center gap-3 p-3 rounded mb-3"
                         style="background:var(--tk-surface);border:1px solid var(--tk-border-light);">
                        <div class="tk-tutor-avatar" style="width:44px;height:44px;">
                            {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'TK', 0, 2)) }}
                        </div>
                        <div>
                            <div class="fw-500" style="font-size:.9375rem;">
                                {{ $booking->tutorProfile->user->name }}
                            </div>
                            <div class="text-muted" style="font-size:.8125rem;">
                                {{ $booking->mataPelajaran->nama ?? '-' }}
                            </div>
                        </div>
                    </div>

                    @php
                        $rows = [
                            ['label' => 'Hari & Waktu', 'value' => $booking->scheduled_day . ', ' . $booking->scheduled_time . ' WIB'],
                            ['label' => 'Durasi',       'value' => $booking->duration . ' jam'],
                            ['label' => 'Tarif/jam',    'value' => 'Rp ' . number_format($booking->tutorProfile->hourly_rate, 0, ',', '.')],
                        ];
                    @endphp

                    @foreach($rows as $row)
                        <div class="d-flex justify-content-between py-2"
                             style="border-top:1px solid var(--tk-border-light);font-size:.875rem;">
                            <span class="text-muted">{{ $row['label'] }}</span>
                            <span class="fw-500">{{ $row['value'] }}</span>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between py-2 mt-1"
                         style="border-top:2px solid var(--tk-border);font-size:1rem;">
                        <span class="fw-600">Total Pembayaran</span>
                        <span class="fw-600" style="color:var(--tk-primary-dark);font-size:1.125rem;">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Pilih metode pembayaran --}}
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-credit-card me-2 text-primary"></i>Metode Pembayaran
                    </h2>
                </div>
                <div class="tk-card-body">
                    <div class="d-flex flex-column gap-2" id="paymentMethods">

                        {{-- Transfer Bank --}}
                        <label class="pay-method-option active" for="pm_transfer">
                            <input type="radio" name="payment_method" id="pm_transfer"
                                   value="transfer" class="d-none" checked>
                            <div class="d-flex align-items-center gap-3">
                                <div class="pay-method-icon">
                                    <i class="bi bi-bank2"></i>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.9rem;">Transfer Bank</div>
                                    <div class="text-muted" style="font-size:.75rem;">BCA, BNI, BRI, Mandiri</div>
                                </div>
                            </div>
                        </label>

                        {{-- E-Wallet --}}
                        <label class="pay-method-option" for="pm_ewallet">
                            <input type="radio" name="payment_method" id="pm_ewallet"
                                   value="ewallet" class="d-none">
                            <div class="d-flex align-items-center gap-3">
                                <div class="pay-method-icon">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.9rem;">E-Wallet</div>
                                    <div class="text-muted" style="font-size:.75rem;">GoPay, OVO, Dana, ShopeePay</div>
                                </div>
                            </div>
                        </label>

                        {{-- QRIS --}}
                        <label class="pay-method-option" for="pm_qris">
                            <input type="radio" name="payment_method" id="pm_qris"
                                   value="qris" class="d-none">
                            <div class="d-flex align-items-center gap-3">
                                <div class="pay-method-icon">
                                    <i class="bi bi-qr-code"></i>
                                </div>
                                <div>
                                    <div class="fw-500" style="font-size:.9rem;">QRIS</div>
                                    <div class="text-muted" style="font-size:.75rem;">Scan QR dari aplikasi apapun</div>
                                </div>
                            </div>
                        </label>

                    </div>

                    {{-- Detail metode (dinamis) --}}
                    <div id="methodDetail" class="mt-3">

                        {{-- Transfer detail --}}
                        <div id="detail_transfer"
                             style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);
                                    border:1px solid var(--tk-border);padding:1rem;">
                            <div class="text-muted mb-2" style="font-size:.8rem;font-weight:500;">
                                NOMOR REKENING TUJUAN
                            </div>
                            @foreach([
                                ['bank'=>'BCA',     'no'=>'1234 5678 9012', 'an'=>'TutorKu Indonesia'],
                                ['bank'=>'BNI',     'no'=>'9876 5432 1098', 'an'=>'TutorKu Indonesia'],
                                ['bank'=>'BRI',     'no'=>'0011 2233 4455', 'an'=>'TutorKu Indonesia'],
                            ] as $rek)
                                <div class="d-flex align-items-center justify-content-between py-2
                                     {{ !$loop->last ? 'border-bottom' : '' }}"
                                     style="border-color:var(--tk-border-light)!important;">
                                    <div>
                                        <div class="fw-500" style="font-size:.875rem;">
                                            {{ $rek['bank'] }} — {{ $rek['no'] }}
                                        </div>
                                        <div class="text-muted" style="font-size:.75rem;">a/n {{ $rek['an'] }}</div>
                                    </div>
                                    <button type="button"
                                            class="btn-copy-rek"
                                            data-text="{{ str_replace(' ', '', $rek['no']) }}"
                                            style="border:none;background:transparent;
                                                   color:var(--tk-primary);font-size:.75rem;font-weight:500;cursor:pointer;">
                                        <i class="bi bi-copy me-1"></i>Salin
                                    </button>
                                </div>
                            @endforeach
                            <div class="mt-2 d-flex align-items-center gap-2"
                                 style="background:var(--tk-warning-bg);border:1px solid var(--tk-warning-border);
                                        border-radius:var(--tk-radius);padding:.5rem .75rem;">
                                <i class="bi bi-exclamation-triangle-fill"
                                   style="color:var(--tk-warning-text);font-size:.875rem;"></i>
                                <span style="font-size:.8rem;color:var(--tk-warning-text);">
                                    Transfer tepat sejumlah
                                    <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                                    untuk verifikasi otomatis.
                                </span>
                            </div>
                        </div>

                        {{-- E-Wallet detail (hidden default) --}}
                        <div id="detail_ewallet" style="display:none;background:var(--tk-surface);
                             border-radius:var(--tk-radius-lg);border:1px solid var(--tk-border);padding:1rem;">
                            <div class="text-muted mb-2" style="font-size:.8rem;font-weight:500;">
                                NOMOR TUJUAN E-WALLET
                            </div>
                            <div class="fw-500 mb-1" style="font-size:1rem;">0812 3456 7890</div>
                            <div class="text-muted" style="font-size:.75rem;">a/n TutorKu Indonesia (GoPay/OVO/Dana)</div>
                        </div>

                        {{-- QRIS detail (hidden default) --}}
                        <div id="detail_qris" style="display:none;text-align:center;
                             background:var(--tk-surface);border-radius:var(--tk-radius-lg);
                             border:1px solid var(--tk-border);padding:1.5rem;">
                            <div style="width:120px;height:120px;background:var(--tk-border);
                                        border-radius:var(--tk-radius);margin:0 auto 1rem;
                                        display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-qr-code" style="font-size:3rem;color:var(--tk-text-light);"></i>
                            </div>
                            <div class="text-muted" style="font-size:.8rem;">
                                Scan QR ini menggunakan aplikasi e-wallet Anda
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upload bukti transfer --}}
            <div class="tk-card">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-upload me-2 text-primary"></i>Upload Bukti Pembayaran
                    </h2>
                </div>
                <div class="tk-card-body">
                    <p class="text-muted small mb-3">
                        Upload screenshot/foto bukti transfer setelah melakukan pembayaran.
                        Admin akan memverifikasi dalam 1×24 jam.
                    </p>

                    <div id="uploadDropzone"
                         style="border:2px dashed var(--tk-border);border-radius:var(--tk-radius-lg);
                                padding:2rem;text-align:center;cursor:pointer;transition:all .2s;">
                        <i class="bi bi-cloud-upload text-muted" style="font-size:2rem;"></i>
                        <p class="text-muted small mt-2 mb-1">Drag & drop atau klik untuk memilih file</p>
                        <p class="text-muted" style="font-size:.75rem;">PNG, JPG, PDF — maks 5MB</p>
                        <input type="file" id="buktiBayar" accept="image/*,.pdf" class="d-none">
                    </div>

                    <div id="filePreview" class="mt-3" style="display:none;">
                        <div class="d-flex align-items-center gap-3 p-2 rounded"
                             style="background:var(--tk-success-bg);border:1px solid var(--tk-success-border);">
                            <i class="bi bi-file-earmark-check"
                               style="font-size:1.25rem;color:var(--tk-success-text);"></i>
                            <div class="flex-1 min-w-0">
                                <div id="fileName" class="fw-500 text-truncate"
                                     style="font-size:.875rem;color:var(--tk-success-text);"></div>
                                <div id="fileSize" class="text-muted" style="font-size:.75rem;"></div>
                            </div>
                            <button type="button" id="removeFile" class="btn-close btn-close-sm"></button>
                        </div>
                    </div>

                    <button type="button" id="submitPayment"
                            class="tk-btn-primary mt-4">
                        <i class="bi bi-send-check"></i> Konfirmasi Pembayaran
                    </button>
                </div>
            </div>

        </div>

        {{-- RIGHT: Info & status --}}
        <div class="col-lg-5">

            {{-- Status booking --}}
            <div class="tk-card mb-4">
                <div class="tk-card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="tk-badge tk-badge-confirmed">
                            <i class="bi bi-patch-check-fill"></i> Dikonfirmasi Tutor
                        </span>
                    </div>
                    <p class="text-muted small mb-0">
                        Tutor telah mengkonfirmasi jadwal ini. Selesaikan pembayaran untuk mengunci sesi Anda.
                    </p>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title" style="font-size:.9rem;">
                        <i class="bi bi-list-check me-2 text-primary"></i>Alur Pembayaran
                    </h2>
                </div>
                <div class="tk-card-body">
                    @php
                        $steps = [
                            ['icon'=>'bi-check-circle-fill', 'color'=>'var(--tk-primary)',      'label'=>'Booking dibuat',          'done'=>true],
                            ['icon'=>'bi-check-circle-fill', 'color'=>'var(--tk-success-text)', 'label'=>'Dikonfirmasi tutor',      'done'=>true],
                            ['icon'=>'bi-circle-half',       'color'=>'var(--tk-warning-text)', 'label'=>'Menunggu pembayaran',     'done'=>false],
                            ['icon'=>'bi-circle',            'color'=>'var(--tk-text-light)',   'label'=>'Pembayaran terverifikasi','done'=>false],
                            ['icon'=>'bi-circle',            'color'=>'var(--tk-text-light)',   'label'=>'Sesi siap dimulai',       'done'=>false],
                        ];
                    @endphp
                    @foreach($steps as $i => $step)
                        <div class="d-flex align-items-center gap-3
                             {{ !$loop->last ? 'mb-2' : '' }}">
                            <i class="bi {{ $step['icon'] }}" style="color:{{ $step['color'] }};font-size:1rem;"></i>
                            <span style="font-size:.8125rem;
                                         color:{{ $step['done'] ? 'var(--tk-text)' : 'var(--tk-text-light)' }};
                                         font-weight:{{ $step['done'] ? '500' : '400' }};">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Notifikasi WA --}}
            <div class="tk-card"
                 style="background:var(--tk-success-bg);border-color:var(--tk-success-border);">
                <div class="tk-card-body">
                    <div class="d-flex gap-3">
                        <i class="bi bi-whatsapp"
                           style="font-size:1.5rem;color:var(--tk-success-text);flex-shrink:0;"></i>
                        <div>
                            <div class="fw-500 mb-1" style="font-size:.875rem;color:var(--tk-success-text);">
                                Konfirmasi via WhatsApp
                            </div>
                            <p style="font-size:.8rem;color:var(--tk-success-text);opacity:.8;margin:0;">
                                Setelah pembayaran terverifikasi, detail sesi akan dikirim ke WhatsApp Anda
                                <strong>{{ Auth::user()->phone }}</strong> secara otomatis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('styles')
<style>
.pay-method-option {
    display: flex;
    align-items: center;
    padding: .875rem 1rem;
    border: 1px solid var(--tk-border);
    border-radius: var(--tk-radius-lg);
    cursor: pointer;
    transition: all .15s;
    background: #fff;
}
.pay-method-option:hover { border-color: var(--tk-primary-100); background: var(--tk-primary-50); }
.pay-method-option.active { border-color: var(--tk-primary); background: var(--tk-primary-50); }

.pay-method-icon {
    width: 40px;
    height: 40px;
    background: var(--tk-primary-50);
    border-radius: var(--tk-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    color: var(--tk-primary);
    flex-shrink: 0;
}
.pay-method-option.active .pay-method-icon {
    background: var(--tk-primary);
    color: #fff;
}

#uploadDropzone:hover {
    border-color: var(--tk-primary-light);
    background: var(--tk-primary-50);
}
#uploadDropzone.dragover {
    border-color: var(--tk-primary);
    background: var(--tk-primary-50);
}
</style>
@endpush


@push('scripts')
<script>
$(document).ready(function () {

    const BOOKING_ID = {{ $booking->id }};
    let selectedFile  = null;
    let selectedMethod = 'transfer';

    // ── Pilih metode pembayaran ──────────────────────────────
    $('input[name="payment_method"]').on('change', function () {
        selectedMethod = $(this).val();

        // Toggle active class
        $('.pay-method-option').removeClass('active');
        $(this).closest('.pay-method-option').addClass('active');

        // Tampilkan detail yang sesuai
        $('#detail_transfer, #detail_ewallet, #detail_qris').hide();
        $(`#detail_${selectedMethod}`).show();
    });

    // ── Salin nomor rekening ─────────────────────────────────
    $(document).on('click', '.btn-copy-rek', function () {
        const text = $(this).data('text');
        navigator.clipboard.writeText(text).then(() => {
            showToast('Nomor rekening disalin!', 'success');
        });
    });

    // ── Upload dropzone ──────────────────────────────────────
    $('#uploadDropzone').on('click', function () { $('#buktiBayar').trigger('click'); });

    $('#uploadDropzone').on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    }).on('dragleave', function () {
        $(this).removeClass('dragover');
    }).on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    $('#buktiBayar').on('change', function () {
        if (this.files[0]) handleFile(this.files[0]);
    });

    function handleFile(file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            showToast('Ukuran file maksimal 5MB.', 'error');
            return;
        }
        selectedFile = file;
        $('#fileName').text(file.name);
        $('#fileSize').text((file.size / 1024).toFixed(1) + ' KB');
        $('#filePreview').show();
        $('#uploadDropzone').hide();
    }

    $('#removeFile').on('click', function () {
        selectedFile = null;
        $('#buktiBayar').val('');
        $('#filePreview').hide();
        $('#uploadDropzone').show();
    });

    // ── Submit pembayaran ────────────────────────────────────
    $('#submitPayment').on('click', function () {
        if (!selectedFile) {
            showToast('Upload bukti pembayaran terlebih dahulu.', 'warning');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Memproses...');

        const formData = new FormData();
        formData.append('_token',           $('meta[name="csrf-token"]').attr('content'));
        formData.append('payment_method',   selectedMethod);
        formData.append('bukti_bayar',      selectedFile);

        $.ajax({
            url: `/siswa/pemesanan/${BOOKING_ID}/bayar`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                showToast('Bukti pembayaran berhasil dikirim!', 'success');
                setTimeout(() => {
                    window.location.href = res.redirect || `/siswa/pemesanan/${BOOKING_ID}/sukses`;
                }, 1200);
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal mengirim bukti pembayaran.', 'error');
                $btn.prop('disabled', false)
                    .html('<i class="bi bi-send-check"></i> Konfirmasi Pembayaran');
            }
        });
    });

});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Pembayaran #' . $order['id'] . ' — TutorKu')

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
                <span style="color:#8890a8;">Pembayaran #{{ $order['id'] }}</span>
            </div>
        </nav>

        {{-- Header --}}
        <div style="margin-bottom:24px;">
            <h1 style="font-size:22px;font-weight:600;color:#1a1a2e;margin:0 0 4px;">Konfirmasi Pembayaran</h1>
            <p style="font-size:13px;color:#8890a8;margin:0;">
                Booking <strong>#BK-{{ str_pad($order['id'], 6, '0', STR_PAD_LEFT) }}</strong> · {{ $order['tutor_name'] }}
            </p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 340px;gap:14px;align-items:start;">

            {{-- LEFT: Metode & Upload --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Ringkasan Pesanan --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-receipt" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Ringkasan Pesanan</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;background:#f8f9fc;border:1px solid #e8eaf0;margin-bottom:14px;">
                        <div style="width:44px;height:44px;border-radius:50%;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($order['tutor_name'], 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:600;color:#1a1a2e;">{{ $order['tutor_name'] }}</div>
                            <div style="font-size:13px;color:#8890a8;">{{ $order['mapel'] }}</div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Hari & Waktu</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['hari'] }}, {{ $order['jam'] }} WIB</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Durasi</span>
                        <span style="font-weight:500;color:#1a1a2e;">{{ $order['durasi'] }} jam</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f2f8;">
                        <span style="color:#8890a8;">Tarif/jam</span>
                        <span style="font-weight:500;color:#1a1a2e;">Rp {{ number_format($order['tarif_per_jam'], 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px 0 0;font-size:16px;margin-top:4px;">
                        <span style="font-weight:600;color:#1a1a2e;">Total Pembayaran</span>
                        <span style="font-weight:700;color:#1e2d6b;font-size:18px;">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Pilih Metode Pembayaran --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-credit-card" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Metode Pembayaran</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:8px;" id="paymentMethods">
                        {{-- Transfer Bank --}}
                        <label id="pm_label_transfer" style="display:flex;align-items:center;gap:12px;padding:14px;border:2px solid #1e2d6b;border-radius:10px;cursor:pointer;background:#eef2ff;transition:all .15s;">
                            <input type="radio" name="payment_method" value="transfer" checked style="display:none;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#1e2d6b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#1a1a2e;">Transfer Bank</div>
                                <div style="font-size:12px;color:#8890a8;">BCA, BNI, BRI, Mandiri</div>
                            </div>
                        </label>

                        {{-- E-Wallet --}}
                        <label id="pm_label_ewallet" style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid #e8eaf0;border-radius:10px;cursor:pointer;background:#fff;transition:all .15s;">
                            <input type="radio" name="payment_method" value="ewallet" style="display:none;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#eef2ff;color:#1e2d6b;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#1a1a2e;">E-Wallet</div>
                                <div style="font-size:12px;color:#8890a8;">GoPay, OVO, Dana, ShopeePay</div>
                            </div>
                        </label>

                        {{-- QRIS --}}
                        <label id="pm_label_qris" style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid #e8eaf0;border-radius:10px;cursor:pointer;background:#fff;transition:all .15s;">
                            <input type="radio" name="payment_method" value="qris" style="display:none;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#eef2ff;color:#1e2d6b;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                <i class="bi bi-qr-code"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#1a1a2e;">QRIS</div>
                                <div style="font-size:12px;color:#8890a8;">Scan QR dari aplikasi apapun</div>
                            </div>
                        </label>
                    </div>

                    {{-- Detail Metode --}}
                    <div style="margin-top:14px;">

                        {{-- Transfer Detail --}}
                        <div id="detail_transfer" style="background:#f8f9fc;border-radius:10px;border:1px solid #e8eaf0;padding:14px;">
                            <div style="font-size:11px;color:#8890a8;font-weight:600;margin-bottom:10px;">NOMOR REKENING TUJUAN</div>
                            @foreach([
                                ['bank'=>'BCA', 'no'=>'1234 5678 9012', 'an'=>'TutorKu Indonesia'],
                                ['bank'=>'BNI', 'no'=>'9876 5432 1098', 'an'=>'TutorKu Indonesia'],
                                ['bank'=>'BRI', 'no'=>'0011 2233 4455', 'an'=>'TutorKu Indonesia'],
                            ] as $rek)
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #e8eaf0;' : '' }}">
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $rek['bank'] }} — {{ $rek['no'] }}</div>
                                        <div style="font-size:12px;color:#8890a8;">a/n {{ $rek['an'] }}</div>
                                    </div>
                                    <button type="button" class="btn-copy-rek" data-text="{{ str_replace(' ', '', $rek['no']) }}"
                                        style="border:none;background:transparent;color:#1e2d6b;font-size:12px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                        <i class="bi bi-copy"></i> Salin
                                    </button>
                                </div>
                            @endforeach
                            <div style="margin-top:10px;display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;background:#fffbeb;border:1px solid #fde68a;">
                                <i class="bi bi-exclamation-triangle-fill" style="color:#b45309;font-size:14px;flex-shrink:0;"></i>
                                <span style="font-size:12px;color:#92400e;">
                                    Transfer tepat sejumlah <strong>Rp {{ number_format($order['total'], 0, ',', '.') }}</strong> untuk verifikasi otomatis.
                                </span>
                            </div>
                        </div>

                        {{-- E-Wallet Detail --}}
                        <div id="detail_ewallet" style="display:none;background:#f8f9fc;border-radius:10px;border:1px solid #e8eaf0;padding:14px;">
                            <div style="font-size:11px;color:#8890a8;font-weight:600;margin-bottom:10px;">NOMOR TUJUAN E-WALLET</div>
                            <div style="font-size:16px;font-weight:600;color:#1a1a2e;margin-bottom:4px;">0812 3456 7890</div>
                            <div style="font-size:12px;color:#8890a8;">a/n TutorKu Indonesia (GoPay/OVO/Dana)</div>
                        </div>

                        {{-- QRIS Detail --}}
                        <div id="detail_qris" style="display:none;text-align:center;background:#f8f9fc;border-radius:10px;border:1px solid #e8eaf0;padding:24px;">
                            <div style="width:120px;height:120px;background:#e8eaf0;border-radius:10px;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-qr-code" style="font-size:48px;color:#b0b8cc;"></i>
                            </div>
                            <div style="font-size:13px;color:#8890a8;">Scan QR ini menggunakan aplikasi e-wallet Anda</div>
                        </div>
                    </div>
                </div>

                {{-- Upload Bukti Bayar --}}
                <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:14px;">
                        <i class="bi bi-upload" style="font-size:15px;color:#1e2d6b;"></i>
                        <span style="font-size:14px;font-weight:600;color:#1a1a2e;">Upload Bukti Pembayaran</span>
                    </div>
                    <p style="font-size:13px;color:#8890a8;margin:0 0 14px;">
                        Upload screenshot/foto bukti transfer setelah melakukan pembayaran. Admin akan memverifikasi dalam 1×24 jam.
                    </p>

                    <div id="uploadDropzone"
                         style="border:2px dashed #d0d5e8;border-radius:10px;padding:32px;text-align:center;cursor:pointer;transition:all .2s;">
                        <i class="bi bi-cloud-upload" style="font-size:32px;color:#8890a8;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:13px;color:#8890a8;margin:0 0 4px;">Drag & drop atau klik untuk memilih file</p>
                        <p style="font-size:11px;color:#b0b8cc;margin:0;">PNG, JPG, PDF — maks 5MB</p>
                        <input type="file" id="buktiBayar" accept="image/*,.pdf" style="display:none;">
                    </div>

                    <div id="filePreview" style="display:none;margin-top:14px;">
                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0;">
                            <i class="bi bi-file-earmark-check" style="font-size:18px;color:#15803d;"></i>
                            <div style="flex:1;min-width:0;">
                                <div id="fileName" style="font-size:13px;font-weight:500;color:#15803d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                                <div id="fileSize" style="font-size:11px;color:#8890a8;"></div>
                            </div>
                            <button type="button" id="removeFile" style="border:none;background:transparent;font-size:18px;color:#8890a8;cursor:pointer;">&times;</button>
                        </div>
                    </div>

                    <button type="button" id="submitPayment"
                        style="margin-top:18px;background:#1e2d6b;color:#fff;border-radius:8px;padding:10px 20px;font-size:13px;font-weight:500;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                        <i class="bi bi-send-check"></i> Konfirmasi Pembayaran
                    </button>
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

                {{-- WhatsApp --}}
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;gap:12px;">
                        <i class="bi bi-whatsapp" style="font-size:22px;color:#15803d;flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#15803d;margin-bottom:4px;">Konfirmasi via WhatsApp</div>
                            <p style="font-size:12px;color:#15803d;opacity:.7;margin:0;line-height:1.6;">
                                Setelah pembayaran terverifikasi, detail sesi akan dikirim ke WhatsApp Anda secara otomatis.
                            </p>
                        </div>
                    </div>
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
<script>
$(document).ready(function () {

    const BOOKING_ID = {{ $order['id'] }};
    let selectedFile  = null;
    let selectedMethod = 'transfer';

    // ── Pilih metode pembayaran ──────────────────────────────
    $('input[name="payment_method"]').on('change', function () {
        selectedMethod = $(this).val();

        // Reset all labels
        $('#pm_label_transfer, #pm_label_ewallet, #pm_label_qris').css({
            'border-color': '#e8eaf0',
            'background': '#fff'
        }).find('div:first-child').css({ 'background': '#eef2ff', 'color': '#1e2d6b' });

        // Active label
        const $active = $(this).closest('label');
        $active.css({ 'border-color': '#1e2d6b', 'background': '#eef2ff' })
               .find('div:first-child').css({ 'background': '#1e2d6b', 'color': '#fff' });

        // Show/hide detail
        $('#detail_transfer, #detail_ewallet, #detail_qris').hide();
        $(`#detail_${selectedMethod}`).show();
    });

    // ── Salin nomor rekening ─────────────────────────────────
    $(document).on('click', '.btn-copy-rek', function () {
        const text = $(this).data('text');
        navigator.clipboard.writeText(text).then(() => {
            const $btn = $(this);
            $btn.html('<i class="bi bi-check"></i> Disalin!');
            setTimeout(() => $btn.html('<i class="bi bi-copy"></i> Salin'), 2000);
        });
    });

    // ── Upload dropzone ──────────────────────────────────────
    $('#uploadDropzone').on('click', function () { $('#buktiBayar').trigger('click'); });

    $('#uploadDropzone').on('dragover', function (e) {
        e.preventDefault();
        $(this).css({ 'border-color': '#1e2d6b', 'background': '#eef2ff' });
    }).on('dragleave', function () {
        $(this).css({ 'border-color': '#d0d5e8', 'background': 'transparent' });
    }).on('drop', function (e) {
        e.preventDefault();
        $(this).css({ 'border-color': '#d0d5e8', 'background': 'transparent' });
        const file = e.originalEvent.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    $('#buktiBayar').on('change', function () {
        if (this.files[0]) handleFile(this.files[0]);
    });

    function handleFile(file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Ukuran file maksimal 5MB.' });
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
            Swal.fire({ icon: 'warning', title: 'Belum Ada File', text: 'Upload bukti pembayaran terlebih dahulu.' });
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true)
            .html('<span style="display:inline-block;width:14px;height:14px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;"></span> Memproses...');

        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('payment_method', selectedMethod);
        formData.append('bukti_bayar', selectedFile);

        $.ajax({
            url: `/siswa/pemesanan/${BOOKING_ID}/bayar`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                window.location.href = res.redirect || `/siswa/pemesanan/${BOOKING_ID}/sukses`;
            },
            error: function (xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Gagal mengirim bukti pembayaran.' });
                $btn.prop('disabled', false)
                    .html('<i class="bi bi-send-check"></i> Konfirmasi Pembayaran');
            }
        });
    });

});
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

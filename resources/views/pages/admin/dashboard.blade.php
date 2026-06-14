@extends('layouts.admin')

@section('title', 'Admin Panel — TutorKu')

@section('content')

<!-- Stat cards -->
    <div class="tk-stats-grid">
        <div class="tk-stat">
            <div class="tk-stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="tk-stat-label">Total Siswa</div>
                <div class="tk-stat-value">0</div>
                <div class="tk-stat-sub">terdaftar</div>
            </div>
        </div>
        <div class="tk-stat">
            <div class="tk-stat-icon indigo"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="tk-stat-label">Tutor Aktif</div>
                <div class="tk-stat-value">0</div>
                <div class="tk-stat-sub">terverifikasi</div>
            </div>
        </div>
        <div class="tk-stat">
            <div class="tk-stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="tk-stat-label">Tutor Pending</div>
                <div class="tk-stat-value">0</div>
                <div class="tk-stat-sub warn">perlu verifikasi</div>
            </div>
        </div>
        <div class="tk-stat">
            <div class="tk-stat-icon green"><i class="bi bi-calendar2-check-fill"></i></div>
            <div>
                <div class="tk-stat-label">Total Booking</div>
                <div class="tk-stat-value">0</div>
                <div class="tk-stat-sub">sepanjang waktu</div>
            </div>
        </div>
    </div>

    <!-- Mid cards -->
    <div class="tk-mid-grid">
        <div class="tk-mid-card accent-indigo">
            <div class="tk-mid-label">Booking Bulan Ini</div>
            <div class="tk-mid-value">0</div>
            <div class="tk-mid-sub"><span class="up">↑ +0%</span> dari bulan lalu</div>
        </div>
        <div class="tk-mid-card accent-teal">
            <div class="tk-mid-label">Sesi Selesai</div>
            <div class="tk-mid-value">0</div>
            <div class="tk-mid-sub">Bulan June 2026</div>
        </div>
        <div class="tk-mid-card">
            <div class="tk-mid-label">Total Transaksi Platform</div>
            <div class="tk-mid-value currency" style="color:#1e2d6b">Rp 0</div>
            <div class="tk-mid-sub">Akumulasi semua pembayaran</div>
        </div>
    </div>

    <!-- Bottom panels -->
    <div class="tk-bottom-grid">

        <!-- Verifikasi tutor -->
        <div class="tk-panel">
            <div class="tk-panel-header">
                <span class="tk-panel-title">
                    <i class="bi bi-shield-check"></i> Verifikasi Tutor Baru
                </span>
                <a href="#" class="tk-panel-link">Lihat semua →</a>
            </div>
            <table class="tk-table">
                <thead>
                    <tr>
                        <th>Tutor</th>
                        <th>Spesialisasi</th>
                        <th>Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span style="font-weight:500">Ahmad Rifai</span></td>
                        <td>Matematika, Fisika</td>
                        <td>01 Jun 2026</td>
                        <td><span class="tk-badge tk-badge-pending">Pending</span></td>
                        <td><a href="#" class="tk-action-btn"><i class="bi bi-eye" style="font-size:11px"></i> Review</a></td>
                    </tr>
                    <tr>
                        <td><span style="font-weight:500">Siti Nurhaliza</span></td>
                        <td>Bahasa Inggris</td>
                        <td>03 Jun 2026</td>
                        <td><span class="tk-badge tk-badge-pending">Pending</span></td>
                        <td><a href="#" class="tk-action-btn"><i class="bi bi-eye" style="font-size:11px"></i> Review</a></td>
                    </tr>
                    <tr>
                        <td><span style="font-weight:500">Dwi Wahyuni</span></td>
                        <td>Kimia, Biologi</td>
                        <td>07 Jun 2026</td>
                        <td><span class="tk-badge tk-badge-pending">Pending</span></td>
                        <td><a href="#" class="tk-action-btn"><i class="bi bi-eye" style="font-size:11px"></i> Review</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Right column -->
        <div class="tk-right-col">

            <!-- Booking terkini -->
            <div class="tk-panel">
                <div class="tk-panel-header">
                    <span class="tk-panel-title">
                        <i class="bi bi-activity"></i> Booking Terkini
                    </span>
                    <a href="#" class="tk-panel-link">Lihat semua →</a>
                </div>
                <div class="tk-empty">
                    <i class="bi bi-calendar-x"></i>
                    Belum ada booking.
                </div>
            </div>

            <!-- Mapel terpopuler -->
            <div class="tk-panel">
                <div class="tk-panel-header">
                    <span class="tk-panel-title">
                        <i class="bi bi-graph-up"></i> Mapel Terpopuler
                    </span>
                </div>
                <div class="tk-mapel-row">
                    <span class="tk-mapel-rank">1</span>
                    <span style="flex:1;font-size:13px">Matematika</span>
                    <span style="font-size:12px;color:#8890a8">24 booking</span>
                    <div class="tk-mapel-bar-track"><div class="tk-mapel-bar-fill" style="width:100%"></div></div>
                </div>
                <div class="tk-mapel-row">
                    <span class="tk-mapel-rank">2</span>
                    <span style="flex:1;font-size:13px">Bahasa Inggris</span>
                    <span style="font-size:12px;color:#8890a8">18 booking</span>
                    <div class="tk-mapel-bar-track"><div class="tk-mapel-bar-fill" style="width:75%"></div></div>
                </div>
                <div class="tk-mapel-row">
                    <span class="tk-mapel-rank">3</span>
                    <span style="flex:1;font-size:13px">Fisika</span>
                    <span style="font-size:12px;color:#8890a8">11 booking</span>
                    <div class="tk-mapel-bar-track"><div class="tk-mapel-bar-fill" style="width:46%"></div></div>
                </div>
                <div class="tk-mapel-row">
                    <span class="tk-mapel-rank">4</span>
                    <span style="flex:1;font-size:13px">Kimia</span>
                    <span style="font-size:12px;color:#8890a8">8 booking</span>
                    <div class="tk-mapel-bar-track"><div class="tk-mapel-bar-fill" style="width:33%"></div></div>
                </div>
            </div>

        </div>
    </div>

{{-- MODAL KONFIRMASI VERIFIKASI TUTOR --}}
{{-- <div class="modal fade" id="verifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-body text-center p-4">
                <div style="width:52px;height:52px;background:var(--tk-primary-50);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-shield-check" style="color:var(--tk-primary);font-size:1.25rem;"></i>
                </div>
                <h5 class="fw-600 mb-1">Verifikasi Tutor?</h5>
                <p class="text-muted small mb-1" id="verifTutorName"></p>
                <p class="text-muted small mb-4">
                    Tutor akan menerima notifikasi email dan langsung bisa aktif di platform.
                </p>
                <input type="hidden" id="verifTutorId">
                <div class="d-flex gap-2">
                    <button type="button" class="flex-1 btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmVerif" class="flex-1 tk-btn-primary"
                            style="width:auto;">
                        <i class="bi bi-check-lg"></i> Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}


@endsection


@push('scripts')
<script>
$(document).ready(function () {

    const verifModal = new bootstrap.Modal('#verifModal');

    // ── VERIFIKASI TUTOR ─────────────────────────────────────
    $(document).on('click', '.btn-verif-tutor', function () {
        const id   = $(this).data('tutor-id');
        const name = $(this).data('tutor-name');

        $('#verifTutorId').val(id);
        $('#verifTutorName').text('Tutor: ' + name);
        verifModal.show();
    });

    $('#confirmVerif').on('click', function () {
        const id   = $('#verifTutorId').val();
        const $btn = $(this);

        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-2" style="width:12px;height:12px;border-width:2px;"></span>Memproses...');

        $.ajax({
            url: `/admin/tutor/${id}/verifikasi`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                verifModal.hide();
                showToast('Tutor berhasil diverifikasi! Email notifikasi dikirim.', 'success');

                // Update row
                const $row = $(`.pending-tutor-row[data-tutor-id="${id}"]`);
                $row.find('.tutor-status-badge')
                    .removeClass('tk-badge-pending')
                    .addClass('tk-badge-completed')
                    .html('<i class="bi bi-patch-check-fill me-1"></i>Aktif');
                $row.find('.btn-verif-tutor, .btn-tolak-tutor').remove();

                // Update pending counter badge di sidebar
                updatePendingTutorBadge(-1);
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal memverifikasi tutor.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-check-lg"></i> Verifikasi');
            }
        });
    });

    // ── TOLAK TUTOR ──────────────────────────────────────────
    $(document).on('click', '.btn-tolak-tutor', function () {
        const id = $(this).data('tutor-id');
        if (!confirm('Tolak pendaftaran tutor ini? Tindakan ini tidak dapat dibatalkan.')) return;

        $.ajax({
            url: `/admin/tutor/${id}/tolak`,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                showToast('Pendaftaran tutor ditolak.', 'info');
                $(`.pending-tutor-row[data-tutor-id="${id}"]`).fadeOut(300, function () {
                    $(this).remove();
                    updatePendingTutorBadge(-1);
                    if ($('.pending-tutor-row').length === 0) {
                        $('#pendingTutorTable').html(`
                            <tr><td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle-fill me-2" style="color:var(--tk-success-text);"></i>
                                Semua tutor sudah diverifikasi.
                            </td></tr>`);
                    }
                });
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal menolak tutor.', 'error');
            }
        });
    });

    function updatePendingTutorBadge(delta) {
        const $badge = $('.tk-sidebar-link .badge').first();
        if ($badge.length) {
            const next = (parseInt($badge.text()) || 0) + delta;
            if (next <= 0) $badge.remove();
            else $badge.text(next);
        }
    }

});
</script>
@endpush

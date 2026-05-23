@extends('layouts.app')

@section('title', 'Kelola Jadwal — TutorKu')

@section('content')

<div class="d-flex" style="min-height:calc(100vh - 56px);">

{{-- SIDEBAR --}}
<aside class="tk-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 mb-3 text-center">
        <div class="tk-avatar-sm mx-auto mb-2"
             style="width:44px;height:44px;font-size:.9rem;background:var(--tk-primary-light);">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="text-white fw-500" style="font-size:.875rem;">{{ Auth::user()->name }}</div>
        <div style="color:rgba(255,255,255,.4);font-size:.75rem;">Tutor</div>
    </div>
    <hr style="border-color:rgba(255,255,255,.08);margin:.5rem 0;">
    <div class="tk-sidebar-section">Menu</div>
    <a href="{{ route('tutor.dashboard') }}" class="tk-sidebar-link">
        <i class="bi bi-grid"></i> Dashboard
    </a>
    <a href="{{ route('tutor.pemesanan') }}" class="tk-sidebar-link">
        <i class="bi bi-calendar-check"></i> Pemesanan
    </a>
    <a href="{{ route('tutor.jadwal') }}" class="tk-sidebar-link active">
        <i class="bi bi-calendar3"></i> Kelola Jadwal
    </a>
    <a href="{{ route('tutor.riwayat') }}" class="tk-sidebar-link">
        <i class="bi bi-clock-history"></i> Riwayat Mengajar
    </a>
    <div class="tk-sidebar-section mt-2">Akun</div>
    <a href="{{ route('tutor.profil') }}" class="tk-sidebar-link">
        <i class="bi bi-person"></i> Profil Saya
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-auto mb-3">
        @csrf
        <button type="submit" class="tk-sidebar-link w-100 text-start border-0 bg-transparent"
                style="color:rgba(255,255,255,.4);">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</aside>

{{-- MAIN --}}
<main class="flex-1 p-4" style="background:var(--tk-surface);min-width:0;">
    <div class="container-fluid" style="max-width:860px;">

        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.25rem;font-weight:600;margin-bottom:4px;">Kelola Jadwal</h1>
                <p class="text-muted mb-0" style="font-size:.875rem;">
                    Atur hari dan jam ketersediaan Anda untuk mengajar. Jadwal yang sudah di-booking tidak dapat dihapus.
                </p>
            </div>
            <button type="button" class="tk-btn-primary flex-shrink-0"
                    data-bs-toggle="modal" data-bs-target="#tambahJadwalModal"
                    style="width:auto;padding:.5rem 1rem;font-size:.875rem;">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </button>
        </div>

        {{-- INFO: Hari ini --}}
        <div class="tk-card mb-4"
             style="background:var(--tk-primary-50);border-color:var(--tk-primary-100);">
            <div class="tk-card-body py-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span style="font-size:.8375rem;color:var(--tk-primary-dark);">
                        Slot berwarna <strong>merah</strong> sudah di-booking siswa dan tidak dapat dihapus.
                        Slot <strong>hijau</strong> masih kosong dan bisa dihapus.
                    </span>
                </div>
            </div>
        </div>

        {{-- ── JADWAL GRID per hari ────────────────────────── --}}
        @php
            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        @endphp

        <div class="row g-3">
            @foreach($days as $day)
                @php
                    $daySchedules = $schedules->where('hari', $day)->sortBy('jam_mulai');
                @endphp
                <div class="col-sm-6 col-lg-4">
                    <div class="tk-card h-100">
                        <div class="tk-card-header py-2 px-3">
                            <h3 style="font-size:.875rem;font-weight:600;margin:0;">
                                {{ $day }}
                            </h3>
                            <span class="text-muted" style="font-size:.75rem;">
                                {{ $daySchedules->count() }} slot
                            </span>
                        </div>
                        <div class="tk-card-body py-2 px-3">
                            @if($daySchedules->isEmpty())
                                <p class="text-muted text-center py-3 mb-0" style="font-size:.8rem;">
                                    Belum ada jadwal
                                </p>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    @foreach($daySchedules as $slot)
                                        @php
                                            $isBooked = $slot->bookings()
                                                ->whereIn('status', ['pending','confirmed'])
                                                ->exists();
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between
                                                    py-2 px-2 rounded schedule-slot"
                                             style="background:{{ $isBooked ? 'var(--tk-danger-bg)' : 'var(--tk-success-bg)' }};
                                                    border:1px solid {{ $isBooked ? 'var(--tk-danger-border)' : 'var(--tk-success-border)' }};">
                                            <div>
                                                <div style="font-size:.8125rem;font-weight:500;
                                                            color:{{ $isBooked ? 'var(--tk-danger-text)' : 'var(--tk-success-text)' }};">
                                                    {{ $slot->jam_mulai }} — {{ $slot->jam_selesai }}
                                                </div>
                                                @if($isBooked)
                                                    <div style="font-size:.7rem;color:var(--tk-danger-text);opacity:.7;">
                                                        <i class="bi bi-lock-fill me-1"></i>Di-booking
                                                    </div>
                                                @endif
                                            </div>
                                            @if(!$isBooked)
                                                <button type="button"
                                                        class="btn-hapus-slot"
                                                        data-slot-id="{{ $slot->id }}"
                                                        data-hari="{{ $slot->hari }}"
                                                        data-jam="{{ $slot->jam_mulai }}"
                                                        style="background:none;border:none;
                                                               color:var(--tk-danger-text);
                                                               cursor:pointer;padding:2px 4px;
                                                               opacity:.6;transition:opacity .15s;"
                                                        title="Hapus slot"
                                                        onmouseover="this.style.opacity='1'"
                                                        onmouseout="this.style.opacity='.6'">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @else
                                                <i class="bi bi-lock-fill"
                                                   style="color:var(--tk-danger-text);opacity:.4;font-size:.75rem;"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</main>
</div>


{{-- ================================================================
     MODAL TAMBAH JADWAL
================================================================ --}}
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Tambah Slot Jadwal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-muted small mb-4">
                    Pilih hari dan jam yang ingin Anda tambahkan sebagai slot ketersediaan mengajar.
                </p>

                {{-- Pilih hari --}}
                <div class="mb-3">
                    <label class="tk-form-label">Hari</label>
                    <div class="d-flex flex-wrap gap-2" id="dayPicker">
                        @foreach($days as $i => $day)
                            <button type="button"
                                    class="day-pick-btn"
                                    data-day="{{ $day }}"
                                    style="border:1px solid var(--tk-border);background:#fff;
                                           border-radius:var(--tk-radius);padding:.375rem .75rem;
                                           font-size:.8125rem;color:var(--tk-text-muted);
                                           cursor:pointer;transition:all .15s;">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedDay">
                </div>

                {{-- Pilih jam mulai --}}
                <div class="mb-3">
                    <label class="tk-form-label" for="jamMulai">Jam Mulai</label>
                    <select id="jamMulai" class="tk-form-control">
                        <option value="">Pilih jam mulai...</option>
                        @foreach(['07:00','08:00','09:00','10:00','11:00','12:00',
                                  '13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'] as $jam)
                            <option value="{{ $jam }}">{{ $jam }} WIB</option>
                        @endforeach
                    </select>
                </div>

                {{-- Durasi slot --}}
                <div class="mb-3">
                    <label class="tk-form-label" for="durasiSlot">Durasi Slot</label>
                    <select id="durasiSlot" class="tk-form-control">
                        <option value="1">1 jam</option>
                        <option value="1.5">1.5 jam</option>
                        <option value="2">2 jam</option>
                    </select>
                </div>

                {{-- Preview --}}
                <div id="slotPreview" class="mb-3"
                     style="display:none;background:var(--tk-success-bg);border:1px solid var(--tk-success-border);
                            border-radius:var(--tk-radius-lg);padding:.75rem 1rem;">
                    <span style="font-size:.875rem;color:var(--tk-success-text);">
                        <i class="bi bi-calendar-check me-1"></i>
                        <strong id="previewText"></strong>
                    </span>
                </div>

                {{-- Tambah berulang --}}
                <div class="d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="checkbox" id="isRepeat"
                           style="width:16px;height:16px;border-radius:4px;">
                    <label class="form-check-label text-muted" for="isRepeat" style="font-size:.8375rem;">
                        Ulangi setiap minggu (jadwal rutin)
                    </label>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="tk-btn-primary" id="btnTambahSlot"
                        style="width:auto;padding:.5rem 1.25rem;" disabled>
                    <i class="bi bi-plus-lg"></i> Tambah Slot
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="modal fade" id="hapusSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--tk-radius-xl);border:none;">
            <div class="modal-body text-center p-4">
                <div style="width:52px;height:52px;background:var(--tk-danger-bg);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-trash" style="color:var(--tk-danger-text);font-size:1.25rem;"></i>
                </div>
                <h5 class="fw-600 mb-1">Hapus Slot?</h5>
                <p class="text-muted small mb-1" id="hapusSlotLabel"></p>
                <p class="text-muted small mb-4">Slot yang dihapus tidak dapat dikembalikan.</p>
                <input type="hidden" id="hapusSlotId">
                <div class="d-flex gap-2">
                    <button type="button" class="flex-1 btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmHapusSlot"
                            class="flex-1 tk-btn-primary"
                            style="background:var(--tk-danger-text);">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    const tambahModal = new bootstrap.Modal('#tambahJadwalModal');
    const hapusModal  = new bootstrap.Modal('#hapusSlotModal');

    // ── TAMBAH JADWAL ─────────────────────────────────────────

    // Pilih hari
    $(document).on('click', '.day-pick-btn', function () {
        $('.day-pick-btn').css({
            background: '#fff', color: 'var(--tk-text-muted)', borderColor: 'var(--tk-border)'
        });
        $(this).css({
            background: 'var(--tk-primary-50)',
            color: 'var(--tk-primary)',
            borderColor: 'var(--tk-primary)'
        });
        $('#selectedDay').val($(this).data('day'));
        updatePreview();
    });

    // Update preview saat jam/durasi berubah
    $('#jamMulai, #durasiSlot').on('change', updatePreview);

    function updatePreview() {
        const day    = $('#selectedDay').val();
        const jam    = $('#jamMulai').val();
        const durasi = parseFloat($('#durasiSlot').val()) || 1;

        const isReady = day && jam;
        $('#btnTambahSlot').prop('disabled', !isReady);

        if (isReady) {
            const [h, m] = jam.split(':').map(Number);
            const totalMin = h * 60 + m + durasi * 60;
            const endH  = Math.floor(totalMin / 60);
            const endM  = String(totalMin % 60).padStart(2, '0');
            const jamSelesai = `${endH}:${endM}`;

            $('#previewText').text(`${day}, ${jam} – ${jamSelesai} WIB`);
            $('#slotPreview').show();
        } else {
            $('#slotPreview').hide();
        }
    }

    // Submit tambah slot
    $('#btnTambahSlot').on('click', function () {
        const day    = $('#selectedDay').val();
        const jam    = $('#jamMulai').val();
        const durasi = parseFloat($('#durasiSlot').val()) || 1;

        if (!day || !jam) { showToast('Pilih hari dan jam terlebih dahulu.', 'warning'); return; }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Menyimpan...');

        // Hitung jam selesai
        const [h, m] = jam.split(':').map(Number);
        const totalMin = h * 60 + m + durasi * 60;
        const jamSelesai = `${Math.floor(totalMin / 60)}:${String(totalMin % 60).padStart(2, '0')}`;

        $.ajax({
            url: '{{ route("tutor.jadwal.store") }}',
            method: 'POST',
            data: {
                _token:      $('meta[name="csrf-token"]').attr('content'),
                hari:        day,
                jam_mulai:   jam,
                jam_selesai: jamSelesai,
                is_repeat:   $('#isRepeat').is(':checked') ? 1 : 0,
            },
            success: function (res) {
                tambahModal.hide();
                showToast('Slot jadwal berhasil ditambahkan!', 'success');
                // Reload halaman untuk refresh grid
                setTimeout(() => location.reload(), 800);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal menambah jadwal.';
                showToast(msg, 'error');
                $btn.prop('disabled', false).html('<i class="bi bi-plus-lg"></i> Tambah Slot');
            }
        });
    });

    // Reset modal saat ditutup
    $('#tambahJadwalModal').on('hidden.bs.modal', function () {
        $('#selectedDay').val('');
        $('#jamMulai').val('');
        $('#durasiSlot').val('1');
        $('#slotPreview').hide();
        $('#btnTambahSlot').prop('disabled', true);
        $('.day-pick-btn').css({ background:'#fff', color:'var(--tk-text-muted)', borderColor:'var(--tk-border)' });
    });

    // ── HAPUS SLOT ────────────────────────────────────────────
    $(document).on('click', '.btn-hapus-slot', function () {
        const id   = $(this).data('slot-id');
        const hari = $(this).data('hari');
        const jam  = $(this).data('jam');

        $('#hapusSlotId').val(id);
        $('#hapusSlotLabel').text(`${hari}, ${jam} WIB`);
        hapusModal.show();
    });

    $('#confirmHapusSlot').on('click', function () {
        const id   = $('#hapusSlotId').val();
        const $btn = $(this);

        $btn.prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: `/tutor-panel/jadwal/${id}`,
            method: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                hapusModal.hide();
                showToast('Slot jadwal berhasil dihapus.', 'success');
                setTimeout(() => location.reload(), 700);
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Gagal menghapus slot.', 'error');
                $btn.prop('disabled', false).text('Hapus');
            }
        });
    });

});
</script>
@endpush

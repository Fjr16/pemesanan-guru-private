@extends('layouts.tutor')

@section('title', 'Kelola Jadwal — TutorKu')
@section('page-title', 'Kelola Jadwal')

@section('topbar-actions')
<button type="button" class="tk-topbar-btn" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
    <i class="bi bi-plus-lg"></i> Tambah Jadwal
</button>
@endsection

@section('content')

<div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:flex-start;gap:8px;">
    <i class="bi bi-info-circle" style="color:#3730a3;font-size:14px;margin-top:1px;"></i>
    <span style="font-size:12px;color:#3730a3;line-height:1.5;">
        <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#dcfce7;border:1px solid #86efac;margin-right:2px;"></span> Tersedia — bisa dihapus &nbsp;
        <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#fef9c3;border:1px solid #fde047;margin-right:2px;"></span> Dibooking, menunggu bayar &nbsp;
        <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#dbeafe;border:1px solid #93c5fd;margin-right:2px;"></span> Sudah dibayar — sesi aktif &nbsp;
        <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#f0fdf4;border:1px solid #86efac;margin-right:2px;"></span> Selesai
    </span>
</div>

@php $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
    @foreach($days as $day)
        @php $daySchedules = ($schedules ?? collect())->where('day', $day)->sortBy('jam_start'); @endphp
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #f0f2f8;">
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $day }}</span>
                <span style="font-size:11px;color:#8890a8;">{{ $daySchedules->count() }} slot</span>
            </div>
            <div style="padding:12px 16px;flex:1;">
                @if($daySchedules->isEmpty())
                    <p style="text-align:center;color:#8890a8;font-size:12px;padding:12px 0;margin:0 0 8px;">Belum ada jadwal</p>
                    <button type="button" class="btn-tambah-dari-hari" data-day="{{ $day }}"
                            style="width:100%;padding:6px;border:1px dashed #d0d5e8;border-radius:8px;background:#fafbff;font-size:12px;color:#4b5574;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:4px;transition:all .15s;"
                            onmouseover="this.style.borderColor='#7f9cf5';this.style.color='#1e2d6b'"
                            onmouseout="this.style.borderColor='#d0d5e8';this.style.color='#4b5574'">
                        <i class="bi bi-plus" style="font-size:14px;"></i> Tambah
                    </button>
                @else
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($daySchedules as $slot)
                            @php
                                $hasActive = $slot->has_active_locks;
                                $hasLocked = collect($slot->locked_dates)->contains('status', 'locked');
                                $hasConfirmedPaid = $slot->has_confirmed_paid;
                                $hasPaid = $slot->has_paid;

                                if ($hasPaid) {
                                    $bgColor = '#f0fdf4'; $borderColor = '#86efac'; $textColor = '#15803d'; $statusLabel = 'Selesai'; $statusIcon = 'bi-check-circle-fill';
                                } elseif ($hasConfirmedPaid) {
                                    $bgColor = '#dbeafe'; $borderColor = '#93c5fd'; $textColor = '#1e40af'; $statusLabel = 'Sudah Dibayar'; $statusIcon = 'bi-wallet2';
                                } elseif ($hasLocked) {
                                    $bgColor = '#fef9c3'; $borderColor = '#fde047'; $textColor = '#854d0e'; $statusLabel = 'Menunggu Bayar'; $statusIcon = 'bi-hourglass-split';
                                } else {
                                    $bgColor = '#f0fdf4'; $borderColor = '#a7f3d0'; $textColor = '#15803d'; $statusLabel = 'Tersedia'; $statusIcon = 'bi-check-circle';
                                }
                            @endphp
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:8px 10px;border-radius:8px;background:{{ $bgColor }};border:1px solid {{ $borderColor }};">
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <i class="bi {{ $statusIcon }}" style="font-size:11px;color:{{ $textColor }};"></i>
                                        <span style="font-size:12px;font-weight:500;color:{{ $textColor }};">
                                            {{ $slot->jam_start }} — {{ $slot->jam_end }}
                                        </span>
                                        @if($hasActive || $hasConfirmedPaid || $hasPaid)
                                            <span style="font-size:9px;padding:1px 6px;border-radius:4px;background:{{ $hasPaid ? '#dcfce7' : ($hasConfirmedPaid ? '#bfdbfe' : ($hasLocked ? '#fef9c3' : '#dcfce7')) }};color:{{ $textColor }};font-weight:600;margin-left:auto;">
                                                {{ $statusLabel }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($hasActive)
                                        <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:3px;">
                                            @foreach($slot->locked_dates as $lock)
                                                <span style="display:inline-block;font-size:9px;padding:1px 5px;border-radius:4px;background:{{ $lock['status'] === 'confirmed' ? '#dbeafe' : '#fef9c3' }};color:{{ $lock['status'] === 'confirmed' ? '#1e40af' : '#854d0e' }};">
                                                    {{ $lock['tanggal'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @if(!$hasActive)
                                    <button type="button"
                                            class="btn-hapus-slot"
                                            data-slot-id="{{ $slot->id }}"
                                            data-day="{{ $slot->day }}"
                                            data-jam="{{ $slot->jam_start }} — {{ $slot->jam_end }}"
                                            style="background:none;border:none;color:#991b1b;cursor:pointer;padding:2px 4px;opacity:.6;transition:opacity .15s;"
                                            title="Hapus slot"
                                            onmouseover="this.style.opacity='1'"
                                            onmouseout="this.style.opacity='.6'">
                                        <i class="bi bi-x-lg" style="font-size:11px;"></i>
                                    </button>
                                @else
                                    <i class="bi bi-lock-fill" style="color:{{ $textColor }};opacity:.4;font-size:11px;margin-top:2px;"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div id="toastContainer" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px"></div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-header" style="padding:20px 24px 0;border:none">
                <h5 class="modal-title" style="font-size:15px;font-weight:600;color:#1a1a2e;display:flex;align-items:center;gap:8px">
                    <i class="bi bi-calendar-plus" style="color:#1e2d6b"></i> Tambah Slot Jadwal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:13px"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px">
                <p style="font-size:12px;color:#8890a8;margin:0 0 16px;">Pilih hari dan jam ketersediaan. Setiap slot berdurasi <strong>1 jam</strong>.</p>

                <div style="margin-bottom:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block;">Hari</label>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;" id="dayPicker">
                        @foreach($days as $day)
                            <button type="button" class="day-pick-btn" data-day="{{ $day }}"
                                    style="border:1px solid #e8eaf0;background:#fff;border-radius:6px;padding:6px 12px;font-size:12px;color:#4b5574;cursor:pointer;transition:all .15s;font-family:inherit;">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedDay">
                </div>

                <div style="margin-bottom:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block;" for="jamMulai">Jam</label>
                    <input type="time" id="jamMulai" step="3600"
                           style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                    <div style="font-size:11px;color:#8890a8;margin-top:4px;">
                        Slot otomatis 1 jam. <span id="jamEndPreview" style="font-weight:500;color:#4b5574;"></span>
                    </div>
                </div>

                <div id="slotPreview" style="display:none;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:8px;padding:10px 14px;margin-bottom:14px;">
                    <span style="font-size:13px;color:#15803d;">
                        <i class="bi bi-calendar-check" style="margin-right:4px;"></i>
                        <strong id="previewText"></strong>
                    </span>
                </div>

                <div id="overlapWarning" style="display:none;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-bottom:14px;">
                    <span style="font-size:12px;color:#991b1b;">
                        <i class="bi bi-exclamation-triangle-fill" style="margin-right:4px;"></i>
                        <span id="overlapText"></span>
                    </span>
                </div>
            </div>
            <div class="modal-footer" style="padding:0 24px 20px;border:none;gap:8px">
                <button type="button" class="tk-topbar-btn" data-bs-dismiss="modal" style="color:#4b5574">Batal</button>
                <button type="button" id="btnTambahSlot" disabled
                        style="display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s;">
                    <i class="bi bi-plus-lg"></i> Tambah Slot
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="hapusSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:340px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-trash" style="font-size:22px;color:#991b1b"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">Hapus Slot?</h5>
                <p style="font-size:13px;color:#4b5574;margin:0 0 4px" id="hapusSlotLabel"></p>
                <p style="font-size:12px;color:#8890a8;margin:0 0 24px;">Slot yang dihapus tidak dapat dikembalikan.</p>
                <input type="hidden" id="hapusSlotId">
                <div style="display:flex;gap:8px">
                    <button type="button" class="tk-topbar-btn" style="flex:1;justify-content:center;color:#4b5574" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmHapusSlot"
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#991b1b;color:#fff;transition:background .15s;">
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
document.addEventListener('DOMContentLoaded', function () {

    const tambahModal = new bootstrap.Modal(document.getElementById('tambahJadwalModal'));
    const hapusModal  = new bootstrap.Modal(document.getElementById('hapusSlotModal'));
    const existingSlots = @json($schedules->map(fn($s) => ['day' => $s->day, 'jam_start' => $s->jam_start]));

    function showToast(msg, type = 'success') {
        const colors = type === 'success' ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca';
        const icon = type === 'success' ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-exclamation-circle-fill"></i>';
        const t = document.createElement('div');
        t.style.cssText = `display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;min-width:240px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1);animation:slideUp .2s ease;${colors}`;
        t.innerHTML = icon + ' ' + msg;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
    }

    function addOneHour(timeStr) {
        const [h, m] = timeStr.split(':').map(Number);
        return String((h + 1) % 24).padStart(2, '0') + ':' + String(m).padStart(2, '0');
    }

    function checkOverlap(day, jamMulai) {
        if (!day || !jamMulai) return [];
        const jamSelesai = addOneHour(jamMulai);
        return existingSlots.filter(s => s.day === day && s.jam_start === jamMulai);
    }

    // Day picker
    document.querySelectorAll('.day-pick-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.day-pick-btn').forEach(b => { b.style.background = '#fff'; b.style.color = '#4b5574'; b.style.borderColor = '#e8eaf0'; });
            this.style.background = '#eef2ff'; this.style.color = '#3730a3'; this.style.borderColor = '#3730a3';
            document.getElementById('selectedDay').value = this.dataset.day;
            updatePreview();
        });
    });

    document.getElementById('jamMulai').addEventListener('change', updatePreview);

    function updatePreview() {
        const day = document.getElementById('selectedDay').value;
        const jamMulai = document.getElementById('jamMulai').value;
        const isReady = day && jamMulai;
        document.getElementById('btnTambahSlot').disabled = !isReady;

        const preview = document.getElementById('slotPreview');
        const overlapWarning = document.getElementById('overlapWarning');
        const jamEndPreview = document.getElementById('jamEndPreview');

        if (isReady) {
            const jamSelesai = addOneHour(jamMulai);
            jamEndPreview.textContent = 'Selesai: ' + jamSelesai;
            document.getElementById('previewText').textContent = day + ', ' + jamMulai + ' – ' + jamSelesai + ' WIB';
            preview.style.display = 'block';

            const duplicates = checkOverlap(day, jamMulai);
            if (duplicates.length > 0) {
                document.getElementById('overlapText').textContent = 'Slot jam ' + jamMulai + ' sudah ada di hari ' + day + '.';
                overlapWarning.style.display = 'block';
                document.getElementById('btnTambahSlot').disabled = true;
            } else {
                overlapWarning.style.display = 'none';
            }
        } else {
            preview.style.display = 'none';
            overlapWarning.style.display = 'none';
            jamEndPreview.textContent = '';
        }
    }

    // Quick add from day card
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-tambah-dari-hari');
        if (!btn) return;
        const day = btn.dataset.day;
        document.querySelectorAll('.day-pick-btn').forEach(b => {
            if (b.dataset.day === day) {
                b.style.background = '#eef2ff'; b.style.color = '#3730a3'; b.style.borderColor = '#3730a3';
            } else {
                b.style.background = '#fff'; b.style.color = '#4b5574'; b.style.borderColor = '#e8eaf0';
            }
        });
        document.getElementById('selectedDay').value = day;
        tambahModal.show();
    });

    document.getElementById('btnTambahSlot').addEventListener('click', function () {
        const day = document.getElementById('selectedDay').value;
        const jamMulai = document.getElementById('jamMulai').value;
        if (!day || !jamMulai) { showToast('Pilih hari dan jam.', 'error'); return; }

        const duplicates = checkOverlap(day, jamMulai);
        if (duplicates.length > 0) { showToast('Slot jam ' + jamMulai + ' sudah ada.', 'error'); return; }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menyimpan...';

        fetch('{{ route("tutor.jadwal.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({ day: day, jam_start: jamMulai })
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { tambahModal.hide(); showToast('Slot jadwal berhasil ditambahkan!'); setTimeout(() => location.reload(), 800); })
        .catch(async (r) => {
            let msg = 'Gagal menambah jadwal.';
            try { const j = await r.json(); if (j.message) msg = j.message; } catch(e) {}
            showToast(msg, 'error');
        })
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-plus-lg"></i> Tambah Slot'; });
    });

    // Hapus slot
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-slot');
        if (!btn) return;
        document.getElementById('hapusSlotId').value = btn.dataset.slotId;
        document.getElementById('hapusSlotLabel').textContent = btn.dataset.day + ', ' + btn.dataset.jam;
        hapusModal.show();
    });

    document.getElementById('confirmHapusSlot').addEventListener('click', function () {
        const id = document.getElementById('hapusSlotId').value;
        const btn = this;
        btn.disabled = true; btn.textContent = 'Menghapus...';
        fetch('/tutor-panel/jadwal/' + id, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { hapusModal.hide(); showToast('Slot jadwal berhasil dihapus.'); setTimeout(() => location.reload(), 700); })
        .catch(async (r) => {
            let msg = 'Gagal menghapus slot.';
            try { const j = await r.json(); if (j.message) msg = j.message; } catch(e) {}
            showToast(msg, 'error');
        })
        .finally(() => { btn.disabled = false; btn.textContent = 'Hapus'; });
    });

    // Reset modal
    document.getElementById('tambahJadwalModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('selectedDay').value = '';
        document.getElementById('jamMulai').value = '';
        document.getElementById('slotPreview').style.display = 'none';
        document.getElementById('overlapWarning').style.display = 'none';
        document.getElementById('jamEndPreview').textContent = '';
        document.getElementById('btnTambahSlot').disabled = true;
        document.querySelectorAll('.day-pick-btn').forEach(b => { b.style.background = '#fff'; b.style.color = '#4b5574'; b.style.borderColor = '#e8eaf0'; });
    });

});
</script>

<style>
    @keyframes slideUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin    { to { transform:rotate(360deg); } }
</style>
@endpush

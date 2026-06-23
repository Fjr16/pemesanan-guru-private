@extends('layouts.tutor')

@section('title', 'Kelola Jadwal — TutorKu')
@section('page-title', 'Kelola Jadwal')

@section('topbar-actions')
<button type="button" class="tk-topbar-btn" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
    <i class="bi bi-plus-lg"></i> Tambah Jadwal
</button>
@endsection

@section('content')

{{-- ── Info ──────────────────────────────────────────────────── --}}
<div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-info-circle" style="color:#3730a3;font-size:14px;"></i>
    <span style="font-size:13px;color:#3730a3;">
        Slot berwarna <strong>merah</strong> sudah di-booking siswa dan tidak dapat dihapus.
        Slot <strong>hijau</strong> masih kosong dan bisa dihapus.
    </span>
</div>

{{-- ── Jadwal grid per hari ──────────────────────────────────── --}}
@php $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
    @foreach($days as $day)
        @php $daySchedules = ($schedules ?? collect())->where('hari', $day)->sortBy('jam_mulai'); @endphp
        <div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #f0f2f8;">
                <span style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $day }}</span>
                <span style="font-size:11px;color:#8890a8;">{{ $daySchedules->count() }} slot</span>
            </div>
            <div style="padding:12px 16px;flex:1;">
                @if($daySchedules->isEmpty())
                    <p style="text-align:center;color:#8890a8;font-size:12px;padding:16px 0;margin:0;">Belum ada jadwal</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($daySchedules as $slot)
                            @php
                                $isBooked = ($slot->bookings_count ?? 0) > 0;
                            @endphp
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:8px;{{ $isBooked ? 'background:#fef2f2;border:1px solid #fecaca;' : 'background:#f0fdf4;border:1px solid #a7f3d0;' }}">
                                <div>
                                    <div style="font-size:12px;font-weight:500;color:{{ $isBooked ? '#991b1b' : '#15803d' }};">
                                        {{ $slot->jam_mulai ?? '09:00' }} — {{ $slot->jam_selesai ?? '11:00' }}
                                    </div>
                                    @if($isBooked)
                                        <div style="font-size:10px;color:#991b1b;opacity:.7;">
                                            <i class="bi bi-lock-fill" style="margin-right:2px;"></i>Di-booking
                                        </div>
                                    @endif
                                </div>
                                @if(!$isBooked)
                                    <button type="button"
                                            class="btn-hapus-slot"
                                            data-slot-id="{{ $slot->id }}"
                                            data-hari="{{ $slot->hari }}"
                                            data-jam="{{ $slot->jam_mulai }}"
                                            style="background:none;border:none;color:#991b1b;cursor:pointer;padding:2px 4px;opacity:.6;transition:opacity .15s;"
                                            title="Hapus slot"
                                            onmouseover="this.style.opacity='1'"
                                            onmouseout="this.style.opacity='.6'">
                                        <i class="bi bi-x-lg" style="font-size:11px;"></i>
                                    </button>
                                @else
                                    <i class="bi bi-lock-fill" style="color:#991b1b;opacity:.4;font-size:11px;"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- Toast container --}}
<div id="toastContainer" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px"></div>

{{-- ================================================================
     MODAL TAMBAH JADWAL
=============================================================== --}}
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
                <p style="font-size:12px;color:#8890a8;margin:0 0 16px;">Pilih hari dan jam ketersediaan mengajar.</p>

                {{-- Pilih hari --}}
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

                {{-- Jam mulai --}}
                <div style="margin-bottom:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block;" for="jamMulai">Jam Mulai</label>
                    <select id="jamMulai" style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                        <option value="">Pilih jam mulai...</option>
                        @foreach(['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'] as $jam)
                            <option value="{{ $jam }}">{{ $jam }} WIB</option>
                        @endforeach
                    </select>
                </div>

                {{-- Durasi --}}
                <div style="margin-bottom:14px;">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block;" for="durasiSlot">Durasi Slot</label>
                    <select id="durasiSlot" style="width:100%;height:34px;padding:0 10px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;">
                        <option value="1">1 jam</option>
                        <option value="1.5">1.5 jam</option>
                        <option value="2">2 jam</option>
                    </select>
                </div>

                {{-- Preview --}}
                <div id="slotPreview" style="display:none;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:8px;padding:10px 14px;margin-bottom:14px;">
                    <span style="font-size:13px;color:#15803d;">
                        <i class="bi bi-calendar-check" style="margin-right:4px;"></i>
                        <strong id="previewText"></strong>
                    </span>
                </div>

                {{-- Repeat --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <input class="form-check-input" type="checkbox" id="isRepeat" style="width:16px;height:16px;cursor:pointer;">
                    <label style="font-size:12px;color:#8890a8;cursor:pointer;margin:0;" for="isRepeat">Ulangi setiap minggu (jadwal rutin)</label>
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
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#991b1b;color:#fff;transition:background .15s">
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

    function showToast(msg, type = 'success') {
        const colors = type === 'success' ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca';
        const icon = type === 'success' ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-exclamation-circle-fill"></i>';
        const t = document.createElement('div');
        t.style.cssText = `display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;min-width:240px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1);animation:slideUp .2s ease;${colors}`;
        t.innerHTML = icon + ' ' + msg;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
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
    document.getElementById('durasiSlot').addEventListener('change', updatePreview);

    function updatePreview() {
        const day = document.getElementById('selectedDay').value;
        const jam = document.getElementById('jamMulai').value;
        const durasi = parseFloat(document.getElementById('durasiSlot').value) || 1;
        const isReady = day && jam;
        document.getElementById('btnTambahSlot').disabled = !isReady;
        if (isReady) {
            const [h, m] = jam.split(':').map(Number);
            const totalMin = h * 60 + m + durasi * 60;
            document.getElementById('previewText').textContent = day + ', ' + jam + ' – ' + String(Math.floor(totalMin/60)).padStart(2,'0') + ':' + String(totalMin%60).padStart(2,'0') + ' WIB';
            document.getElementById('slotPreview').style.display = 'block';
        } else {
            document.getElementById('slotPreview').style.display = 'none';
        }
    }

    document.getElementById('btnTambahSlot').addEventListener('click', function () {
        const day = document.getElementById('selectedDay').value;
        const jam = document.getElementById('jamMulai').value;
        if (!day || !jam) { showToast('Pilih hari dan jam terlebih dahulu.', 'error'); return; }
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menyimpan...';
        fetch('{{ route("tutor.jadwal.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ hari: day, jam_mulai: jam, jam_selesai: document.getElementById('previewText').textContent.split('–')[1]?.trim().replace(' WIB','') || '' })
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { tambahModal.hide(); showToast('Slot jadwal berhasil ditambahkan!'); setTimeout(() => location.reload(), 800); })
        .catch(() => showToast('Gagal menambah jadwal.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-plus-lg"></i> Tambah Slot'; });
    });

    // Hapus slot
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-slot');
        if (!btn) return;
        document.getElementById('hapusSlotId').value = btn.dataset.slotId;
        document.getElementById('hapusSlotLabel').textContent = btn.dataset.hari + ', ' + btn.dataset.jam + ' WIB';
        hapusModal.show();
    });

    document.getElementById('confirmHapusSlot').addEventListener('click', function () {
        const id = document.getElementById('hapusSlotId').value;
        const btn = this;
        btn.disabled = true; btn.textContent = 'Menghapus...';
        fetch('/tutor-panel/jadwal/' + id, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        })
        .then(r => { if (!r.ok) throw r; return r.json(); })
        .then(() => { hapusModal.hide(); showToast('Slot jadwal berhasil dihapus.'); setTimeout(() => location.reload(), 700); })
        .catch(() => showToast('Gagal menghapus slot.', 'error'))
        .finally(() => { btn.disabled = false; btn.textContent = 'Hapus'; });
    });

    // Reset modal
    document.getElementById('tambahJadwalModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('selectedDay').value = '';
        document.getElementById('jamMulai').value = '';
        document.getElementById('durasiSlot').value = '1';
        document.getElementById('slotPreview').style.display = 'none';
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

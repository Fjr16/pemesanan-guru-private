@extends('layouts.admin')

@section('title', 'Siswa — Admin TutorKu')

@section('content')

{{-- ── Page header ──────────────────────────────────────────── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px;flex-wrap:wrap;">
    <div>
        <h1 style="font-size:16px;font-weight:600;margin:0 0 2px;color:#1a1a2e">Siswa</h1>
        <p style="font-size:13px;color:#8890a8;margin:0">Kelola daftar siswa yang tersedia di platform.</p>
    </div>
</div>

{{-- ── Toolbar ───────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:12px;flex-wrap:wrap;">
    <div style="position:relative;max-width:300px;width:100%">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8890a8;font-size:13px;pointer-events:none"></i>
        <input type="text"
               id="searchMapel"
               placeholder="Cari siswa..."
               style="width:100%;height:34px;padding:0 12px 0 32px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;transition:border-color .15s;"
               onfocus="this.style.borderColor='#a5b4fc'"
               onblur="this.style.borderColor='#e8eaf0'">
    </div>
    <p style="font-size:12px;color:#8890a8;margin:0;white-space:nowrap" id="countLabel">
        0 siswa
    </p>
</div>

{{-- ── Table panel ───────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #e8eaf0;border-radius:12px;overflow:hidden">
    <div class="table-responsive">
        <table style="width:100%;font-size:13px;border-collapse:collapse" id="mapelTable">
            <thead>
                <tr>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:44px;">#</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;">Mata Pelajaran</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:60px;text-align:center;">Icon</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:110px;">Tutor</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:80px;">Status</th>
                    <th style="background:#f8f9fc;color:#8890a8;font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:10px 16px;border-bottom:1px solid #e8eaf0;white-space:nowrap;width:110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $mp)
                    <tr class="mapel-row"
                        data-nama="{{ strtolower($mp->nama) }}"
                        style="transition:background .1s"
                        onmouseover="this.style.background='#fafbff'"
                        onmouseout="this.style.background=''">
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;color:#8890a8;font-size:12px;vertical-align:middle">
                            {{ $loop->iteration }}
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;color:#1a1a2e;vertical-align:middle">
                            <div style="font-weight:500;font-size:13px">{{ $mp->nama }}</div>
                            @if($mp->deskripsi)
                                <div style="font-size:11px;color:#8890a8;margin-top:2px">
                                    {{ Str::limit($mp->deskripsi, 55) }}
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle;text-align:center">
                            <div style="width:36px;height:36px;border-radius:8px;background:#f0f2f8;display:flex;align-items:center;justify-content:center;font-size:18px;line-height:1;margin:0 auto">
                                {{ $mp->icon ?? '📚' }}
                            </div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px">
                                <i class="bi bi-mortarboard" style="font-size:11px"></i>
                                {{ $mp->tutors_count ?? 0 }} tutor
                            </span>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input toggle-status"
                                       type="checkbox"
                                       role="switch"
                                       data-id="{{ $mp->id }}"
                                       {{ $mp->is_active ? 'checked' : '' }}
                                       style="cursor:pointer;width:36px;height:20px;">
                            </div>
                        </td>
                        <td style="padding:12px 16px;border-bottom:1px solid #f0f2f8;vertical-align:middle">
                            <div style="display:flex;gap:6px">
                                <button type="button"
                                        class="tk-action-btn btn-edit-mapel"
                                        data-id="{{ $mp->id }}"
                                        data-nama="{{ $mp->nama }}"
                                        data-icon="{{ $mp->icon }}"
                                        data-deskripsi="{{ $mp->deskripsi }}"
                                        data-is-active="{{ $mp->is_active ? '1' : '0' }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button"
                                        class="tk-action-btn btn-delete-mapel"
                                        data-id="{{ $mp->id }}"
                                        data-nama="{{ $mp->nama }}"
                                        data-tutor-count="{{ $mp->tutors_count ?? 0 }}"
                                        title="Hapus"
                                        style="border-color:#fecaca;background:#fef2f2;color:#991b1b">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="6" style="padding:52px 0;text-align:center;color:#8890a8">
                            <i class="bi bi-book" style="font-size:36px;opacity:.25;display:block;margin-bottom:10px"></i>
                            <p style="font-size:13px;margin:0">Belum ada siswa.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    {{-- @if(isset($mataPelajaran) && $mataPelajaran->hasPages())
        <div style="padding:14px 16px;border-top:1px solid #f0f2f8;display:flex;justify-content:center">
            {{ $mataPelajaran->links() }}
        </div>
    @endif --}}
</div>

{{-- Toast container --}}
<div id="toastContainer"
     style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px">
</div>


{{-- ================================================================
     MODAL TAMBAH / EDIT
================================================================ --}}
<div class="modal fade" id="mapelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-header" style="padding:20px 24px 0;border:none">
                <h5 class="modal-title" id="mapelModalTitle"
                    style="font-size:15px;font-weight:600;color:#1a1a2e;display:flex;align-items:center;gap:8px">
                    <i class="bi bi-plus-circle" style="color:#1e2d6b"></i>
                    Tambah Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:13px"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px">
                <input type="hidden" id="mapelId">
                <input type="hidden" id="mapelMethod" value="POST">

                {{-- Nama --}}
                <div style="margin-bottom:16px">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block"
                           for="mapelNama">
                        Nama Siswa <span style="color:#991b1b">*</span>
                    </label>
                    <input type="text" id="mapelNama"
                           placeholder="cth: Matematika, Fisika, Bahasa Inggris"
                           style="width:100%;padding:8px 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;transition:border-color .15s"
                           onfocus="this.style.borderColor='#a5b4fc'"
                           onblur="this.style.borderColor=this.classList.contains('is-invalid')?'#fca5a5':'#e8eaf0'">
                    <div id="mapelNamaError"
                         style="font-size:11px;color:#991b1b;margin-top:4px;display:none">
                        Nama Siswa wajib diisi.
                    </div>
                </div>

                {{-- Icon --}}
                <div style="margin-bottom:16px">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block"
                           for="mapelIcon">Icon Emoji</label>
                    <div style="display:flex;align-items:center;gap:10px">
                        <input type="text" id="mapelIcon"
                               placeholder="📚"
                               style="width:64px;text-align:center;font-size:18px;padding:6px 8px;border:1px solid #e8eaf0;border-radius:8px;font-family:inherit;outline:none;transition:border-color .15s"
                               onfocus="this.style.borderColor='#a5b4fc'"
                               onblur="this.style.borderColor='#e8eaf0'">
                        <span style="font-size:12px;color:#8890a8">
                            Pilih di bawah atau dari
                            <a href="https://emojipedia.org" target="_blank"
                               style="color:#1e2d6b;text-decoration:none;font-weight:500">emojipedia.org</a>
                        </span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:8px" id="emojiGrid">
                        @foreach(['📐','📏','🔬','🧪','🧬','🌍','📝','🔢','📖','🎨','🎵','💻','🏛️','⚗️','🌱','🧮','🗺️','✏️'] as $emoji)
                            <button type="button"
                                    class="tk-emoji-pick"
                                    data-emoji="{{ $emoji }}"
                                    style="width:32px;height:32px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;cursor:pointer;transition:background .1s,border-color .1s">
                                {{ $emoji }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div style="margin-bottom:16px">
                    <label style="font-size:12px;font-weight:500;color:#4b5574;margin-bottom:6px;display:block"
                           for="mapelDeskripsi">
                        Deskripsi <span style="color:#b0b8cc">(opsional)</span>
                    </label>
                    <textarea id="mapelDeskripsi" rows="2"
                              placeholder="Deskripsi singkat Siswa ini..."
                              style="width:100%;padding:8px 12px;border:1px solid #e8eaf0;border-radius:8px;font-size:13px;font-family:inherit;color:#1a1a2e;background:#fff;outline:none;resize:vertical;min-height:72px;transition:border-color .15s"
                              onfocus="this.style.borderColor='#a5b4fc'"
                              onblur="this.style.borderColor='#e8eaf0'"></textarea>
                </div>

                {{-- Status --}}
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f8f9fc;border-radius:8px;border:1px solid #e8eaf0">
                    <input class="form-check-input mt-0"
                           type="checkbox"
                           id="mapelIsActive"
                           checked
                           style="width:18px;height:18px;border-radius:4px;cursor:pointer">
                    <div>
                        <label class="form-check-label" for="mapelIsActive"
                               style="font-size:13px;font-weight:500;color:#1a1a2e;cursor:pointer">
                            Aktif
                        </label>
                        <div style="font-size:11px;color:#8890a8">Tampil di halaman pencarian tutor</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:0 24px 20px;border:none;gap:8px">
                <button type="button"
                        class="tk-topbar-btn"
                        data-bs-dismiss="modal"
                        style="color:#4b5574">
                    Batal
                </button>
                <button type="button" id="btnSaveMapel"
                        style="display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#1e2d6b;color:#fff;transition:background .15s">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ================================================================
     MODAL KONFIRMASI HAPUS
================================================================ --}}
<div class="modal fade" id="deleteMapelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content" style="border:none;border-radius:14px;box-shadow:0 8px 32px rgba(30,45,107,.12)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i class="bi bi-trash3" style="font-size:22px;color:#991b1b"></i>
                </div>
                <h5 style="font-size:15px;font-weight:600;margin:0 0 6px;color:#1a1a2e">
                    Hapus Siswa?
                </h5>
                <p style="font-size:13px;font-weight:500;color:#4b5574;margin:0 0 4px" id="deleteMapelName"></p>
                <p style="font-size:12px;color:#b45309;margin:0 0 24px;min-height:18px" id="deleteMapelWarning"></p>
                <input type="hidden" id="deleteMapelId">
                <div style="display:flex;gap:8px">
                    <button type="button"
                            class="tk-topbar-btn"
                            style="flex:1;justify-content:center;color:#4b5574"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" id="confirmDeleteMapel"
                            style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:inherit;background:#991b1b;color:#fff;transition:background .15s">
                        <i class="bi bi-trash3"></i> Hapus
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

    const mapelModal  = new bootstrap.Modal(document.getElementById('mapelModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteMapelModal'));

    /* ── Toast helper ──────────────────────────────────────── */
    function showToast(msg, type = 'success') {
        const colors = type === 'success'
            ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0'
            : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca';
        const icon = type === 'success'
            ? '<i class="bi bi-check-circle-fill"></i>'
            : '<i class="bi bi-exclamation-circle-fill"></i>';
        const t = document.createElement('div');
        t.style.cssText = `display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;min-width:240px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1);animation:slideUp .2s ease;${colors}`;
        t.innerHTML = icon + ' ' + msg;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
    }

    /* ── Search filter ─────────────────────────────────────── */
    document.getElementById('searchMapel').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.mapel-row').forEach(row => {
            const match = (row.dataset.nama || '').includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        document.getElementById('countLabel').textContent = visible + ' Siswa';
    });

    /* ── Emoji quick pick ──────────────────────────────────── */
    document.getElementById('emojiGrid').addEventListener('click', function (e) {
        const btn = e.target.closest('.tk-emoji-pick');
        if (!btn) return;
        document.getElementById('mapelIcon').value = btn.dataset.emoji;
        document.querySelectorAll('.tk-emoji-pick').forEach(b => b.style.cssText = 'width:32px;height:32px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;cursor:pointer');
        btn.style.cssText = 'width:32px;height:32px;border-radius:6px;border:1px solid #1e2d6b;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:16px;cursor:pointer';
    });

    /* ── Reset form ────────────────────────────────────────── */
    function resetForm() {
        ['mapelId','mapelNama','mapelIcon','mapelDeskripsi'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('mapelIsActive').checked = true;
        document.getElementById('mapelNama').style.borderColor = '#e8eaf0';
        document.getElementById('mapelNamaError').style.display = 'none';
        document.querySelectorAll('.tk-emoji-pick').forEach(b => b.style.cssText = 'width:32px;height:32px;border-radius:6px;border:1px solid #e8eaf0;background:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;cursor:pointer');
    }

    /* ── Open: Tambah ──────────────────────────────────────── */
    document.getElementById('btnTambahMapel').addEventListener('click', function () {
        resetForm();
        document.getElementById('mapelMethod').value = 'POST';
        document.getElementById('mapelModalTitle').innerHTML = '<i class="bi bi-plus-circle" style="color:#1e2d6b"></i> Tambah Siswa';
        mapelModal.show();
    });

    /* ── Open: Edit ────────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-mapel');
        if (!btn) return;
        resetForm();
        document.getElementById('mapelId').value = btn.dataset.id;
        document.getElementById('mapelMethod').value = 'PUT';
        document.getElementById('mapelNama').value = btn.dataset.nama;
        document.getElementById('mapelIcon').value = btn.dataset.icon || '';
        document.getElementById('mapelDeskripsi').value = btn.dataset.deskripsi || '';
        document.getElementById('mapelIsActive').checked = btn.dataset.isActive === '1';
        const icon = btn.dataset.icon;
        document.querySelectorAll('.tk-emoji-pick').forEach(b => {
            if (b.dataset.emoji === icon) b.style.cssText = 'width:32px;height:32px;border-radius:6px;border:1px solid #1e2d6b;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:16px;cursor:pointer';
        });
        document.getElementById('mapelModalTitle').innerHTML = '<i class="bi bi-pencil" style="color:#1e2d6b"></i> Edit Siswa';
        mapelModal.show();
    });

    /* ── Save ──────────────────────────────────────────────── */
    document.getElementById('btnSaveMapel').addEventListener('click', function () {
        const namaEl = document.getElementById('mapelNama');
        const nama = namaEl.value.trim();
        if (!nama) {
            namaEl.style.borderColor = '#fca5a5';
            document.getElementById('mapelNamaError').style.display = 'block';
            namaEl.focus();
            return;
        }
        namaEl.style.borderColor = '#e8eaf0';
        document.getElementById('mapelNamaError').style.display = 'none';

        const id     = document.getElementById('mapelId').value;
        const method = document.getElementById('mapelMethod').value;
        const url    = id ? `/admin/mata-pelajaran/${id}` : '/admin/mata-pelajaran';
        const btn    = this;

        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menyimpan...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                _method:   method,
                nama,
                icon:      document.getElementById('mapelIcon').value.trim(),
                deskripsi: document.getElementById('mapelDeskripsi').value.trim(),
                is_active: document.getElementById('mapelIsActive').checked ? 1 : 0,
            })
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) throw data;
            mapelModal.hide();
            showToast(method === 'POST' ? 'Siswa berhasil ditambahkan.' : 'Siswa berhasil diperbarui.');
            setTimeout(() => location.reload(), 700);
        })
        .catch(err => {
            const errors = err?.errors;
            if (errors?.nama) {
                document.getElementById('mapelNama').style.borderColor = '#fca5a5';
                document.getElementById('mapelNamaError').textContent = errors.nama[0];
                document.getElementById('mapelNamaError').style.display = 'block';
            }
            showToast(err?.message || 'Gagal menyimpan data.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan';
        });
    });

    /* ── Toggle status ─────────────────────────────────────── */
    document.addEventListener('change', function (e) {
        const toggle = e.target.closest('.toggle-status');
        if (!toggle) return;
        const id = toggle.dataset.id;
        const isActive = toggle.checked ? 1 : 0;

        fetch(`/admin/mata-pelajaran/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ _method: 'PATCH', is_active: isActive })
        })
        .then(r => { if (!r.ok) throw r; })
        .then(() => showToast(isActive ? 'Siswa diaktifkan.' : 'Siswa dinonaktifkan.'))
        .catch(() => {
            toggle.checked = !toggle.checked;
            showToast('Gagal mengubah status.', 'error');
        });
    });

    /* ── Open: Delete confirm ──────────────────────────────── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-mapel');
        if (!btn) return;
        const count = parseInt(btn.dataset.tutorCount) || 0;
        document.getElementById('deleteMapelId').value = btn.dataset.id;
        document.getElementById('deleteMapelName').textContent = `"${btn.dataset.nama}"`;
        document.getElementById('deleteMapelWarning').textContent = count > 0
            ? `⚠ Ada ${count} tutor yang mengajar Siswa ini. Relasi akan dilepas.`
            : '';
        deleteModal.show();
    });

    /* ── Confirm delete ────────────────────────────────────── */
    document.getElementById('confirmDeleteMapel').addEventListener('click', function () {
        const id  = document.getElementById('deleteMapelId').value;
        const btn = this;

        btn.disabled = true;
        btn.innerHTML = '<span style="width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;display:inline-block;vertical-align:middle"></span> Menghapus...';

        fetch(`/admin/mata-pelajaran/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) throw data;
            deleteModal.hide();
            showToast('Siswa berhasil dihapus.');
            setTimeout(() => location.reload(), 700);
        })
        .catch(err => showToast(err?.message || 'Gagal menghapus data.', 'error'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash3"></i> Hapus';
        });
    });

});
</script>

<style>
    .form-check-input:checked { background-color:#1e2d6b; border-color:#1e2d6b; }
    .form-check-input:focus   { box-shadow:0 0 0 3px rgba(30,45,107,.12); }
    @keyframes slideUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes spin    { to { transform:rotate(360deg); } }
    #btnSaveMapel:hover:not(:disabled)    { background:#162356 !important; }
    #confirmDeleteMapel:hover:not(:disabled) { background:#7f1d1d !important; }
</style>
@endpush

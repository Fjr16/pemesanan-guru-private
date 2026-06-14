@extends('layouts.guest')

@section('title', 'Daftar — TutorKu')

@section('auth-content')

<div class="mb-4">
    <h3 class="fw-600 mb-1" style="font-size:1.375rem;color:#0f172a;" id="formTitle">Buat akun baru</h3>
    <p class="text-muted" style="font-size:.9rem;" id="formSubtitle">Pilih peran Anda untuk memulai.</p>
</div>

{{-- ROLE TABS --}}
<div class="tk-role-tabs mb-4">
    <button class="tk-role-tab {{ request('role') === 'tutor' ? '' : 'active' }}"
            data-role="siswa" type="button">
        <i class="bi bi-person-fill me-1"></i> Siswa
    </button>
    <button class="tk-role-tab {{ request('role') === 'tutor' ? 'active' : '' }}"
            data-role="tutor" type="button">
        <i class="bi bi-mortarboard-fill me-1"></i> Tutor
    </button>
</div>

{{-- ============================================================
     STEP INDICATOR (tutor only)
============================================================ --}}
<div id="stepIndicator" class="tk-steps mb-4" style="{{ request('role') === 'tutor' ? '' : 'display:none;' }}">
    <div class="tk-step">
        <div class="tk-step-num done" id="step1Num">1</div>
        <div class="tk-step-label">Akun</div>
    </div>
    <div class="tk-step-line" id="line12"></div>
    <div class="tk-step">
        <div class="tk-step-num todo" id="step2Num">2</div>
        <div class="tk-step-label">Profil</div>
    </div>
    <div class="tk-step-line" id="line23"></div>
    <div class="tk-step">
        <div class="tk-step-num todo" id="step3Num">3</div>
        <div class="tk-step-label">Jadwal</div>
    </div>
    <div class="tk-step-line" id="line34"></div>
    <div class="tk-step">
        <div class="tk-step-num todo" id="step4Num">4</div>
        <div class="tk-step-label">Selesai</div>
    </div>
</div>

{{-- ============================================================
     FORM UTAMA
============================================================ --}}
<form method="POST" action="{{ route('register') }}" data-loading id="registerForm"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="role" id="roleInput"
           value="{{ request('role') === 'tutor' ? 'tutor' : 'siswa' }}">

    {{-- ======================================================
         STEP 1 / SISWA: Data Akun
    ====================================================== --}}
    <div id="step1">

        {{-- Nama lengkap --}}
        <div class="mb-3">
            <label class="tk-form-label" for="name">Nama Lengkap</label>
            <div class="tk-input-group">
                <i class="bi bi-person tk-input-icon"></i>
                <input type="text" id="name" name="name"
                       class="tk-form-control @error('name') is-invalid @enderror"
                       placeholder="Nama sesuai identitas"
                       value="{{ old('name') }}" required>
            </div>
            @error('name')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>
        <div class="row mb-3">
            <div class="col-7">
                <label class="tk-form-label" for="name">Tempat Lahir</label>
                <div class="tk-input-group">
                    <i class="bi bi-geo-alt tk-input-icon"></i>
                    <input type="text" id="tempat_lhr" name="tempat_lhr"
                           class="tk-form-control @error('tempat_lhr') is-invalid @enderror"
                           placeholder="Nama tempat lahir"
                           value="{{ old('tempat_lhr') }}" required>
                </div>
            </div>
            <div class="col-5">
                <label class="tk-form-label" for="name">Tanggal Lahir</label>
                <div class="tk-input-group">
                    <i class="bi bi-calendar tk-input-icon"></i>
                    <input type="date" id="tanggal_lhr" name="tanggal_lhr"
                           class="tk-form-control tanggal-input @error('tanggal_lhr') is-invalid @enderror"
                           placeholder="Tanggal lahir"
                           value="{{ old('tanggal_lhr') }}" required>
                </div>
            </div>
            @if($errors->has('tempat_lhr'))
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">
                    {{ $errors->first('tempat_lhr') }}
                </div>
            @endif
            @if($errors->has('tanggal_lhr'))
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">
                    {{ $errors->first('tanggal_lhr') }}
                </div>
            @endif
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label class="tk-form-label" for="email">Domisili</label>
            <div class="tk-input-group">
                <i class="bi bi-house-door tk-input-icon"></i>
                <input type="text" id="alamat" name="alamat"
                       class="tk-form-control @error('alamat') is-invalid @enderror"
                       placeholder="Alamat sesuai domisili"
                       value="{{ old('alamat') }}" required>
            </div>
            @error('alamat')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>
        {{-- Email --}}
        <div class="mb-3">
            <label class="tk-form-label" for="email">Alamat Email</label>
            <div class="tk-input-group">
                <i class="bi bi-envelope tk-input-icon"></i>
                <input type="email" id="email" name="email"
                       class="tk-form-control @error('email') is-invalid @enderror"
                       placeholder="nama@email.com"
                       value="{{ old('email') }}" required>
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- No. HP --}}
        <div class="mb-3">
            <label class="tk-form-label" for="phone">No. WhatsApp</label>
            <div class="tk-input-group">
                <i class="bi bi-whatsapp tk-input-icon"></i>
                <input type="tel" id="phone" name="phone"
                       class="tk-form-control @error('phone') is-invalid @enderror"
                       placeholder="08xxxxxxxxxx"
                       value="{{ old('phone') }}" required>
            </div>
            @error('phone')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="tk-form-label" for="password">Password</label>
            <div class="tk-input-group">
                <i class="bi bi-lock tk-input-icon"></i>
                <input type="password" id="password" name="password"
                       class="tk-form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter" required>
                <button type="button" class="tk-password-toggle" tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label class="tk-form-label" for="password_confirmation">Konfirmasi Password</label>
            <div class="tk-input-group">
                <i class="bi bi-lock-fill tk-input-icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="tk-form-control" placeholder="Ulangi password" required>
                <button type="button" class="tk-password-toggle" tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        {{-- Password strength indicator --}}
        <div class="mb-4" id="passwordStrength" style="display:none;">
            <div class="d-flex gap-1 mb-1">
                <div class="flex-1 rounded" id="ps1" style="height:4px;background:#e2e8f0;transition:background .3s;"></div>
                <div class="flex-1 rounded" id="ps2" style="height:4px;background:#e2e8f0;transition:background .3s;"></div>
                <div class="flex-1 rounded" id="ps3" style="height:4px;background:#e2e8f0;transition:background .3s;"></div>
                <div class="flex-1 rounded" id="ps4" style="height:4px;background:#e2e8f0;transition:background .3s;"></div>
            </div>
            <span id="psLabel" class="text-muted" style="font-size:.75rem;"></span>
        </div>

        {{-- Syarat & Ketentuan --}}
        <div class="mb-4 d-flex align-items-start gap-2">
            <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" id="terms" name="terms"
                   style="width:16px;height:16px;border-radius:4px;" required>
            <label class="form-check-label text-muted" for="terms" style="font-size:.8375rem;">
                Saya setuju dengan
                <a href="#" class="tk-link">Syarat & Ketentuan</a> dan
                <a href="#" class="tk-link">Kebijakan Privasi</a> TutorKu.
            </label>
        </div>

        {{-- SISWA: langsung submit. TUTOR: tombol "Lanjut" --}}
        <div id="step1BtnSiswa">
            <button type="submit" class="tk-btn-primary" id="submitSiswa">
                <i class="bi bi-person-check"></i> Buat Akun Siswa
            </button>
        </div>

        <div id="step1BtnTutor" style="display:none;">
            <button type="button" class="tk-btn-primary" id="nextToStep2">
                Lanjut: Profil Keahlian <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>

    </div>{{-- /step1 --}}


    {{-- ======================================================
         STEP 2 (Tutor): Profil Keahlian
    ====================================================== --}}
    <div id="step2" style="display:none;">

        {{-- Mata pelajaran --}}
        <div class="mb-3">
            <label class="tk-form-label">Mata Pelajaran yang Diajarkan</label>
            <div id="subjectCheckboxes" class="d-flex flex-wrap gap-2">
                {{-- Akan di-populate via AJAX --}}
                <div class="skeleton skeleton-text" style="width:80px;height:28px;border-radius:100px;"></div>
                <div class="skeleton skeleton-text" style="width:60px;height:28px;border-radius:100px;"></div>
                <div class="skeleton skeleton-text" style="width:90px;height:28px;border-radius:100px;"></div>
            </div>
            @error('subjects')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Pengalaman --}}
        <div class="mb-3">
            <label class="tk-form-label" for="experience_years">Pengalaman Mengajar</label>
            <div class="tk-input-group">
                <i class="bi bi-briefcase tk-input-icon"></i>
                <select id="experience_years" name="experience_years" class="tk-form-control">
                    <option value="">Pilih pengalaman...</option>
                    <option value="1">Kurang dari 1 tahun</option>
                    <option value="2">1–2 tahun</option>
                    <option value="3">3–5 tahun</option>
                    <option value="5">5+ tahun</option>
                    <option value="10">10+ tahun</option>
                </select>
            </div>
        </div>

        {{-- Tarif per jam --}}
        <div class="mb-3">
            <label class="tk-form-label" for="hourly_rate">Tarif per Jam (Rp)</label>
            <div class="tk-input-group">
                <i class="bi bi-currency-exchange tk-input-icon"></i>
                <input type="number" id="hourly_rate" name="hourly_rate"
                       class="tk-form-control @error('hourly_rate') is-invalid @enderror"
                       placeholder="cth: 75000" min="10000" step="5000"
                       value="{{ old('hourly_rate') }}">
            </div>
            <div class="text-muted mt-1" style="font-size:.75rem;" id="tarifPreview"></div>
            @error('hourly_rate')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jenjang pendidikan siswa --}}
        <div class="mb-3">
            <label class="tk-form-label" for="education_level">Target Jenjang Siswa</label>
            <div class="tk-input-group">
                <i class="bi bi-mortarboard tk-input-icon"></i>
                <select id="education_level" name="education_level" class="tk-form-control">
                    <option value="">Pilih jenjang...</option>
                    <option value="sd">SD</option>
                    <option value="smp">SMP</option>
                    <option value="sma">SMA / SMK</option>
                    <option value="kuliah">Perguruan Tinggi</option>
                    <option value="semua">Semua Jenjang</option>
                </select>
            </div>
        </div>

        {{-- Bio --}}
        <div class="mb-4">
            <label class="tk-form-label" for="bio">Tentang Anda</label>
            <textarea id="bio" name="bio" rows="3"
                      class="tk-form-control @error('bio') is-invalid @enderror"
                      placeholder="Ceritakan pengalaman dan keunggulan Anda sebagai tutor..."
                      style="resize:vertical;">{{ old('bio') }}</textarea>
            <div class="d-flex justify-content-end mt-1">
                <span class="text-muted" style="font-size:.75rem;" id="bioCount">0/300 karakter</span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="tk-btn-outline-primary flex-shrink-0" id="backToStep1"
                    style="padding:.6875rem 1rem;">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button type="button" class="tk-btn-primary" id="nextToStep3">
                Lanjut: Atur Jadwal <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>

    </div>{{-- /step2 --}}


    {{-- ======================================================
         STEP 3 (Tutor): Atur Jadwal Ketersediaan
    ====================================================== --}}
    <div id="step3" style="display:none;">

        <p class="text-muted mb-3" style="font-size:.875rem;">
            Pilih hari dan jam Anda tersedia untuk mengajar. Jadwal ini bisa diubah kapan saja di dashboard.
        </p>

        <div id="scheduleBuilder">
            @php
                $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                $slots = ['07:00','08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'];
            @endphp

            @foreach($days as $i => $day)
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input class="form-check-input day-toggle mt-0" type="checkbox"
                           id="day_{{ $i }}" data-day="{{ $i }}"
                           style="width:16px;height:16px;border-radius:4px;">
                    <label class="fw-500" style="font-size:.875rem;cursor:pointer;" for="day_{{ $i }}">
                        {{ $day }}
                    </label>
                </div>
                <div class="day-slots ms-4 gap-2 flex-wrap" data-day="{{ $i }}" style="display:none;">
                    @foreach($slots as $slot)
                        <label class="tk-slot-picker" for="slot_{{ $i }}_{{ str_replace(':','', $slot) }}">
                            <input type="checkbox" name="schedules[{{ $i }}][]"
                                   value="{{ $slot }}"
                                   id="slot_{{ $i }}_{{ str_replace(':','', $slot) }}"
                                   class="d-none slot-checkbox">
                            <span class="tk-slot sf">{{ $slot }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="button" class="tk-btn-outline-primary flex-shrink-0" id="backToStep2"
                    style="padding:.6875rem 1rem;">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button type="submit" class="tk-btn-primary" id="submitTutor">
                <i class="bi bi-check-circle"></i> Selesai & Daftar
            </button>
        </div>

    </div>{{-- /step3 --}}

</form>

{{-- Link ke login --}}
<p class="text-center text-muted mt-4 mb-0" style="font-size:.875rem;">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="tk-link">Masuk di sini</a>
</p>

@endsection


@push('styles')
<style>
/* Slot picker */
.tk-slot-picker { cursor: pointer; display: inline-block; }
.tk-slot-picker input:checked + .tk-slot { background: var(--tk-primary) !important; color: #fff !important; border-color: var(--tk-primary) !important; }
.tk-slot { display: inline-flex; align-items: center; justify-content: center; padding: .25rem .625rem; border-radius: .375rem; font-size: .75rem; font-weight: 500; border: 1px solid var(--tk-success-border); background: var(--tk-success-bg); color: var(--tk-success-text); transition: all .15s; cursor: pointer; }
.tk-slot:hover { background: #dcfce7; }

/* Subject pill checkboxes */
.subject-pill { display: inline-flex; align-items: center; gap: .375rem; padding: .3125rem .875rem; border-radius: 100px; border: 1px solid var(--tk-border); background: var(--tk-surface); color: var(--tk-text-muted); font-size: .8125rem; cursor: pointer; transition: all .15s; }
.subject-pill.selected { background: var(--tk-primary-50); border-color: var(--tk-primary-100); color: var(--tk-primary); font-weight: 500; }
</style>
@endpush


@push('scripts')
<script>
$(document).ready(function () {

    let currentRole = '{{ request("role") === "tutor" ? "tutor" : "siswa" }}';
    let currentStep = 1;

    // ── Inisialisasi berdasarkan URL param ──────────────────────
    if (currentRole === 'tutor') activateRole('tutor');

    // ── Role tab click ──────────────────────────────────────────
    $('.tk-role-tab').on('click', function () {
        currentRole = $(this).data('role');
        activateRole(currentRole);
    });

    function activateRole(role) {
        $('#roleInput').val(role);
        currentRole = role;

        $('.tk-role-tab').removeClass('active');
        $(`.tk-role-tab[data-role="${role}"]`).addClass('active');

        if (role === 'tutor') {
            $('#formTitle').text('Daftar sebagai Tutor');
            $('#formSubtitle').text('Langkah 1 dari 3 — Data akun Anda.');
            $('#stepIndicator').slideDown(200);
            $('#step1BtnSiswa').hide();
            $('#step1BtnTutor').show();
        } else {
            $('#formTitle').text('Buat akun baru');
            $('#formSubtitle').text('Isi data di bawah untuk membuat akun siswa.');
            $('#stepIndicator').slideUp(200);
            $('#step1BtnSiswa').show();
            $('#step1BtnTutor').hide();
        }

        goToStep(1);
    }

    // ── Navigasi step ───────────────────────────────────────────
    function goToStep(step) {
        ['step1','step2','step3'].forEach(s => $('#' + s).hide());
        $('#step' + step).show();
        currentStep = step;
        updateStepIndicator(step);
    }

    function updateStepIndicator(step) {
        for (let i = 1; i <= 4; i++) {
            const $num  = $('#step' + i + 'Num');
            const $line = $('#line' + i + (i+1));

            if (i < step) {
                $num.removeClass('current todo').addClass('done').html('<i class="bi bi-check"></i>');
                if ($line.length) $line.addClass('done');
            } else if (i === step) {
                $num.removeClass('done todo').addClass('current').text(i);
            } else {
                $num.removeClass('done current').addClass('todo').text(i);
                if ($line.length) $line.removeClass('done');
            }
        }
    }

    $('#nextToStep2').on('click', function () {
        if (!validateStep1()) return;
        goToStep(2);
        loadSubjects();
    });

    $('#backToStep1').on('click', function () { goToStep(1); });

    $('#nextToStep3').on('click', function () {
        if (!validateStep2()) return;
        goToStep(3);
    });

    $('#backToStep2').on('click', function () { goToStep(2); });

    // ── Validasi step 1 ─────────────────────────────────────────
    function validateStep1() {
        const name  = $('#name').val().trim();
        const email = $('#email').val().trim();
        const phone = $('#phone').val().trim();
        const pass  = $('#password').val();
        const conf  = $('#password_confirmation').val();
        const terms = $('#terms').is(':checked');

        if (!name)  { flashField('#name',  'Nama wajib diisi.'); return false; }
        if (!email) { flashField('#email', 'Email wajib diisi.'); return false; }
        if (!phone) { flashField('#phone', 'No. WhatsApp wajib diisi.'); return false; }
        if (pass.length < 8)  { flashField('#password', 'Password minimal 8 karakter.'); return false; }
        if (pass !== conf)    { flashField('#password_confirmation', 'Password tidak cocok.'); return false; }
        if (!terms) { showToast('Setujui syarat & ketentuan terlebih dahulu.', 'warning'); return false; }
        return true;
    }

    // ── Validasi step 2 ─────────────────────────────────────────
    function validateStep2() {
        const subjects = $('input[name^="subjects"]:checked').length;
        const rate = $('#hourly_rate').val();
        if (subjects === 0) { showToast('Pilih minimal 1 mata pelajaran.', 'warning'); return false; }
        if (!rate || parseInt(rate) < 10000) { flashField('#hourly_rate', 'Masukkan tarif minimal Rp 10.000.'); return false; }
        return true;
    }

    function flashField(selector, msg) {
        const $el = $(selector);
        $el.addClass('is-invalid').focus();
        $el.siblings('.invalid-feedback').remove();
        $el.after(`<div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">${msg}</div>`);
        setTimeout(() => $el.removeClass('is-invalid'), 3000);
    }

    // ── Load mata pelajaran via AJAX ─────────────────────────────
    function loadSubjects() {
        if ($('#subjectCheckboxes').find('.subject-pill').length) return; // sudah loaded

        $.ajax({
            url: '/api/mata-pelajaran',
            method: 'GET',
            success: function (res) {
                const $box = $('#subjectCheckboxes').empty();
                (res.data || []).forEach(function (mp) {
                    $box.append(`
                        <label class="subject-pill" data-id="${mp.id}">
                            <input type="checkbox" name="subjects[]" value="${mp.id}" class="d-none">
                            ${mp.nama}
                        </label>
                    `);
                });
            },
            error: function () {
                $('#subjectCheckboxes').html('<p class="text-muted small">Gagal memuat mata pelajaran.</p>');
            }
        });
    }

    // Subject pill toggle
    $(document).on('click', '.subject-pill', function () {
        const $input = $(this).find('input');
        const checked = !$input.prop('checked');
        $input.prop('checked', checked);
        $(this).toggleClass('selected', checked);
    });

    // ── Hari toggle → tampilkan slot ────────────────────────────
    $(document).on('change', '.day-toggle', function () {
        const day = $(this).data('day');
        const $slots = $(`.day-slots[data-day="${day}"]`);
        if ($(this).is(':checked')) {
            $slots.css('display', 'flex');
        } else {
            $slots.hide();
            $slots.find('.slot-checkbox').prop('checked', false);
            $slots.find('.tk-slot').removeClass('selected');
        }
    });

    // ── Bio character counter ────────────────────────────────────
    $('#bio').on('input', function () {
        const len = $(this).val().length;
        $('#bioCount').text(len + '/300 karakter');
        if (len > 300) $(this).val($(this).val().slice(0, 300));
    });

    // ── Tarif preview ────────────────────────────────────────────
    $('#hourly_rate').on('input', function () {
        const val = parseInt($(this).val());
        if (val > 0) {
            $('#tarifPreview').html(
                `<i class="bi bi-info-circle me-1"></i>Tarif yang akan ditampilkan: <strong>Rp ${val.toLocaleString('id-ID')}/jam</strong>`
            );
        } else {
            $('#tarifPreview').text('');
        }
    });

    // ── Password strength ────────────────────────────────────────
    $('#password').on('input', function () {
        const val = $(this).val();
        if (!val) { $('#passwordStrength').hide(); return; }
        $('#passwordStrength').show();

        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['#ef4444','#f59e0b','#3b82f6','#22c55e'];
        const labels = ['Lemah','Cukup','Kuat','Sangat kuat'];

        for (let i = 1; i <= 4; i++) {
            $('#ps' + i).css('background', i <= score ? colors[score-1] : '#e2e8f0');
        }
        $('#psLabel').text(labels[score-1] || '').css('color', colors[score-1]);
    });

});
</script>
@endpush

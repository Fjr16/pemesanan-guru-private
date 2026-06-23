@extends('layouts.guest')

@section('title', 'Daftar — TutorKu')

@section('auth-content')

@php
    $hasErrors = $errors->any();
    $oldRole = old('role');
    $isTutor = $oldRole === 'tutor' || (!$hasErrors && request('role') === 'tutor');

    // Tentukan step tutor saat validation error
    $tutorStep = 1;
    if ($hasErrors && $isTutor) {
        $step3Fields = ['schedules', 'riwayat_pendidikan', 'pengalaman'];
        $tutorStep = 2;
        foreach ($step3Fields as $f) {
            if ($errors->has($f) || $errors->has($f . '.*')) { $tutorStep = 3; break; }
        }
    }

    $oldSubjectTingkatan = old('subject_tingkatan', []);
    $oldPendidikan = old('riwayat_pendidikan', []);
    $oldPengalaman = old('pengalaman', []);
@endphp

<div class="mb-4">
    <h3 class="fw-600 mb-1" style="font-size:1.375rem;color:#0f172a;" id="formTitle">
        {{ $isTutor ? 'Daftar sebagai Tutor' : 'Buat akun baru' }}
    </h3>
    <p class="text-muted" style="font-size:.9rem;" id="formSubtitle">
        {{ $isTutor ? 'Langkah 1 dari 3 — Data akun Anda.' : 'Pilih peran Anda untuk memulai.' }}
    </p>
</div>

{{-- ROLE TABS --}}
<div class="tk-role-tabs mb-4">
    <button class="tk-role-tab {{ $isTutor ? '' : 'active' }}"
            data-role="siswa" type="button">
        <i class="bi bi-person-fill me-1"></i> Siswa
    </button>
    <button class="tk-role-tab {{ $isTutor ? 'active' : '' }}"
            data-role="tutor" type="button">
        <i class="bi bi-mortarboard-fill me-1"></i> Tutor
    </button>
</div>

{{-- STEP INDICATOR (tutor only) --}}
<div id="stepIndicator" class="tk-steps mb-4" style="{{ $isTutor ? '' : 'display:none;' }}">
    <div class="tk-step">
        <div class="tk-step-num {{ $tutorStep >= 1 ? 'done' : 'todo' }}" id="step1Num">{{ $tutorStep > 1 ? '' : '1' }}@if($tutorStep > 1)<i class="bi bi-check"></i>@endif</div>
        <div class="tk-step-label">Akun</div>
    </div>
    <div class="tk-step-line {{ $tutorStep > 1 ? 'done' : '' }}" id="line12"></div>
    <div class="tk-step">
        <div class="tk-step-num {{ $tutorStep == 2 ? 'current' : ($tutorStep > 2 ? 'done' : 'todo') }}" id="step2Num">{{ $tutorStep > 2 ? '' : '2' }}@if($tutorStep > 2)<i class="bi bi-check"></i>@endif</div>
        <div class="tk-step-label">Profil</div>
    </div>
    <div class="tk-step-line {{ $tutorStep > 2 ? 'done' : '' }}" id="line23"></div>
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

<form method="POST" action="{{ route('register') }}" data-loading id="registerForm"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="role" id="roleInput"
           value="{{ $isTutor ? 'tutor' : 'siswa' }}">

    {{-- Error summary — selalu terlihat --}}
    @if($hasErrors)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <i class="bi bi-exclamation-circle-fill" style="color:#991b1b;font-size:14px;"></i>
                <span style="font-size:13px;font-weight:600;color:#991b1b;">Terdapat kesalahan pada form:</span>
            </div>
            <ul style="margin:0;padding-left:20px;font-size:12px;color:#991b1b;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom:2px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ======================================================
         STEP 1: Data Akun
    ====================================================== --}}
    <div id="step1" style="{{ ($isTutor && $tutorStep > 1) ? 'display:none;' : '' }}">

        {{-- Username --}}
        <div class="mb-3">
            <label class="tk-form-label" for="username">Username <span class="req">*</span></label>
            <div class="tk-input-group">
                <i class="bi bi-person tk-input-icon"></i>
                <input type="text" id="username" name="username"
                       class="tk-form-control @error('username') is-invalid @enderror"
                       placeholder="Username untuk login"
                       value="{{ old('username') }}" required>
            </div>
            @error('username')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="tk-form-label" for="email">Alamat Email <span class="req">*</span></label>
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
            <label class="tk-form-label" for="no_hp">No. WhatsApp <span class="req">*</span></label>
            <div class="tk-input-group">
                <i class="bi bi-whatsapp tk-input-icon"></i>
                <input type="tel" id="no_hp" name="no_hp"
                       class="tk-form-control @error('no_hp') is-invalid @enderror"
                       placeholder="08xxxxxxxxxx"
                       value="{{ old('no_hp') }}" required>
            </div>
            @error('no_hp')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="tk-form-label" for="password">Password <span class="req">*</span></label>
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
        <div class="mb-3">
            <label class="tk-form-label" for="password_confirmation">Konfirmasi Password <span class="req">*</span></label>
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

        {{-- SISWA: Data Diri --}}
        <div id="siswaFields" style="{{ $isTutor ? 'display:none;' : '' }}">
            <hr style="border-color:#e8eaf0;margin:16px 0;">
            <p style="font-size:12px;color:#8890a8;font-weight:500;text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px;">Data Diri</p>

            <div class="mb-3">
                <label class="tk-form-label" for="siswa_name">Nama Lengkap <span class="req">*</span></label>
                <div class="tk-input-group">
                    <i class="bi bi-person-badge tk-input-icon"></i>
                    <input type="text" id="siswa_name" name="siswa_name"
                           class="tk-form-control @error('siswa_name') is-invalid @enderror"
                           placeholder="Nama sesuai identitas"
                           value="{{ old('siswa_name') }}" {{ $isTutor ? '' : 'required' }}>
                </div>
                @error('siswa_name')
                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-7">
                    <label class="tk-form-label" for="tempat_lhr">Tempat Lahir <span class="req">*</span></label>
                    <div class="tk-input-group">
                        <i class="bi bi-geo-alt tk-input-icon"></i>
                        <input type="text" id="tempat_lhr" name="tempat_lhr"
                               class="tk-form-control @error('tempat_lhr') is-invalid @enderror"
                               placeholder="Kota kelahiran"
                               value="{{ old('tempat_lhr') }}" {{ $isTutor ? '' : 'required' }}>
                    </div>
                    @error('tempat_lhr')
                        <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-5">
                    <label class="tk-form-label" for="siswa_tanggal_lhr">Tanggal Lahir <span class="req">*</span></label>
                    <div class="tk-input-group">
                        <i class="bi bi-calendar tk-input-icon"></i>
                        <input type="date" id="siswa_tanggal_lhr" name="siswa_tanggal_lhr"
                               class="tk-form-control tanggal-input @error('siswa_tanggal_lhr') is-invalid @enderror"
                               value="{{ old('siswa_tanggal_lhr') }}" {{ $isTutor ? '' : 'required' }}>
                    </div>
                    @error('siswa_tanggal_lhr')
                        <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="tk-form-label" for="alamat">Alamat Domisili <span class="req">*</span></label>
                <div class="tk-input-group">
                    <i class="bi bi-house-door tk-input-icon"></i>
                    <input type="text" id="alamat" name="alamat"
                           class="tk-form-control @error('alamat') is-invalid @enderror"
                           placeholder="Alamat lengkap domisili"
                           value="{{ old('alamat') }}" {{ $isTutor ? '' : 'required' }}>
                </div>
                @error('alamat')
                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Syarat & Ketentuan --}}
        <div class="mb-4 d-flex align-items-start gap-2">
            <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" id="terms" name="terms"
                   style="width:16px;height:16px;border-radius:4px;" {{ old('terms') ? 'checked' : '' }} required>
            <label class="form-check-label text-muted" for="terms" style="font-size:.8375rem;">
                Saya setuju dengan
                <a href="#" class="tk-link">Syarat & Ketentuan</a> dan
                <a href="#" class="tk-link">Kebijakan Privasi</a> TutorKu.
            </label>
        </div>

        <div id="step1BtnSiswa" style="{{ $isTutor ? 'display:none;' : '' }}">
            <button type="submit" class="tk-btn-primary" id="submitSiswa">
                <i class="bi bi-person-check"></i> Buat Akun Siswa
            </button>
        </div>

        <div id="step1BtnTutor" style="{{ $isTutor ? '' : 'display:none;' }}">
            <button type="button" class="tk-btn-primary" id="nextToStep2">
                Lanjut: Profil Keahlian <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>

    </div>{{-- /step1 --}}


    {{-- ======================================================
         STEP 2 (Tutor): Profil Keahlian
    ====================================================== --}}
    <div id="step2" style="{{ ($isTutor && $tutorStep == 2) ? '' : 'display:none;' }}">

        {{-- Nama Lengkap --}}
        <div class="mb-3">
            <label class="tk-form-label" for="tutor_name">Nama Lengkap <span class="req">*</span></label>
            <div class="tk-input-group">
                <i class="bi bi-person-badge tk-input-icon"></i>
                <input type="text" id="tutor_name" name="tutor_name"
                       class="tk-form-control @error('tutor_name') is-invalid @enderror"
                       placeholder="Nama sesuai identitas"
                       value="{{ old('tutor_name') }}">
            </div>
            @error('tutor_name')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jenis Kelamin + Tanggal Lahir --}}
        <div class="row mb-3">
            <div class="col-6">
                <label class="tk-form-label" for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                <div class="tk-input-group">
                    <i class="bi bi-gender-ambiguous tk-input-icon"></i>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="tk-form-control @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">Pilih...</option>
                        <option value="Pria" {{ old('jenis_kelamin') === 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('jenis_kelamin') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>
                @error('jenis_kelamin')
                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="tk-form-label" for="tanggal_lhr_tutor">Tanggal Lahir <span class="req">*</span></label>
                <div class="tk-input-group">
                    <i class="bi bi-calendar tk-input-icon"></i>
                <input type="date" id="tanggal_lhr_tutor" name="tutor_tanggal_lhr"
                       class="tk-form-control tanggal-input @error('tutor_tanggal_lhr') is-invalid @enderror"
                       value="{{ old('tutor_tanggal_lhr') }}">
                </div>
                @error('tanggal_lhr')
                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Domisili --}}
        <div class="mb-3">
            <label class="tk-form-label" for="domisili">Domisili <span class="req">*</span></label>
            <div class="tk-input-group">
                <i class="bi bi-geo-alt tk-input-icon"></i>
                <input type="text" id="domisili" name="domisili"
                       class="tk-form-control @error('domisili') is-invalid @enderror"
                       placeholder="Kota domisili saat ini"
                       value="{{ old('domisili') }}">
            </div>
            @error('domisili')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Pekerjaan --}}
        <div class="mb-3">
            <label class="tk-form-label" for="job">Pekerjaan <span class="req">*</span></label>
            <div class="tk-input-group">
                <i class="bi bi-briefcase tk-input-icon"></i>
                <input type="text" id="job" name="job"
                       class="tk-form-control @error('job') is-invalid @enderror"
                       placeholder="cth: Guru, Dosen, Mahasiswa"
                       value="{{ old('job') }}">
            </div>
            @error('job')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mata Pelajaran + Tingkatan per Subject --}}
        <div class="mb-3">
            <label class="tk-form-label">Mata Pelajaran & Tingkatan <span class="req">*</span></label>
            <p style="font-size:11px;color:#8890a8;margin:0 0 10px;">Pilih mata pelajaran, lalu tentukan tingkatan untuk masing-masing.</p>

            {{-- Subject pills --}}
            <div id="subjectCheckboxes" class="d-flex flex-wrap gap-2 mb-3">
                @php
                    $oldSubjectTingkatan = old('subject_tingkatan', []);
                @endphp
                @foreach($mataPelajaran as $mp)
                    @php $isSelected = isset($oldSubjectTingkatan[$mp->id]); @endphp
                    <label class="subject-pill {{ $isSelected ? 'selected' : '' }}" data-id="{{ $mp->id }}" data-nama="{{ $mp->name }}">
                        <input type="checkbox" value="{{ $mp->id }}"
                               class="d-none subject-cb" {{ $isSelected ? 'checked' : '' }}>
                        {{ $mp->name }}
                    </label>
                @endforeach
            </div>
            @error('subject_tingkatan')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror

            {{-- Per-subject tingkatan rows (generated by JS) --}}
            <div id="subjectTingkatanContainer"></div>
        </div>

        {{-- Tarif per jam + Lokasi mengajar --}}
        <div class="row mb-3">
            <div class="col-7">
                <label class="tk-form-label" for="hourly_rate">Tarif per Jam (Rp) <span class="req">*</span></label>
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
            <div class="col-5">
                <label class="tk-form-label" for="lokasi_mengajar">Lokasi <span class="req">*</span></label>
                <div class="tk-input-group">
                    <i class="bi bi-geo-alt tk-input-icon"></i>
                    <select id="lokasi_mengajar" name="lokasi_mengajar" class="tk-form-control @error('lokasi_mengajar') is-invalid @enderror">
                        <option value="offline" {{ old('lokasi_mengajar') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="online" {{ old('lokasi_mengajar') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="fleksibel" {{ old('lokasi_mengajar') === 'fleksibel' ? 'selected' : '' }}>Fleksibel</option>
                    </select>
                </div>
                @error('lokasi_mengajar')
                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Bio / Deskripsi --}}
        <div class="mb-4">
            <label class="tk-form-label" for="desc">Tentang Anda <span class="opt">(opsional)</span></label>
            <textarea id="desc" name="desc" rows="3"
                      class="tk-form-control @error('desc') is-invalid @enderror"
                      placeholder="Ceritakan pengalaman dan keunggulan Anda sebagai tutor..."
                      style="resize:vertical;">{{ old('desc') }}</textarea>
            <div class="d-flex justify-content-end mt-1">
                <span class="text-muted" style="font-size:.75rem;" id="bioCount">{{ strlen(old('desc', '')) }}/500 karakter</span>
            </div>
            @error('desc')
                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="tk-btn-outline-primary flex-shrink-0" id="backToStep1"
                    style="padding:.6875rem 1rem;">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button type="button" class="tk-btn-primary" id="nextToStep3">
                Lanjut: Jadwal & Pengalaman <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>

    </div>{{-- /step2 --}}


    {{-- ======================================================
         STEP 3 (Tutor): Jadwal, Pendidikan, Pengalaman
    ====================================================== --}}
    <div id="step3" style="display:none;">

        {{-- Jadwal Ketersediaan --}}
        <p style="font-size:12px;color:#8890a8;font-weight:500;text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px;">
            <i class="bi bi-calendar3 me-1"></i> Jadwal Ketersediaan <span class="req">*</span>
        </p>
        <p class="text-muted mb-3" style="font-size:.8rem;">
            Pilih hari dan jam Anda tersedia untuk mengajar.
        </p>

        <div id="scheduleBuilder" class="mb-4">
            @php
                $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                $slots = ['07:00','08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'];
                $oldSchedules = old('schedules', []);
            @endphp

            @foreach($days as $i => $day)
            <div class="mb-2">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <input class="form-check-input day-toggle mt-0" type="checkbox"
                           id="day_{{ $i }}" data-day="{{ $i }}"
                           style="width:16px;height:16px;border-radius:4px;"
                           {{ !empty($oldSchedules[$i]) ? 'checked' : '' }}>
                    <label class="fw-500" style="font-size:.8rem;cursor:pointer;" for="day_{{ $i }}">
                        {{ $day }}
                    </label>
                </div>
                <div class="day-slots ms-4 gap-2 flex-wrap" data-day="{{ $i }}"
                     style="{{ !empty($oldSchedules[$i]) ? 'display:flex;' : 'display:none;' }}">
                    @foreach($slots as $slot)
                        <label class="tk-slot-picker" for="slot_{{ $i }}_{{ str_replace(':','', $slot) }}">
                            <input type="checkbox" name="schedules[{{ $i }}][]"
                                   value="{{ $slot }}"
                                   id="slot_{{ $i }}_{{ str_replace(':','', $slot) }}"
                                   class="d-none slot-checkbox"
                                   {{ in_array($slot, $oldSchedules[$i] ?? []) ? 'checked' : '' }}>
                            <span class="tk-slot sf {{ in_array($slot, $oldSchedules[$i] ?? []) ? 'selected' : '' }}">{{ $slot }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <hr style="border-color:#e8eaf0;margin:20px 0;">

        {{-- Riwayat Pendidikan --}}
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p style="font-size:12px;color:#8890a8;font-weight:500;text-transform:uppercase;letter-spacing:.04em;margin:0;">
                    <i class="bi bi-mortarboard me-1"></i> Riwayat Pendidikan <span class="opt">(opsional)</span>
                </p>
                <button type="button" id="addPendidikan"
                        style="font-size:11px;font-weight:500;color:#1e2d6b;background:none;border:1px solid #d0d5e8;border-radius:6px;padding:3px 10px;cursor:pointer;">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>
            <p class="text-muted mb-2" style="font-size:.75rem;">Opsional. Isi latar belakang pendidikan Anda.</p>

            <div id="pendidikanRows">
                @php $oldPendidikan = old('riwayat_pendidikan', []); @endphp
                @if(count($oldPendidikan) > 0)
                    @foreach($oldPendidikan as $idx => $pd)
                    <div class="pendidikan-row" style="background:#f8f9fc;border:1px solid #e8eaf0;border-radius:8px;padding:12px;margin-bottom:8px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size:11px;font-weight:600;color:#4b5574;">Pendidikan #{{ $idx + 1 }}</span>
                            <button type="button" class="btn-remove-row" style="background:none;border:none;color:#991b1b;cursor:pointer;font-size:12px;"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="row g-2">
                            <div class="col-5">
                                <input type="text" name="riwayat_pendidikan[{{ $idx }}][sekolah]" value="{{ $pd['sekolah'] ?? '' }}"
                                       class="tk-form-control" placeholder="Nama sekolah/kampus" style="font-size:.8rem;height:32px;">
                            </div>
                            <div class="col-4">
                                <input type="text" name="riwayat_pendidikan[{{ $idx }}][jurusan]" value="{{ $pd['jurusan'] ?? '' }}"
                                       class="tk-form-control" placeholder="Jurusan" style="font-size:.8rem;height:32px;">
                            </div>
                            <div class="col-3">
                                <input type="text" name="riwayat_pendidikan[{{ $idx }}][periode]" value="{{ $pd['periode'] ?? '' }}"
                                       class="tk-form-control" placeholder="2018-2022" style="font-size:.8rem;height:32px;">
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <hr style="border-color:#e8eaf0;margin:20px 0;">

        {{-- Pengalaman Mengajar --}}
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p style="font-size:12px;color:#8890a8;font-weight:500;text-transform:uppercase;letter-spacing:.04em;margin:0;">
                    <i class="bi bi-briefcase me-1"></i> Pengalaman Mengajar <span class="opt">(opsional)</span>
                </p>
                <button type="button" id="addPengalaman"
                        style="font-size:11px;font-weight:500;color:#1e2d6b;background:none;border:1px solid #d0d5e8;border-radius:6px;padding:3px 10px;cursor:pointer;">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>
            <p class="text-muted mb-2" style="font-size:.75rem;">Opsional. Ceritakan pengalaman mengajar Anda.</p>

            <div id="pengalamanRows">
                @php $oldPengalaman = old('pengalaman', []); @endphp
                @if(count($oldPengalaman) > 0)
                    @foreach($oldPengalaman as $idx => $pg)
                    <div class="pengalaman-row" style="background:#f8f9fc;border:1px solid #e8eaf0;border-radius:8px;padding:12px;margin-bottom:8px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size:11px;font-weight:600;color:#4b5574;">Pengalaman #{{ $idx + 1 }}</span>
                            <button type="button" class="btn-remove-row" style="background:none;border:none;color:#991b1b;cursor:pointer;font-size:12px;"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="text" name="pengalaman[{{ $idx }}][tempat]" value="{{ $pg['tempat'] ?? '' }}"
                                       class="tk-form-control" placeholder="Tempat (opsional)" style="font-size:.8rem;height:32px;">
                            </div>
                            <div class="col-2">
                                <input type="number" name="pengalaman[{{ $idx }}][experience_years]" value="{{ $pg['experience_years'] ?? '' }}"
                                       class="tk-form-control" placeholder="Tahun" min="0" style="font-size:.8rem;height:32px;">
                            </div>
                            <div class="col-3">
                                <input type="text" name="pengalaman[{{ $idx }}][periode]" value="{{ $pg['periode'] ?? '' }}"
                                       class="tk-form-control" placeholder="2020-sekarang" style="font-size:.8rem;height:32px;">
                            </div>
                            <div class="col-3">
                                <input type="number" name="pengalaman[{{ $idx }}][jumlah_siswa]" value="{{ $pg['jumlah_siswa'] ?? '' }}"
                                       class="tk-form-control" placeholder="Jml siswa" min="0" style="font-size:.8rem;height:32px;">
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
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

<p class="text-center text-muted mt-4 mb-0" style="font-size:.875rem;">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="tk-link">Masuk di sini</a>
</p>

@endsection


@push('styles')
<style>
.tk-slot-picker { cursor: pointer; display: inline-block; }
.tk-slot-picker input:checked + .tk-slot { background: var(--tk-primary) !important; color: #fff !important; border-color: var(--tk-primary) !important; }
.tk-slot { display: inline-flex; align-items: center; justify-content: center; padding: .25rem .625rem; border-radius: .375rem; font-size: .75rem; font-weight: 500; border: 1px solid var(--tk-success-border); background: var(--tk-success-bg); color: var(--tk-success-text); transition: all .15s; cursor: pointer; }
.tk-slot:hover { background: #dcfce7; }
.subject-pill { display: inline-flex; align-items: center; gap: .375rem; padding: .3125rem .875rem; border-radius: 100px; border: 1px solid var(--tk-border); background: var(--tk-surface); color: var(--tk-text-muted); font-size: .8125rem; cursor: pointer; transition: all .15s; user-select: none; }
.subject-pill.selected { background: var(--tk-primary-50); border-color: var(--tk-primary-100); color: var(--tk-primary); font-weight: 500; }
.tingkatan-pill { display: inline-flex; align-items: center; gap: .375rem; padding: .25rem .75rem; border-radius: 100px; border: 1px solid var(--tk-border); background: #fff; color: var(--tk-text-muted); font-size: .75rem; cursor: pointer; transition: all .15s; user-select: none; }
.tingkatan-pill:hover { border-color: #a5b4fc; background: #f5f7ff; }
.tingkatan-pill.selected { background: #f0fdf4; border-color: #86efac; color: #15803d; font-weight: 500; }
.st-row { background:#fff;border:1px solid #e8eaf0;border-radius:8px;padding:10px 12px;margin-bottom:6px; }
.st-row-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:6px; }
.st-row-name { font-size:.8rem;font-weight:600;color:#1a1a2e; }
.req { color:#991b1b; margin-left:2px; }
.opt { color:#b0b8cc; font-size:.7rem; font-weight:400; margin-left:4px; }
</style>
@endpush


@push('scripts')
<script>
$(document).ready(function () {

    let currentRole = '{{ $isTutor ? "tutor" : "siswa" }}';
    let currentStep = {{ $tutorStep }};
    let pendidikanIdx = {{ count($oldPendidikan) }};
    let pengalamanIdx = {{ count($oldPengalaman) }};

    const TINGKATAN_OPTIONS = [
        { val: 'SD',     label: 'SD' },
        { val: 'SMP',    label: 'SMP' },
        { val: 'SMA',    label: 'SMA / SMK' },
        { val: 'Kuliah', label: 'Perguruan Tinggi' },
    ];

    // ── Inisialisasi: restore per-subject tingkatan dari old() ──
    const oldSubjectTingkatan = @json($oldSubjectTingkatan);
    if (Object.keys(oldSubjectTingkatan).length > 0) {
        Object.keys(oldSubjectTingkatan).forEach(function (sid) {
            const $pill = $(`.subject-pill[data-id="${sid}"]`);
            if ($pill.length) {
                $pill.find('.subject-cb').prop('checked', true);
                $pill.addClass('selected');
            }
        });
        rebuildSubjectTingkatan();
        // Restore checked tingkatan after rows are built
        Object.keys(oldSubjectTingkatan).forEach(function (sid) {
            const selected = oldSubjectTingkatan[sid] || [];
            selected.forEach(function (t) {
                const $row = $(`#st-row-${sid}`);
                $row.find(`input[value="${t}"]`).prop('checked', true).closest('.tingkatan-pill').addClass('selected');
            });
        });
    }

    // ── Inisialisasi: jika tutor + error, langsung ke step yang sesuai ──
    @if($isTutor)
        activateRole('tutor');
        @if($tutorStep > 1)
            goToStep({{ $tutorStep }});
        @endif
    @endif

    // ── Role tab click ──────────────────────────────────────────
    $('.tk-role-tab').on('click', function () {
        currentRole = $(this).data('role');
        activateRole(currentRole);
    });

    function syncDisabledState() {
        // Only disable the specific fields that have duplicate names between siswa/tutor
        // Siswa fields (inside #siswaFields): name, tanggal_lhr, tempat_lhr, alamat
        // Tutor fields (inside #step2): name, tanggal_lhr
        // All other fields are unique and should always be enabled for their role.

        if (currentRole === 'siswa') {
            // Enable siswaFields, disable tutor-only steps
            $('#siswaFields input, #siswaFields select').prop('disabled', false);
            $('#step2 input, #step2 select, #step2 textarea').prop('disabled', true);
            $('#step3 input, #step3 select, #step3 textarea').prop('disabled', true);
        } else {
            // Tutor: enable ALL steps (step 1 + step 2 + step 3)
            // Only disable siswaFields to avoid duplicate name/tanggal_lhr
            $('#step1 input, #step1 select, #step1 textarea').prop('disabled', false);
            $('#step2 input, #step2 select, #step2 textarea').prop('disabled', false);
            $('#step3 input, #step3 select, #step3 textarea').prop('disabled', false);
            $('#siswaFields input, #siswaFields select').prop('disabled', true);
        }
        $('#roleInput').prop('disabled', false);
    }

    function activateRole(role) {
        $('#roleInput').val(role);
        currentRole = role;
        $('.tk-role-tab').removeClass('active');
        $(`.tk-role-tab[data-role="${role}"]`).addClass('active');

        if (role === 'tutor') {
            $('#formTitle').text('Daftar sebagai Tutor');
            $('#formSubtitle').text('Langkah ' + currentStep + ' dari 3.');
            $('#stepIndicator').slideDown(200);
            $('#siswaFields').slideUp(200);
            $('#step1BtnSiswa').hide();
            $('#step1BtnTutor').show();
        } else {
            $('#formTitle').text('Buat akun baru');
            $('#formSubtitle').text('Isi data di bawah untuk membuat akun siswa.');
            $('#stepIndicator').slideUp(200);
            $('#siswaFields').slideDown(200);
            $('#step1BtnSiswa').show();
            $('#step1BtnTutor').hide();
            currentStep = 1;
        }
        goToStep(currentRole === 'siswa' ? 1 : currentStep);
    }

    function goToStep(step) {
        ['step1','step2','step3'].forEach(s => $('#' + s).hide());
        $('#step' + step).show();
        currentStep = step;
        updateStepIndicator(step);
        syncDisabledState();
    }

    function updateStepIndicator(step) {
        for (let i = 1; i <= 4; i++) {
            const $num = $(`#step${i}Num`);
            const $line = $(`#line${i}${i+1}`);
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

    $('#nextToStep2').on('click', function () { if (validateStep1()) goToStep(2); });
    $('#backToStep1').on('click', function () { goToStep(1); });
    $('#nextToStep3').on('click', function () { if (validateStep2()) goToStep(3); });
    $('#backToStep2').on('click', function () { goToStep(2); });

    function validateStep1() {
        if (!$('#username').val().trim()) { flashField('#username', 'Username wajib diisi.'); return false; }
        if (!$('#email').val().trim())    { flashField('#email', 'Email wajib diisi.'); return false; }
        if (!$('#no_hp').val().trim())    { flashField('#no_hp', 'No. WhatsApp wajib diisi.'); return false; }
        if ($('#password').val().length < 8) { flashField('#password', 'Password minimal 8 karakter.'); return false; }
        if ($('#password').val() !== $('#password_confirmation').val()) { flashField('#password_confirmation', 'Password tidak cocok.'); return false; }
        if (!$('#terms').is(':checked')) { showToast('Setujui syarat & ketentuan.', 'warning'); return false; }
        if (currentRole === 'siswa') {
            if (!$('#siswa_name').val().trim())     { flashField('#siswa_name', 'Nama wajib diisi.'); return false; }
            if (!$('#tempat_lhr').val().trim())      { flashField('#tempat_lhr', 'Tempat lahir wajib diisi.'); return false; }
            if (!$('#siswa_tanggal_lhr').val())      { flashField('#siswa_tanggal_lhr', 'Tanggal lahir wajib diisi.'); return false; }
            if (!$('#alamat').val().trim())          { flashField('#alamat', 'Alamat wajib diisi.'); return false; }
        }
        return true;
    }

    function validateStep2() {
        if (!$('#tutor_name').val().trim())  { flashField('#tutor_name', 'Nama wajib diisi.'); return false; }
        if (!$('#jenis_kelamin').val())      { flashField('#jenis_kelamin', 'Pilih jenis kelamin.'); return false; }
        if (!$('#tanggal_lhr_tutor').val())  { flashField('#tanggal_lhr_tutor', 'Tanggal lahir wajib diisi.'); return false; }
        if (!$('#domisili').val().trim())     { flashField('#domisili', 'Domisili wajib diisi.'); return false; }
        if (!$('#job').val().trim())          { flashField('#job', 'Pekerjaan wajib diisi.'); return false; }

        const selectedSubjects = getSelectedSubjects();
        if (selectedSubjects.length === 0) { showToast('Pilih minimal 1 mata pelajaran.', 'warning'); return false; }

        let allHaveTingkatan = true;
        selectedSubjects.forEach(function (s) {
            const checked = $(`#st-row-${s.id}`).find('input[type="checkbox"]:checked').length;
            if (checked === 0) allHaveTingkatan = false;
        });
        if (!allHaveTingkatan) { showToast('Pilih tingkatan untuk setiap mata pelajaran.', 'warning'); return false; }

        const rate = $('#hourly_rate').val();
        if (!rate || parseInt(rate) < 10000) { flashField('#hourly_rate', 'Tarif minimal Rp 10.000.'); return false; }
        return true;
    }

    function flashField(selector, msg) {
        const $el = $(selector);
        $el.addClass('is-invalid').focus();
        $el.siblings('.invalid-feedback').remove();
        $el.after(`<div class="invalid-feedback d-block mt-1" style="font-size:.8rem;">${msg}</div>`);
        setTimeout(() => $el.removeClass('is-invalid'), 3000);
    }

    // ── Per-subject tingkatan ────────────────────────────────────
    function getSelectedSubjects() {
        const list = [];
        $('.subject-pill.selected').each(function () {
            list.push({ id: $(this).data('id'), nama: $(this).data('nama') });
        });
        return list;
    }

    function rebuildSubjectTingkatan() {
        const subjects = getSelectedSubjects();
        const container = $('#subjectTingkatanContainer');

        subjects.forEach(function (s) {
            if (!$(`#st-row-${s.id}`).length) {
                let html = `<div class="st-row" id="st-row-${s.id}" data-sid="${s.id}">`;
                html += `<div class="st-row-header"><span class="st-row-name">${s.nama}</span></div>`;
                html += '<div class="d-flex flex-wrap gap-2">';
                TINGKATAN_OPTIONS.forEach(function (t) {
                    html += `<label class="tingkatan-pill" data-val="${t.val}">`;
                    html += `<input type="checkbox" name="subject_tingkatan[${s.id}][]" value="${t.val}" class="d-none">`;
                    html += `${t.label}</label>`;
                });
                html += '</div></div>';
                container.append(html);
            }
        });

        container.find('.st-row').each(function () {
            const sid = $(this).data('sid');
            if (!subjects.find(s => s.id == sid)) {
                $(this).remove();
            }
        });
    }

    $(document).on('click', '.subject-pill', function () {
        const $input = $(this).find('.subject-cb');
        const checked = !$input.prop('checked');
        $input.prop('checked', checked);
        $(this).toggleClass('selected', checked);
        rebuildSubjectTingkatan();
    });

    $(document).on('click', '.tingkatan-pill', function () {
        const $input = $(this).find('input');
        const checked = !$input.prop('checked');
        $input.prop('checked', checked);
        $(this).toggleClass('selected', checked);
    });

    // ── Dynamic rows: Pendidikan ─────────────────────────────────
    $('#addPendidikan').on('click', function () {
        const i = pendidikanIdx++;
        let html = `<div class="pendidikan-row" style="background:#f8f9fc;border:1px solid #e8eaf0;border-radius:8px;padding:12px;margin-bottom:8px;">`;
        html += `<div class="d-flex justify-content-between align-items-center mb-2">`;
        html += `<span style="font-size:11px;font-weight:600;color:#4b5574;">Pendidikan #${i + 1}</span>`;
        html += `<button type="button" class="btn-remove-row" style="background:none;border:none;color:#991b1b;cursor:pointer;font-size:12px;"><i class="bi bi-x-lg"></i></button>`;
        html += `</div><div class="row g-2">`;
        html += `<div class="col-5"><input type="text" name="riwayat_pendidikan[${i}][sekolah]" class="tk-form-control" placeholder="Nama sekolah/kampus" style="font-size:.8rem;height:32px;"></div>`;
        html += `<div class="col-4"><input type="text" name="riwayat_pendidikan[${i}][jurusan]" class="tk-form-control" placeholder="Jurusan" style="font-size:.8rem;height:32px;"></div>`;
        html += `<div class="col-3"><input type="text" name="riwayat_pendidikan[${i}][periode]" class="tk-form-control" placeholder="2018-2022" style="font-size:.8rem;height:32px;"></div>`;
        html += `</div></div>`;
        $('#pendidikanRows').append(html);
    });

    // ── Dynamic rows: Pengalaman ─────────────────────────────────
    $('#addPengalaman').on('click', function () {
        const i = pengalamanIdx++;
        let html = `<div class="pengalaman-row" style="background:#f8f9fc;border:1px solid #e8eaf0;border-radius:8px;padding:12px;margin-bottom:8px;">`;
        html += `<div class="d-flex justify-content-between align-items-center mb-2">`;
        html += `<span style="font-size:11px;font-weight:600;color:#4b5574;">Pengalaman #${i + 1}</span>`;
        html += `<button type="button" class="btn-remove-row" style="background:none;border:none;color:#991b1b;cursor:pointer;font-size:12px;"><i class="bi bi-x-lg"></i></button>`;
        html += `</div><div class="row g-2">`;
        html += `<div class="col-4"><input type="text" name="pengalaman[${i}][tempat]" class="tk-form-control" placeholder="Tempat (opsional)" style="font-size:.8rem;height:32px;"></div>`;
        html += `<div class="col-2"><input type="number" name="pengalaman[${i}][experience_years]" class="tk-form-control" placeholder="Tahun" min="0" style="font-size:.8rem;height:32px;"></div>`;
        html += `<div class="col-3"><input type="text" name="pengalaman[${i}][periode]" class="tk-form-control" placeholder="2020-sekarang" style="font-size:.8rem;height:32px;"></div>`;
        html += `<div class="col-3"><input type="number" name="pengalaman[${i}][jumlah_siswa]" class="tk-form-control" placeholder="Jml siswa" min="0" style="font-size:.8rem;height:32px;"></div>`;
        html += `</div></div>`;
        $('#pengalamanRows').append(html);
    });

    $(document).on('click', '.btn-remove-row', function () {
        $(this).closest('.pendidikan-row, .pengalaman-row').remove();
    });

    // ── Form submit ──────────────────────────────────────────────
    $('#registerForm').on('submit', function () {
        if (currentRole === 'siswa') {
            $('#step1 input, #step1 select, #step1 textarea').prop('disabled', false);
            $('#siswaFields input, #siswaFields select').prop('disabled', false);
            $('#step2 input, #step2 select, #step2 textarea').prop('disabled', true);
            $('#step3 input, #step3 select, #step3 textarea').prop('disabled', true);
        } else {
            $('#step1 input, #step1 select, #step1 textarea').prop('disabled', false);
            $('#step2 input, #step2 select, #step2 textarea').prop('disabled', false);
            $('#step3 input, #step3 select, #step3 textarea').prop('disabled', false);
            $('#siswaFields input, #siswaFields select').prop('disabled', true);
        }
        $('#roleInput').prop('disabled', false);
    });

    // ── Hari toggle ──────────────────────────────────────────────
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

    // ── Bio counter ──────────────────────────────────────────────
    $('#desc').on('input', function () {
        const len = $(this).val().length;
        $('#bioCount').text(len + '/500 karakter');
        if (len > 500) $(this).val($(this).val().slice(0, 500));
    });

    // ── Tarif preview ────────────────────────────────────────────
    $('#hourly_rate').on('input', function () {
        const val = parseInt($(this).val());
        if (val > 0) {
            $('#tarifPreview').html(`<i class="bi bi-info-circle me-1"></i>Tarif: <strong>Rp ${val.toLocaleString('id-ID')}/jam</strong>`);
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
            $(`#ps${i}`).css('background', i <= score ? colors[score-1] : '#e2e8f0');
        }
        $('#psLabel').text(labels[score-1] || '').css('color', colors[score-1]);
    });

});
</script>
@endpush

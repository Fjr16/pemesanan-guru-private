@extends('layouts.app')

@section('title', ($tutor->user->name ?? 'Tutor') . ' — TutorKu')

@section('content')

{{-- ================================================================
     PROFIL HEADER
=============================================================== --}}
<div style="background:var(--tk-primary-dark);padding:2.5rem 0 0;">
    <div class="container">
        <div class="row align-items-end g-4 pb-0">

            {{-- Avatar + Info utama --}}
            <div class="col-lg-8">
                <a href="{{ route('home') }}"
                   class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"
                   style="color:rgba(255,255,255,.55);font-size:.8125rem;">
                    <i class="bi bi-arrow-left"></i> Kembali ke daftar tutor
                </a>

                <div class="d-flex align-items-start gap-4">
                    <div class="tk-tutor-avatar flex-shrink-0"
                         style="width:80px;height:80px;font-size:1.5rem;
                                background:var(--tk-primary-light);color:#fff;">
                        @if($tutor->foto)
                            <img src="{{ asset('storage/' . $tutor->foto) }}" alt=""
                                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($tutor->name ?? 'TK', 0, 2)) }}
                        @endif
                    </div>

                    <div>
                        <h1 class="text-white mb-1" style="font-size:1.5rem;font-weight:600;">
                            {{ $tutor->name }}
                        </h1>
                        <p class="mb-2" style="color:rgba(255,255,255,.6);font-size:.9375rem;">
                            {{ $tutor->job }} ·
                            {{ $tutor->tutorSubjects->map(fn($ts) => $ts->subjectCategory->name ?? '-')->join(', ') }}
                        </p>

                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <span style="color:rgba(255,255,255,.65);font-size:.875rem;">
                                <i class="bi bi-person-check me-1"></i>
                                {{ $tutor->session_count ?? 0 }} sesi selesai
                            </span>
                            <span style="color:rgba(255,255,255,.3);">·</span>
                            <span style="color:rgba(255,255,255,.65);font-size:.875rem;">
                                <i class="bi bi-briefcase me-1"></i>
                                {{ $totalExperience ?? 0 }} tahun pengalaman
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarif + CTA --}}
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-block text-start"
                     style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                            border-radius:var(--tk-radius-xl);padding:1rem 1.25rem;">
                    <div style="color:rgba(255,255,255,.5);font-size:.8rem;margin-bottom:4px;">Tarif per jam</div>
                    <div class="text-white fw-600 mb-3" style="font-size:1.625rem;">
                        Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}
                    </div>
                    <a href="#jadwal-section" class="tk-btn-primary d-flex align-items-center justify-content-center gap-2"
                       style="width:100%;font-size:.9rem;">
                        <i class="bi bi-calendar-check"></i> Pilih Jadwal & Pesan
                    </a>
                </div>
            </div>

        </div>

        {{-- Tab nav --}}
        <div class="d-flex gap-0 mt-4" style="border-bottom:1px solid rgba(255,255,255,.1);">
            <a href="#profil-section" class="tk-profile-tab active" data-tab="profil">Profil</a>
            <a href="#jadwal-section" class="tk-profile-tab" data-tab="jadwal">Jadwal & Booking</a>
        </div>
    </div>
</div>


{{-- ================================================================
     BODY CONTENT
=============================================================== --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- LEFT: Detail profil --}}
        <div class="col-lg-8">

            {{-- TENTANG SAYA --}}
            <div id="profil-section" class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>Tentang Saya
                    </h2>
                </div>
                <div class="tk-card-body">
                    <p style="font-size:.9375rem;line-height:1.7;color:var(--tk-text-muted);">
                        {{ $tutor->desc ?? 'Tutor berpengalaman siap membantu belajarmu.' }}
                    </p>

                    <div class="row g-3 mt-1">
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-gender-ambiguous me-1"></i>Jenis Kelamin
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ $tutor->jenis_kelamin ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-geo-alt me-1"></i>Domisili
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ $tutor->domisili ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-briefcase me-1"></i>Pekerjaan
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ $tutor->job ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem;">
                                <div style="font-size:.75rem;color:var(--tk-text-muted);margin-bottom:4px;">
                                    <i class="bi bi-laptop me-1"></i>Lokasi Mengajar
                                </div>
                                <div style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ ucfirst($tutor->lokasi_mengajar ?? 'Offline') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SPESIALISASI (TutorSubject + SubjectCategory) --}}
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-patch-check me-2 text-primary"></i>Spesialisasi
                    </h2>
                </div>
                <div class="tk-card-body">
                    @forelse($tutor->tutorSubjects as $ts)
                        <div style="padding:.75rem 0;{{ !$loop->last ? 'border-bottom:1px solid var(--tk-border-light);' : '' }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    📚 {{ $ts->subjectCategory->name ?? '-' }}
                                </span>
                                <span class="tk-badge tk-badge-completed" style="font-size:.7rem;">
                                    {{ $ts->tingkatan }}
                                </span>
                            </div>
                            @if($ts->deskripsi)
                                <p style="font-size:.8125rem;color:var(--tk-text-muted);margin:0;line-height:1.5;">
                                    {{ $ts->deskripsi }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <span class="text-muted small">Belum ada spesialisasi.</span>
                    @endforelse
                </div>
            </div>

            {{-- PENGALAMAN MENGAJAR (TutorProfile) --}}
            @if($tutor->tutorProfiles->isNotEmpty())
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-briefcase me-2 text-primary"></i>Pengalaman Mengajar
                    </h2>
                </div>
                <div class="tk-card-body">
                    @foreach($tutor->tutorProfiles as $exp)
                        <div style="padding:.75rem 0;{{ !$loop->last ? 'border-bottom:1px solid var(--tk-border-light);' : '' }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ $exp->tempat ?? '-' }}
                                </span>
                                <span style="font-size:.75rem;color:var(--tk-text-muted);">
                                    {{ $exp->periode }}
                                </span>
                            </div>
                            <div class="d-flex gap-3" style="font-size:.8125rem;color:var(--tk-text-muted);">
                                <span><i class="bi bi-clock me-1"></i>{{ $exp->experience_years }} tahun</span>
                                <span><i class="bi bi-people me-1"></i>{{ $exp->jumlah_siswa }} siswa</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- RIWAYAT PENDIDIKAN (StudiedHistory) --}}
            @if($tutor->studiedHistories->isNotEmpty())
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-mortarboard me-2 text-primary"></i>Riwayat Pendidikan
                    </h2>
                </div>
                <div class="tk-card-body">
                    @foreach($tutor->studiedHistories as $edu)
                        <div style="padding:.75rem 0;{{ !$loop->last ? 'border-bottom:1px solid var(--tk-border-light);' : '' }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span style="font-size:.9rem;font-weight:500;color:var(--tk-text);">
                                    {{ $edu->sekolah }}
                                </span>
                                <span style="font-size:.75rem;color:var(--tk-text-muted);">
                                    {{ $edu->periode }}
                                </span>
                            </div>
                            <div style="font-size:.8125rem;color:var(--tk-text-muted);">
                                @if($edu->jenjang)
                                    <span class="tk-badge" style="font-size:.7rem;background:var(--tk-surface);color:var(--tk-text-muted);margin-right:6px;">{{ $edu->jenjang }}</span>
                                @endif
                                {{ $edu->jurusan }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- JADWAL GLOBAL (weekly overview) --}}
            @if($schedulesByDay->isNotEmpty())
            <div class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-calendar-week me-2 text-primary"></i>Jadwal Ketersediaan
                    </h2>
                    <span style="font-size:.75rem;color:var(--tk-text-muted);">Ringkasan mingguan</span>
                </div>
                <div class="tk-card-body">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                            @php $daySlots = $schedulesByDay->get($day, collect()); @endphp
                            <div style="border:1px solid var(--tk-border-light);border-radius:var(--tk-radius-lg);padding:.625rem;text-align:center;">
                                <div style="font-size:.75rem;font-weight:600;color:var(--tk-text);margin-bottom:{{ $daySlots->isNotEmpty() ? '6px' : '0' }};">
                                    {{ $day }}
                                </div>
                                @if($daySlots->isNotEmpty())
                                    <div style="display:flex;flex-direction:column;gap:2px;">
                                        @foreach($daySlots as $slot)
                                            <span style="font-size:.7rem;padding:2px 4px;border-radius:4px;background:var(--tk-success-bg);color:var(--tk-success-text);">
                                                {{ $slot->jam_start }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="font-size:.7rem;color:var(--tk-text-muted);opacity:.5;">—</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <p style="font-size:.75rem;color:var(--tk-text-muted);margin:10px 0 0;">
                        <i class="bi bi-info-circle me-1"></i>
                        Setiap slot berdurasi 1 jam. Pilih tanggal di bawah untuk melihat ketersediaan detail.
                    </p>
                </div>
            </div>
            @endif

            {{-- JADWAL & BOOKING ──────────────────────────────── --}}
            <div id="jadwal-section" class="tk-card mb-4">
                <div class="tk-card-header">
                    <h2 class="tk-card-title">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Pesan Sesi Belajar
                    </h2>
                </div>
                <div class="tk-card-body">

                    @guest
                        <div class="text-center py-4"
                             style="background:var(--tk-primary-50);border-radius:var(--tk-radius-lg);
                                    border:1px dashed var(--tk-primary-100);">
                            <i class="bi bi-lock text-primary" style="font-size:2rem;"></i>
                            <h6 class="mt-2 mb-1">Login untuk melihat & memilih jadwal</h6>
                            <p class="text-muted small mb-3">Daftar gratis atau masuk ke akun Anda.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('login') }}" class="tk-btn-primary" style="width:auto;padding:.5rem 1.25rem;">Masuk</a>
                                <a href="{{ route('register') }}" class="tk-btn-outline-primary">Daftar Gratis</a>
                            </div>
                        </div>
                    @endguest

                    @auth
                        {{-- Step 1: Pilih tanggal --}}
                        <div id="stepTanggal">
                            <div style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:1.25rem;">
                                <label class="tk-form-label" for="tanggalPicker" style="font-size:.875rem;font-weight:500;">
                                    <i class="bi bi-calendar-event me-1"></i>Pilih Tanggal Kelas
                                </label>
                                <input type="date" id="tanggalPicker" class="tk-form-control" style="max-width:240px;">
                                <div id="tanggalInfo" style="font-size:.75rem;color:var(--tk-text-muted);margin-top:6px;">
                                    Minimal booking H-3 dari hari ini.
                                </div>
                                <div id="tanggalErr" style="display:none;font-size:.75rem;color:var(--tk-danger-text);margin-top:4px;"></div>
                            </div>
                        </div>

                        {{-- Step 2: Pilih jam (muncul setelah tanggal dipilih) --}}
                        <div id="stepJam" style="display:none;" class="mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <span style="font-size:.875rem;font-weight:500;color:var(--tk-text);">
                                        <i class="bi bi-clock me-1"></i>
                                        Slot Tersedia — <strong id="labelHari"></strong>, <strong id="labelTanggal"></strong>
                                    </span>
                                </div>
                                <div class="d-flex gap-2 align-items-center" style="font-size:.7rem;">
                                    <span class="d-flex align-items-center gap-1">
                                        <span style="width:8px;height:8px;background:var(--tk-success-bg);border:1px solid var(--tk-success-border);border-radius:2px;"></span>Tersedia
                                    </span>
                                    <span class="d-flex align-items-center gap-1">
                                        <span style="width:8px;height:8px;background:var(--tk-danger-bg);border:1px solid var(--tk-danger-border);border-radius:2px;"></span>Penuh
                                    </span>
                                    <span class="d-flex align-items-center gap-1">
                                        <span style="width:8px;height:8px;background:var(--tk-primary);border-radius:2px;"></span>Dipilih
                                    </span>
                                </div>
                            </div>

                            {{-- Loading --}}
                            <div id="slotsLoading" class="text-center py-3">
                                <div class="tk-spinner tk-spinner-dark mx-auto mb-2"></div>
                                <p class="text-muted small mb-0">Memuat slot...</p>
                            </div>

                            {{-- Slot list --}}
                            <div id="slotsList" style="display:none;"></div>

                            {{-- Empty --}}
                            <div id="slotsEmpty" style="display:none;">
                                <div class="text-center py-3">
                                    <i class="bi bi-calendar-x text-muted" style="font-size:1.5rem;opacity:.4;"></i>
                                    <p class="text-muted small mt-2 mb-0">Tidak ada slot di hari ini.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Ringkasan & konfirmasi --}}
                        <div id="stepKonfirmasi" style="display:none;" class="mt-3">
                            <div style="background:var(--tk-primary-50);border:1px solid var(--tk-primary-100);border-radius:var(--tk-radius-lg);padding:1rem;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <div style="font-size:.875rem;font-weight:500;color:var(--tk-primary-dark);">
                                            <i class="bi bi-check2-circle me-1"></i>
                                            <span id="ringkasanSlot"></span>
                                        </div>
                                        <div style="font-size:.75rem;color:var(--tk-primary-light);margin-top:2px;">
                                            <span id="ringkasanJam"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-close-sm" id="clearSelection"></button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2" style="border-top:1px solid var(--tk-primary-100);">
                                    <span style="font-size:.8125rem;color:var(--tk-primary-dark);">Total Pembayaran</span>
                                    <strong id="ringkasanTotal" style="font-size:1rem;color:var(--tk-primary-dark);"></strong>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="tk-form-label" for="catatanInput">Catatan untuk Tutor (opsional)</label>
                                <textarea id="catatanInput" rows="2" class="tk-form-control"
                                          placeholder="cth: Saya butuh bantuan bab limit dan turunan..."></textarea>
                            </div>

                            <div class="mt-3">
                                @if(Auth::user()?->role === 'siswa')
                                    <button type="button" class="tk-btn-primary" id="btnPesan"
                                            style="width:auto;padding:.6875rem 1.75rem;">
                                        <i class="bi bi-calendar-plus"></i> Pesan Sekarang
                                    </button>
                                @else
                                    <div class="alert alert-info py-2 px-3 mb-0" style="font-size:.875rem;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Hanya akun siswa yang dapat melakukan pemesanan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endauth

                </div>
            </div>

            {{-- MODAL BOOKING BERHASIL --}}
            <div class="modal fade" id="bookingSuccessModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
                    <div class="modal-content" style="border:none;border-radius:var(--tk-radius-xl);box-shadow:0 8px 32px rgba(30,45,107,.12);">
                        <div class="modal-body text-center" style="padding:2rem;">
                            <div style="width:64px;height:64px;border-radius:50%;background:var(--tk-success-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                                <i class="bi bi-check-circle-fill" style="font-size:2rem;color:var(--tk-success-text);"></i>
                            </div>
                            <h5 style="font-size:1.125rem;font-weight:600;color:var(--tk-text);margin-bottom:.5rem;">Pemesanan Berhasil!</h5>
                            <p style="font-size:.875rem;color:var(--tk-text-muted);margin-bottom:.25rem;">
                                Pesanan Anda sedang menunggu konfirmasi dari tutor.
                            </p>
                            <p style="font-size:.8125rem;color:var(--tk-text-muted);margin-bottom:1.5rem;">
                                Silakan tunggu maksimal <strong>24 jam</strong> untuk tutor merespons. Anda akan mendapat notifikasi via email.
                            </p>
                            <div id="bookingSuccessDetail" style="background:var(--tk-surface);border-radius:var(--tk-radius-lg);padding:.875rem 1rem;margin-bottom:1.25rem;text-align:left;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:.8125rem;color:var(--tk-text-muted);">Order ID</span>
                                    <span id="successOrderId" style="font-size:.8125rem;font-weight:500;">-</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-size:.8125rem;color:var(--tk-text-muted);">Jumlah Jam</span>
                                    <span id="successJamCount" style="font-size:.8125rem;font-weight:500;">-</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:.8125rem;color:var(--tk-text-muted);">Total</span>
                                    <span id="successTotal" style="font-size:.8125rem;font-weight:600;color:var(--tk-primary-dark);">-</span>
                                </div>
                            </div>
                            <a id="btnLihatPesanan" href="{{ route('siswa.pemesanan') }}"
                               class="tk-btn-primary d-flex align-items-center justify-content-center gap-2"
                               style="width:100%;margin-bottom:.75rem;">
                                <i class="bi bi-receipt"></i> Lihat Pesanan
                            </a>
                            <a href="{{ route('home') }}" style="font-size:.8125rem;color:var(--tk-text-muted);text-decoration:none;">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>{{-- /col-lg-8 --}}


        {{-- RIGHT: Sticky sidebar ringkasan --}}
        <div class="col-lg-4">
            <div class="tk-card" style="position:sticky;top:76px;">
                <div class="tk-card-body">
                    <div class="text-center mb-4">
                        <div class="tk-tutor-avatar mx-auto mb-2"
                             style="width:64px;height:64px;font-size:1.25rem;
                                    background:var(--tk-primary-light);color:#fff;">
                            @if($tutor->foto)
                                <img src="{{ asset('storage/' . $tutor->foto) }}" alt=""
                                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
                            @else
                                {{ strtoupper(substr($tutor->name ?? 'TK', 0, 2)) }}
                            @endif
                        </div>
                        <h3 style="font-size:1rem;font-weight:600;">{{ $tutor->name }}</h3>
                        <div style="font-size:.8125rem;color:var(--tk-text-muted);">{{ $tutor->job }}</div>
                    </div>

                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Tarif/jam</span>
                        <span style="font-weight:600;color:var(--tk-primary-dark);">
                            Rp {{ number_format($tutor->hourly_rate ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Pengalaman</span>
                        <span style="font-weight:500;">{{ $totalExperience ?? 0 }} tahun</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Sesi selesai</span>
                        <span style="font-weight:500;">{{ $tutor->session_count ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Domisili</span>
                        <span style="font-weight:500;">{{ $tutor->domisili ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 mb-3" style="border-top:1px solid var(--tk-border-light);">
                        <span class="text-muted" style="font-size:.875rem;">Lokasi</span>
                        <span style="font-weight:500;">{{ ucfirst($tutor->lokasi_mengajar ?? 'Offline') }}</span>
                    </div>

                    <a href="#jadwal-section" class="tk-btn-primary d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-calendar-check"></i> Pesan Sesi
                    </a>

                    <div class="mt-3 text-center">
                        @if($tutor->user->no_hp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tutor->user->no_hp) }}"
                               target="_blank"
                               class="d-inline-flex align-items-center gap-2 text-decoration-none"
                               style="font-size:.8125rem;color:var(--tk-success-text);">
                                <i class="bi bi-whatsapp"></i> Chat via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


@push('styles')
<style>
.tk-profile-tab {
    padding: .75rem 1.25rem;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .875rem;
    border-bottom: 2px solid transparent;
    transition: all .15s;
    white-space: nowrap;
}
.tk-profile-tab:hover { color: rgba(255,255,255,.8); }
.tk-profile-tab.active { color: #fff; border-bottom-color: var(--tk-primary-300); }
</style>
@endpush


@push('scripts')
<script>
const TUTOR_ID    = {{ $tutor->id }};
const HOURLY_RATE = {{ $tutor->hourly_rate ?? 0 }};
const HARI_MAP    = {0:'Minggu',1:'Senin',2:'Selasa',3:'Rabu',4:'Kamis',5:'Jumat',6:'Sabtu'};
let selectedSlots = [];
let availableDays = [];
let selectedTanggal = '';

$(document).ready(function () {

    @auth
    loadAvailableDays();
    $('#tanggalPicker').on('change', onTanggalChange);
    $('#clearSelection').on('click', clearSelection);
    $('#btnPesan').on('click', submitBooking);
    @endauth

    $('.tk-profile-tab').on('click', function (e) {
        const target = $(this).attr('href');
        if (target.startsWith('#')) {
            e.preventDefault();
            const $target = $(target);
            if ($target.length) {
                $('html,body').animate({scrollTop: $target.offset().top - 80}, 350);
            }
        }
        $('.tk-profile-tab').removeClass('active');
        $(this).addClass('active');
    });

});

function loadAvailableDays() {
    $.ajax({
        url: `/api/tutor/${TUTOR_ID}/available-days`,
        method: 'GET',
        success: function (res) {
            availableDays = res.days || [];
            const minDate = new Date();
            minDate.setDate(minDate.getDate() + 3);
            $('#tanggalPicker').attr('min', minDate.toISOString().split('T')[0]);
        }
    });
}

function onTanggalChange() {
    const val = $('#tanggalPicker').val();
    const $err = $('#tanggalErr');
    $err.hide();
    $('#stepJam, #stepKonfirmasi').hide();
    selectedSlots = [];

    if (!val) return;

    const selected = new Date(val + 'T00:00:00');
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 3);
    minDate.setHours(0,0,0,0);

    if (selected < minDate) {
        $err.text('Minimal booking H-3 dari hari ini.').show();
        return;
    }

    const dayName = HARI_MAP[selected.getDay()];
    if (availableDays.length > 0 && !availableDays.includes(dayName)) {
        $err.text('Tutor tidak tersedia di hari ' + dayName + '.').show();
        return;
    }

    selectedTanggal = val;
    loadSlots(val, dayName);
}

function loadSlots(tanggal, hari) {
    $('#stepJam').show();
    $('#slotsLoading').show();
    $('#slotsList, #slotsEmpty').hide();

    $('#labelHari').text(hari);
    const parts = tanggal.split('-');
    const tanggalObj = new Date(parts[0], parts[1] - 1, parts[2]);
    const options = {day:'numeric', month:'long', year:'numeric'};
    $('#labelTanggal').text(tanggalObj.toLocaleDateString('id-ID', options));

    $.ajax({
        url: `/api/tutor/${TUTOR_ID}/jadwal?tanggal=${tanggal}`,
        method: 'GET',
        success: function (res) {
            const slots = res.data || [];
            $('#slotsLoading').hide();

            if (!slots.length) {
                $('#slotsEmpty').show();
                return;
            }

            renderSlots(slots);
            $('#slotsList').show();
        },
        error: function () {
            $('#slotsLoading').hide();
            $('#slotsEmpty').show();
        }
    });
}

function renderSlots(slots) {
    let html = '<div style="display:flex;flex-direction:column;gap:6px;">';

    slots.forEach(function (slot) {
        if (slot.is_booked) {
            html += `
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:8px;background:var(--tk-danger-bg);border:1px solid var(--tk-danger-border);">
                    <div>
                        <span style="font-size:.875rem;font-weight:500;color:var(--tk-danger-text);">
                            ${slot.jam_mulai} — ${slot.jam_selesai} WIB
                        </span>
                    </div>
                    <span style="font-size:.75rem;color:var(--tk-danger-text);opacity:.7;">
                        <i class="bi bi-lock-fill me-1"></i>Sudah dipesan
                    </span>
                </div>`;
        } else {
            html += `
                <div class="slot-item" data-schedule-id="${slot.id}" data-jam="${slot.jam_mulai}" data-jam-selesai="${slot.jam_selesai}"
                     onclick="toggleSlot(this)"
                     style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:8px;background:var(--tk-success-bg);border:1px solid var(--tk-success-border);cursor:pointer;transition:all .15s;">
                    <span style="font-size:.875rem;font-weight:500;color:var(--tk-success-text);">
                        ${slot.jam_mulai} — ${slot.jam_selesai} WIB
                    </span>
                    <span style="font-size:.75rem;color:var(--tk-success-text);">
                        Tersedia
                    </span>
                </div>`;
        }
    });

    html += '</div>';
    $('#slotsList').html(html);
}

function toggleSlot(el) {
    const $el = $(el);
    const scheduleId = $el.data('schedule-id');
    const jam = $el.data('jam');
    const jamSelesai = $el.data('jam-selesai');

    const idx = selectedSlots.findIndex(s => s.scheduleId === scheduleId);
    if (idx >= 0) {
        selectedSlots.splice(idx, 1);
        $el.css({background:'var(--tk-success-bg)', border:'1px solid var(--tk-success-border)'});
        $el.find('.slot-check').remove();
    } else {
        selectedSlots.push({scheduleId, jam, jamSelesai});
        $el.css({background:'var(--tk-primary-50)', border:'2px solid var(--tk-primary)'});
        $el.append('<span class="slot-check" style="margin-left:8px;font-size:.875rem;color:var(--tk-primary);"><i class="bi bi-check-circle-fill"></i></span>');
    }

    selectedSlots.sort((a, b) => a.jam.localeCompare(b.jam));
    updateKonfirmasi();
}

function addOneHour(timeStr) {
    const [h, m] = timeStr.split(':').map(Number);
    return String((h + 1) % 24).padStart(2, '0') + ':' + String(m).padStart(2, '0');
}

function updateKonfirmasi() {
    if (selectedSlots.length === 0) {
        $('#stepKonfirmasi').hide();
        return;
    }

    const jamList = selectedSlots.map(s => s.jam);
    const lastJamEnd = addOneHour(selectedSlots[selectedSlots.length - 1].jamSelesai || addOneHour(selectedSlots[selectedSlots.length - 1].jam));
    const jamTampil = jamList.length === 1
        ? jamList[0] + ' – ' + addOneHour(jamList[0]) + ' WIB'
        : jamList[0] + ' – ' + lastJamEnd + ' WIB';

    const total = HOURLY_RATE * selectedSlots.length;

    $('#ringkasanSlot').text(selectedSlots.length + ' jam dipilih');
    $('#ringkasanJam').text(jamTampil);
    $('#ringkasanTotal').text('Rp ' + total.toLocaleString('id-ID'));
    $('#stepKonfirmasi').show();
}

function clearSelection() {
    selectedSlots = [];
    $('.slot-item').each(function () {
        $(this).css({background:'var(--tk-success-bg)', border:'1px solid var(--tk-success-border)'});
        $(this).find('.slot-check').remove();
    });
    $('#stepKonfirmasi').hide();
}

function submitBooking() {
    if (selectedSlots.length === 0 || !selectedTanggal) return;

    const $btn = $('#btnPesan');
    $btn.prop('disabled', true)
        .html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Memproses...');

    const scheduleIds = selectedSlots.map(s => s.scheduleId);

    $.ajax({
        url: '{{ route("api.booking.store") }}',
        method: 'POST',
        data: {
            'schedule_ids[]': scheduleIds,
            tanggal: selectedTanggal,
            catatan: $('#catatanInput').val(),
            _token: "{{ csrf_token() }}",
        },
        traditional: true,
        success: function (res) {
            const total = HOURLY_RATE * selectedSlots.length;
            $('#successOrderId').text('#' + (res.order_id || '-'));
            $('#successJamCount').text(selectedSlots.length + ' jam');
            $('#successTotal').text('Rp ' + total.toLocaleString('id-ID'));
            clearSelection();
            var modal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
            modal.show();
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal melakukan pemesanan.';
            showToast(msg, 'error');
            $btn.prop('disabled', false)
                .html('<i class="bi bi-calendar-plus"></i> Pesan Sekarang');
        }
    });
}

function showToast(msg, type) {
    type = type || 'success';
    const bg = type === 'success' ? '#ecfdf5' : '#fef2f2';
    const color = type === 'success' ? '#065f46' : '#991b1b';
    const border = type === 'success' ? '#a7f3d0' : '#fecaca';
    const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill';
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;min-width:240px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1);background:${bg};color:${color};border:1px solid ${border};`;
    toast.innerHTML = `<i class="bi bi-${icon}"></i> ${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
@endpush

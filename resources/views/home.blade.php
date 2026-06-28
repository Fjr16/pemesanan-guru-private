@extends('layouts.app')

@section('title', 'TutorKu — Temukan Guru Privat Terbaik')

@section('content')

{{-- ================================================================
     HERO SECTION
================================================================ --}}
<section class="tk-hero">
    <div class="container text-center">

        <div class="tk-hero-badge mb-3">
            <i class="bi bi-patch-check-fill"></i>
            Platform Guru Privat Terpercaya #1
        </div>

        <h1 class="mb-3">
            Temukan Guru Privat<br class="d-none d-md-block">
            Terbaik Untukmu
        </h1>

        <p class="tk-hero-sub">
            Pilih tutor sesuai mata pelajaran, jadwal, dan kebutuhanmu.
            Booking mudah, konfirmasi cepat.
        </p>

        {{-- SEARCH BOX --}}
        <form id="searchForm" class="mb-0" autocomplete="off">
            <div class="tk-search-box mx-auto">

                {{-- Icon search --}}
                <i class="bi bi-search text-muted flex-shrink-0" style="font-size:1rem;"></i>

                {{-- Pilih mata pelajaran --}}
                <select id="searchMapel" name="mata_pelajaran_id"
                        class="tk-search-select" aria-label="Pilih mata pelajaran">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($mataPelajaran as $mp)
                        <option value="{{ $mp->id }}">{{ $mp->nama }}</option>
                    @endforeach
                </select>

                <div class="tk-search-divider d-none d-sm-block"></div>

                {{-- Tombol cari --}}
                <button type="submit" class="tk-search-btn" id="searchBtn">
                    <i class="bi bi-search"></i>
                    <span>Cari Tutor</span>
                </button>
            </div>
        </form>

        {{-- Stats --}}
        <div class="tk-hero-stats mt-4">
            <div class="text-center">
                <div class="tk-hero-stat-num">{{ $stats['total_tutor'] ?? '200' }}+</div>
                <div class="tk-hero-stat-lbl">Tutor aktif</div>
            </div>
            <div class="text-center">
                <div class="tk-hero-stat-num">{{ $stats['total_sesi'] ?? '1.200' }}+</div>
                <div class="tk-hero-stat-lbl">Sesi selesai</div>
            </div>
            <div class="text-center">
                <div class="tk-hero-stat-num">{{ $stats['kepuasan'] ?? '98' }}%</div>
                <div class="tk-hero-stat-lbl">Kepuasan siswa</div>
            </div>
        </div>

    </div>
</section>


{{-- ================================================================
     FILTER CHIPS — MATA PELAJARAN POPULER
================================================================ --}}
<section class="tk-section-sm" style="background:#fff;border-bottom:1px solid var(--tk-border);">
    <div class="container">
        <div class="d-flex align-items-center gap-3 overflow-auto pb-1" id="subjectChips"
             style="scrollbar-width:none;">
            <span class="text-muted flex-shrink-0" style="font-size:.8125rem;font-weight:500;">
                Populer:
            </span>
            @foreach($mataPelajaran->take(8) as $mp)
                <button type="button"
                        class="btn flex-shrink-0 subject-chip"
                        data-id="{{ $mp->id }}"
                        style="border:1px solid var(--tk-border);border-radius:100px;
                               font-size:.8125rem;padding:.3rem .875rem;white-space:nowrap;
                               background:#fff;color:var(--tk-text-muted);transition:all .15s;">
                    {{ $mp->nama }}
                </button>
            @endforeach
        </div>
    </div>
</section>


{{-- ================================================================
     TUTOR RESULTS / LISTING
================================================================ --}}
<section class="tk-section">
    <div class="container">

        {{-- Header hasil --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="tk-section-title mb-1" id="resultsTitle">Tutor Terpopuler</h2>
                <p class="tk-section-sub mb-0" id="resultsSubtitle">
                    Tutor terverifikasi siap membantu belajarmu
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 d-none d-sm-flex">
                <span class="text-muted" style="font-size:.8125rem;">Urutkan:</span>
                <select id="sortSelect" class="form-select form-select-sm"
                        style="width:auto;font-size:.8125rem;border-color:var(--tk-border);border-radius:var(--tk-radius);">
                    <option value="popular">Terpopuler</option>
                    <option value="rating">Rating tertinggi</option>
                    <option value="price_asc">Harga terendah</option>
                    <option value="price_desc">Harga tertinggi</option>
                </select>
            </div>
        </div>

        {{-- RESULTS CONTAINER --}}
        <div id="tutorResults" data-auto-load="1">
            {{-- Initial server-side render (fallback jika JS lambat) --}}
            @if(isset($tutors) && $tutors->count())
                <div class="row g-4">
                    @foreach($tutors as $tutor)
                        @include('components.tutor-card', ['tutor' => $tutor])
                    @endforeach
                </div>

                {{-- Pagination --}}
                {{-- @if(isset($tutors) && $tutors->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $tutors->links('components.pagination') }}
                    </div>
                @endif --}}
            @else
                {{-- Skeleton loading awal --}}
                <div class="row g-4" id="skeletonGrid">
                    @for($i = 0; $i < 6; $i++)
                        <div class="col-sm-6 col-lg-4">
                            <div class="tk-tutor-card">
                                <div class="d-flex gap-3 mb-3">
                                    <div class="skeleton" style="width:52px;height:52px;border-radius:50%;"></div>
                                    <div class="flex-1">
                                        <div class="skeleton skeleton-text mb-2" style="width:55%;"></div>
                                        <div class="skeleton skeleton-text" style="width:38%;height:12px;"></div>
                                    </div>
                                </div>
                                <div class="skeleton skeleton-text mb-3" style="width:40%;height:24px;border-radius:100px;"></div>
                                <div class="skeleton skeleton-text mb-1"></div>
                                <div class="skeleton skeleton-text" style="width:65%;"></div>
                                <div class="tk-divider"></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="skeleton skeleton-text" style="width:32%;height:16px;"></div>
                                    <div class="skeleton" style="width:108px;height:34px;border-radius:6px;"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif
        </div>

        {{-- Load more button --}}
        <div class="text-center mt-5" id="loadMoreWrapper" style="display:none !important;">
            <button type="button" class="tk-btn-outline-primary px-4 py-2" id="loadMoreBtn"
                    style="font-size:.9rem;">
                <i class="bi bi-arrow-down-circle me-1"></i>
                Muat lebih banyak tutor
            </button>
        </div>

    </div>
</section>


{{-- ================================================================
     CARA KERJA
================================================================ --}}
<section class="tk-section" id="cara-kerja" style="background:var(--tk-surface);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="tk-section-title mb-2">Cara Kerja TutorKu</h2>
            <p class="tk-section-sub">Mudah, cepat, dan terpercaya — dalam 4 langkah</p>
        </div>

        <div class="row g-4 justify-content-center">
            @php
                $steps = [
                    ['icon'=>'bi-search',           'num'=>'01', 'title'=>'Cari Tutor',        'desc'=>'Pilih mata pelajaran yang tersedia. Lihat profil dan spesialisasi tutor.'],
                    ['icon'=>'bi-calendar-check',   'num'=>'02', 'title'=>'Pilih Jadwal',       'desc'=>'Klik slot jadwal kosong yang sesuai. Sistem akan memastikan tidak ada bentrok.'],
                    ['icon'=>'bi-patch-check',       'num'=>'03', 'title'=>'Konfirmasi Tutor',   'desc'=>'Tutor menerima notifikasi dan mengkonfirmasi kesediaan mengajar di jadwal tersebut.'],
                    ['icon'=>'bi-wallet2',           'num'=>'04', 'title'=>'Bayar & Mulai',      'desc'=>'Selesaikan pembayaran. Detail sesi dikirim via email & WhatsApp secara otomatis.'],
                ];
            @endphp

            @foreach($steps as $i => $step)
                <div class="col-6 col-lg-3">
                    <div class="tk-how-step">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="tk-how-icon">
                                <i class="bi {{ $step['icon'] }}"></i>
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                  style="background:var(--tk-primary);font-size:.65rem;font-weight:600;">
                                {{ $step['num'] }}
                            </span>
                        </div>
                        <h3 class="tk-how-title">{{ $step['title'] }}</h3>
                        <p class="tk-how-desc">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @if($i < count($steps)-1)
                    <div class="d-none d-lg-flex align-items-center" style="width:auto;padding-top:0;margin-top:-1rem;">
                        {{-- Panah connector dihilangkan untuk kesederhanaan di mobile --}}
                    </div>
                @endif
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="text-center mt-5">
            @guest
                <a href="{{ route('register') }}" class="tk-btn-primary d-inline-flex"
                   style="width:auto;padding:.75rem 2rem;font-size:1rem;">
                    <i class="bi bi-person-plus me-2"></i> Mulai Gratis Sekarang
                </a>
                <p class="text-muted mt-2 mb-0" style="font-size:.8125rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" class="tk-link">Masuk</a>
                </p>
            @endguest
            @auth
                <a href="{{ route('home') }}#tutorResults"
                   class="tk-btn-primary d-inline-flex"
                   style="width:auto;padding:.75rem 2rem;font-size:1rem;">
                    <i class="bi bi-search me-2"></i> Cari Tutor Sekarang
                </a>
            @endauth
        </div>
    </div>
</section>


{{-- ================================================================
     MATA PELAJARAN GRID
================================================================ --}}
<section class="tk-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="tk-section-title mb-2">Mata Pelajaran Tersedia</h2>
            <p class="tk-section-sub">Kami memiliki tutor untuk berbagai bidang studi</p>
        </div>

        <div class="row g-3 justify-content-center">
            @foreach($mataPelajaran as $mp)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <button type="button"
                            class="w-100 subject-filter-card"
                            data-id="{{ $mp->id }}"
                            style="background:#fff;border:1px solid var(--tk-border);
                                   border-radius:var(--tk-radius-lg);padding:.875rem .5rem;
                                   text-align:center;cursor:pointer;transition:all .2s;">
                        <div style="font-size:1.5rem;margin-bottom:.5rem;">
                            {{ $mp->icon ?? '📚' }}
                        </div>
                        <div style="font-size:.8125rem;font-weight:500;color:var(--tk-text);">
                            {{ $mp->nama }}
                        </div>
                        <div style="font-size:.7rem;color:var(--tk-text-muted);margin-top:2px;">
                            {{ $mp->tutor_count ?? 0 }} tutor
                        </div>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    let currentPage  = 1;
    let currentFilters = {};
    let isLoading    = false;

    // ── Initial load via AJAX ───────────────────────────────────
    loadTutors({});

    // ── Search form submit ──────────────────────────────────────
    $('#searchForm').off('submit').on('submit', function (e) {
        e.preventDefault();
        currentPage = 1;

        currentFilters = {
            mata_pelajaran_id: $('#searchMapel').val(),
            sort: $('#sortSelect').val()
        };

        const mapelText = $('#searchMapel option:selected').text();

        updateResultsHeader(mapelText);
        doSearch(currentFilters, 1, false);

        // Scroll ke hasil
        $('html, body').animate({
            scrollTop: $('#tutorResults').offset().top - 80
        }, 400);
    });

    // ── Chip klik ───────────────────────────────────────────────
    $(document).on('click', '.subject-chip, .subject-filter-card', function () {
        const id   = $(this).data('id');
        const nama = $(this).find('div:last, span').first().text().trim()
                  || $(this).text().trim();

        // Update active chip style
        $('.subject-chip, .subject-filter-card').css({'background':'#fff','color':'var(--tk-text-muted)','border-color':'var(--tk-border)'});
        $(this).css({'background':'var(--tk-primary-50)','color':'var(--tk-primary)','border-color':'var(--tk-primary-100)'});

        // Sync select
        $('#searchMapel').val(id);

        currentFilters = { mata_pelajaran_id: id, sort: $('#sortSelect').val() };
        currentPage = 1;
        updateResultsHeader(nama);
        doSearch(currentFilters, 1, false);

        $('html, body').animate({ scrollTop: $('#tutorResults').offset().top - 80 }, 300);
    });

    // ── Sort change ─────────────────────────────────────────────
    $('#sortSelect').on('change', function () {
        currentFilters.sort = $(this).val();
        doSearch(currentFilters, 1, false);
    });

    // ── Load more ───────────────────────────────────────────────
    $('#loadMoreBtn').on('click', function () {
        if (isLoading) return;
        currentPage++;
        doSearch(currentFilters, currentPage, true);
    });

    // ── Fungsi search ───────────────────────────────────────────
    function doSearch(params, page, append) {
        if (isLoading) return;
        isLoading = true;

        const $btn = $('#searchBtn');
        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-2" style="width:14px;height:14px;border-width:2px;"></span>Mencari...');

        if (!append) {
            $('#tutorResults').html(loadingGrid(6));
            $('#loadMoreWrapper').hide();
        } else {
            $('#loadMoreBtn').prop('disabled', true)
                .html('<span class="tk-spinner me-2 tk-spinner-dark" style="width:14px;height:14px;border-width:2px;"></span>Memuat...');
        }

        $.ajax({
            url: '/api/tutor/search',
            method: 'GET',
            data: { ...params, page: page },
            success: function (res) {
                const tutors   = res.data     || [];
                const hasMore  = res.has_more || false;
                const total    = res.total    || 0;

                if (!append) {
                    renderTutorCards(tutors);
                    $('#resultsSubtitle').text(total + ' tutor ditemukan');
                } else {
                    // Append cards
                    if (tutors.length) {
                        const $newRow = $('<div class="row g-4 mt-0">');
                        appendTutorCards(tutors, $newRow);
                        $('#tutorResults').append($newRow);
                    }
                }

                // Tampilkan tombol load more
                if (hasMore) {
                    $('#loadMoreWrapper').css('display', 'block !important').show();
                    $('#loadMoreBtn').prop('disabled', false)
                        .html('<i class="bi bi-arrow-down-circle me-1"></i>Muat lebih banyak tutor');
                } else {
                    $('#loadMoreWrapper').hide();
                }
            },
            error: function () {
                if (!append) {
                    $('#tutorResults').html(errorState('Gagal memuat tutor. Silakan coba lagi.'));
                } else {
                    showToast('Gagal memuat tutor tambahan.', 'error');
                }
            },
            complete: function () {
                isLoading = false;
                $btn.prop('disabled', false)
                    .html('<i class="bi bi-search"></i><span>Cari Tutor</span>');
            }
        });
    }

    function updateResultsHeader(mapel) {
        if (mapel && mapel !== 'Semua Mata Pelajaran') {
            $('#resultsTitle').text('Tutor ' + mapel);
        } else {
            $('#resultsTitle').text('Semua Tutor');
        }
    }

    function appendTutorCards(tutors, $row) {
        tutors.forEach(function (t) {
            const initials = t.name.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
            const subjects = (t.subjects || []).map(s =>
                `<span class="tk-badge-subject">${s}</span>`
            ).join('');

            $row.append(`
            <div class="col-sm-6 col-lg-4">
                <div class="tk-tutor-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="tk-tutor-avatar">${initials}</div>
                        <div class="flex-1 min-w-0">
                            <div class="tk-tutor-name">${t.name}</div>
                            <div style="font-size:.75rem;color:var(--tk-text-muted);">${t.session_count || 0} sesi selesai</div>
                        </div>
                    </div>
                    <div class="mb-2">${subjects}</div>
                    <p class="text-muted small mb-3" style="font-size:.8125rem;line-height:1.5;
                       display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        ${t.bio || 'Tutor berpengalaman siap membantu belajarmu.'}
                    </p>
                    <div class="tk-divider"></div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="tk-tutor-price">Rp ${formatRupiah(t.hourly_rate)}</span>
                            <span class="tk-tutor-price-label">/jam</span>
                        </div>
                        <a href="/tutor/${t.id}" class="tk-btn-outline-primary">
                            <i class="bi bi-calendar3"></i> Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>`);
        });
    }

});
</script>
@endpush

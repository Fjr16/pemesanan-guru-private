/**
 * TutorKu — app.js
 * Global JS: CSRF setup, AJAX helpers, UI utilities
 */

/* ================================================================
   1. JQUERY AJAX — CSRF TOKEN GLOBAL SETUP
================================================================ */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json',
    }
});

/* ================================================================
   2. FLASH MESSAGE AUTO-DISMISS
================================================================ */
$(document).ready(function () {

    // Auto hide flash setelah 4 detik
    setTimeout(function () {
        $('#flash-container .alert').each(function () {
            $(this).alert('close');
        });
    }, 4000);

    /* ============================================================
       3. PASSWORD VISIBILITY TOGGLE
    ============================================================ */
    $(document).on('click', '.tk-password-toggle', function () {
        const $btn   = $(this);
        const $input = $btn.closest('.tk-input-group').find('input');
        const isPass = $input.attr('type') === 'password';

        $input.attr('type', isPass ? 'text' : 'password');
        $btn.find('i')
            .toggleClass('bi-eye',      !isPass)
            .toggleClass('bi-eye-slash', isPass);
    });

    /* ============================================================
       4. ROLE TAB — LOGIN & REGISTER
    ============================================================ */
    $(document).on('click', '.tk-role-tab', function () {
        const role = $(this).data('role');

        // Active state
        $('.tk-role-tab').removeClass('active');
        $(this).addClass('active');

        // Update hidden input
        $('#roleInput').val(role);

        // Tampilkan/sembunyikan field khusus tutor
        if (role === 'tutor') {
            $('#tutorExtraFields').slideDown(200);
        } else {
            $('#tutorExtraFields').slideUp(200);
        }
    });

    /* ============================================================
       5. LOADING BUTTON HELPER
    ============================================================ */
    // Tambahkan spinner ke tombol saat form submit
    $(document).on('submit', 'form[data-loading]', function () {
        const $btn = $(this).find('[type=submit]');
        const originalText = $btn.html();

        $btn.prop('disabled', true)
            .html('<span class="tk-spinner me-2"></span>Memproses...');

        // Simpan original text untuk restore jika perlu
        $btn.data('original-text', originalText);
    });

    /* ============================================================
       6. FETCH NOTIFIKASI (untuk user yang sudah login)
    ============================================================ */
    if ($('#notifDropdown').length) {
        fetchNotifications();

        // Refresh notifikasi setiap 60 detik
        setInterval(fetchNotifications, 60000);
    }

    /* ============================================================
       7. HOME — SEARCH FORM AJAX
    ============================================================ */
    $('#searchForm').on('submit', function (e) {
        e.preventDefault();
        const mapelId  = $('#searchMapel').val();
        const hariId   = $('#searchHari').val();

        // Tampilkan loading di hasil
        $('#tutorResults').html(loadingGrid());

        $.ajax({
            url: '/api/tutor/search',
            method: 'GET',
            data: { mata_pelajaran_id: mapelId, hari: hariId },
            success: function (res) {
                renderTutorCards(res.data);
            },
            error: function () {
                $('#tutorResults').html(errorState('Gagal memuat tutor. Silakan coba lagi.'));
            }
        });
    });

    /* ============================================================
       8. DYNAMIC LOAD: TUTOR CARDS saat halaman home pertama kali
    ============================================================ */
    if ($('#tutorResults').length && $('#tutorResults').data('auto-load')) {
        loadTutors();
    }

    /* ============================================================
       9. KONFIRMASI HAPUS / AKSI BERBAHAYA
    ============================================================ */
    $(document).on('click', '[data-confirm]', function (e) {
        const msg = $(this).data('confirm') || 'Apakah Anda yakin?';
        if (!confirm(msg)) {
            e.preventDefault();
            return false;
        }
    });

    /* ============================================================
       10. TOOLTIP INIT (Bootstrap 5)
    ============================================================ */
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(el => new bootstrap.Tooltip(el));

}); // end document.ready


/* ================================================================
   FUNCTIONS
================================================================ */

/**
 * Fetch notifikasi user via AJAX
 */
function fetchNotifications() {
    $.ajax({
        url: '/api/notifications',
        method: 'GET',
        success: function (res) {
            const items = res.data || [];
            const $count = $('#notifCount');
            const $list  = $('#notifList');
            const $empty = $('#notifEmpty');

            if (items.length === 0) {
                $count.hide();
                $empty.show();
                return;
            }

            // Tampilkan badge
            $count.text(items.length).show();
            $empty.hide();

            // Render item notifikasi
            let html = '<li><h6 class="dropdown-header">Notifikasi</h6></li>';
            items.forEach(function (n) {
                html += `
                    <li>
                        <a class="dropdown-item py-2 ${n.read_at ? '' : 'fw-500'}"
                           href="${n.url || '#'}"
                           data-notif-id="${n.id}">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi ${n.icon || 'bi-bell'} mt-1 text-primary"></i>
                                <div>
                                    <div class="small">${n.message}</div>
                                    <div class="text-muted" style="font-size:.7rem;">${n.time}</div>
                                </div>
                            </div>
                        </a>
                    </li>`;
            });

            html += `<li><hr class="dropdown-divider"></li>
                     <li><a class="dropdown-item text-center small text-primary" href="/notifications">
                         Lihat semua notifikasi
                     </a></li>`;

            $list.html(html);
        },
        error: function () { /* silent fail */ }
    });
}

/**
 * Load tutor cards (halaman home, initial load)
 */
function loadTutors(params = {}) {
    const $container = $('#tutorResults');
    $container.html(loadingGrid());

    $.ajax({
        url: '/api/tutor/search',
        method: 'GET',
        data: params,
        success: function (res) {
            renderTutorCards(res.data);
        },
        error: function () {
            $container.html(errorState('Gagal memuat data tutor.'));
        }
    });
}

/**
 * Render array of tutor objects menjadi Bootstrap card grid
 */
function renderTutorCards(tutors) {
    const $container = $('#tutorResults');

    if (!tutors || tutors.length === 0) {
        $container.html(emptyState(
            'bi-person-x',
            'Tutor tidak ditemukan',
            'Coba ubah filter mata pelajaran atau hari.'
        ));
        return;
    }

    let html = '<div class="row g-4">';
    tutors.forEach(function (t) {
        const initials = t.name.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
        const stars    = renderStars(t.rating || 0);
        const subjects = (t.subjects || []).map(s =>
            `<span class="tk-badge-subject">${s}</span>`
        ).join('');

        html += `
        <div class="col-sm-6 col-lg-4">
            <div class="tk-tutor-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="tk-tutor-avatar">${initials}</div>
                    <div class="flex-1 min-w-0">
                        <div class="tk-tutor-name">${t.name}</div>
                        <div class="tk-stars">${stars}
                            <span class="text-muted ms-1" style="font-size:.75rem;">${t.rating_count || 0} ulasan</span>
                        </div>
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
        </div>`;
    });

    html += '</div>';
    $container.html(html);
}

/**
 * Render bintang rating
 */
function renderStars(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
            html += '<i class="bi bi-star-fill"></i>';
        } else if (i - rating < 1 && i - rating > 0) {
            html += '<i class="bi bi-star-half"></i>';
        } else {
            html += '<i class="bi bi-star"></i>';
        }
    }
    return `<span class="tk-stars">${html}</span>`;
}

/**
 * Format angka ke format Rupiah singkat
 */
function formatRupiah(angka) {
    if (!angka) return '0';
    return parseInt(angka).toLocaleString('id-ID');
}

/**
 * Loading skeleton grid (3 kolom)
 */
function loadingGrid(cols = 6) {
    let html = '<div class="row g-4">';
    for (let i = 0; i < cols; i++) {
        html += `
        <div class="col-sm-6 col-lg-4">
            <div class="tk-tutor-card">
                <div class="d-flex gap-3 mb-3">
                    <div class="skeleton" style="width:52px;height:52px;border-radius:50%;"></div>
                    <div class="flex-1">
                        <div class="skeleton skeleton-text mb-2" style="width:60%;"></div>
                        <div class="skeleton skeleton-text" style="width:40%;height:12px;"></div>
                    </div>
                </div>
                <div class="skeleton skeleton-text mb-2" style="width:35%;height:22px;border-radius:100px;"></div>
                <div class="skeleton skeleton-text mb-1"></div>
                <div class="skeleton skeleton-text" style="width:70%;"></div>
                <div class="tk-divider"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="skeleton skeleton-text" style="width:30%;"></div>
                    <div class="skeleton" style="width:100px;height:32px;border-radius:6px;"></div>
                </div>
            </div>
        </div>`;
    }
    html += '</div>';
    return html;
}

/**
 * Empty state component
 */
function emptyState(icon, title, desc) {
    return `
    <div class="text-center py-5">
        <i class="bi ${icon} text-muted" style="font-size:3rem;opacity:.35;"></i>
        <h6 class="mt-3 text-muted fw-500">${title}</h6>
        <p class="text-muted small mb-0">${desc}</p>
    </div>`;
}

/**
 * Error state component
 */
function errorState(msg) {
    return `
    <div class="text-center py-5">
        <i class="bi bi-exclamation-circle text-danger" style="font-size:3rem;opacity:.5;"></i>
        <h6 class="mt-3 text-muted fw-500">Terjadi Kesalahan</h6>
        <p class="text-muted small mb-3">${msg}</p>
        <button class="tk-btn-outline-primary" onclick="loadTutors()">
            <i class="bi bi-arrow-clockwise"></i> Coba Lagi
        </button>
    </div>`;
}

/**
 * Show toast notification (Bootstrap toast)
 */
function showToast(message, type = 'success') {
    const icons = {
        success: 'bi-check-circle-fill text-success',
        error:   'bi-exclamation-circle-fill text-danger',
        warning: 'bi-exclamation-triangle-fill text-warning',
        info:    'bi-info-circle-fill text-primary',
    };

    const toastId = 'toast-' + Date.now();
    const html = `
    <div id="${toastId}" class="toast align-items-center border-0 shadow-sm"
         role="alert" aria-live="assertive" data-bs-autohide="true" data-bs-delay="3500"
         style="position:fixed;top:1.5rem;right:1.5rem;z-index:9999;min-width:280px;">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi ${icons[type] || icons.info}"></i>
                <span style="font-size:.875rem;">${message}</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>`;

    $('body').append(html);
    const toastEl = document.getElementById(toastId);
    const toast   = new bootstrap.Toast(toastEl);
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

/* ================================================================
   SKELETON CSS — inject jika belum ada di stylesheet
================================================================ */
(function injectSkeletonCSS() {
    if (document.getElementById('tk-skeleton-css')) return;
    const style = document.createElement('style');
    style.id = 'tk-skeleton-css';
    style.textContent = `
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.4s infinite;
            border-radius: 4px;
        }
        .skeleton-text { height: 14px; width: 100%; }
        @keyframes skeleton-shimmer { to { background-position: -200% 0; } }
    `;
    document.head.appendChild(style);
})();

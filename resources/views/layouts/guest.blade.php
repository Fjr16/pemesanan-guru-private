<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TutorKu')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="tk-auth-body">

    <div class="tk-auth-wrapper">

        {{-- Left panel: brand + features --}}
        <div class="tk-auth-left d-none d-lg-flex flex-column justify-content-between">
            <div>
                <a href="{{ route('home') }}" class="tk-brand mb-5 d-inline-flex align-items-center gap-2 text-decoration-none">
                    <span class="tk-brand-dot"></span>
                    <span class="text-white fw-500 fs-5">TutorKu</span>
                </a>

                <h2 class="text-white fw-500 mb-3" style="font-size:1.75rem;line-height:1.3;">
                    Platform Guru Privat<br>Terpercaya #1
                </h2>
                <p class="text-white-50 mb-4">
                    Temukan tutor terbaik sesuai kebutuhan, jadwal, dan budgetmu.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="tk-auth-check"><i class="bi bi-check"></i></div>
                        <span class="text-white-75">200+ tutor terverifikasi admin</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="tk-auth-check"><i class="bi bi-check"></i></div>
                        <span class="text-white-75">Jadwal fleksibel, booking mudah</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="tk-auth-check"><i class="bi bi-check"></i></div>
                        <span class="text-white-75">Notifikasi email & konfirmasi WA</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="tk-auth-check"><i class="bi bi-check"></i></div>
                        <span class="text-white-75">Pembayaran via transfer, e-wallet, QRIS</span>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="row g-3 mt-2">
                <div class="col-4">
                    <div class="tk-auth-stat">
                        <div class="tk-auth-stat-num">200+</div>
                        <div class="tk-auth-stat-lbl">Tutor aktif</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="tk-auth-stat">
                        <div class="tk-auth-stat-num">1.2K+</div>
                        <div class="tk-auth-stat-lbl">Sesi selesai</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="tk-auth-stat">
                        <div class="tk-auth-stat-num">98%</div>
                        <div class="tk-auth-stat-lbl">Kepuasan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right panel: form --}}
        <div class="tk-auth-right d-flex flex-column justify-content-center">

            {{-- Mobile brand --}}
            <a href="{{ route('home') }}" class="tk-brand d-flex d-lg-none align-items-center gap-2 text-decoration-none mb-4">
                <div class="tk-brand-dot-dark"></div>
                <span class="fw-500" style="color:#1e40af;font-size:1.1rem;">TutorKu</span>
            </a>

            @yield('auth-content')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        flatpickr(".tanggal-input", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
            locale: "id",
            maxDate: "today",
            disableMobile: true,      // tetap pakai flatpickr di mobile (bukan native)
        });
    </script>

    @stack('scripts')
</body>
</html>

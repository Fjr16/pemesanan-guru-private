<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TutorKu') — Sistem Pemesanan Guru Privat</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Custom CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    {{-- ============================================================
         NAVBAR
    ============================================================ --}}
    <nav class="navbar navbar-expand-lg tk-navbar">
        <div class="container">

            {{-- Brand --}}
            <a class="navbar-brand tk-brand" href="{{ route('home') }}">
                <span class="tk-brand-dot"></span>
                TutorKu
            </a>

            {{-- Mobile toggle --}}
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="bi bi-list text-white fs-5"></i>
            </button>

            {{-- Nav links --}}
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link tk-nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">
                            Cari Tutor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tk-nav-link" href="#">Cara Kerja</a>
                    </li>
                </ul>

                {{-- Right side: guest vs authenticated --}}
                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn tk-btn-ghost">Masuk</a>
                        <a href="{{ route('register') }}" class="btn tk-btn-white">Daftar</a>
                    @endguest

                    @auth
                        {{-- Notification bell --}}
                        <div class="dropdown me-1">
                            <button class="btn tk-btn-ghost position-relative" type="button"
                                    id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger tk-notif-badge"
                                      id="notifCount" style="display:none;">0</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end tk-dropdown" id="notifList"
                                aria-labelledby="notifDropdown" style="min-width:280px;">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <li><p class="text-muted small px-3 py-2 mb-0" id="notifEmpty">Belum ada notifikasi.</p></li>
                            </ul>
                        </div>

                        {{-- User menu --}}
                        <div class="dropdown">
                            <button class="btn tk-user-btn d-flex align-items-center gap-2"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="tk-avatar-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span class="d-none d-lg-inline text-white small">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down text-white small"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end tk-dropdown">
                                <li>
                                    <span class="dropdown-item-text small text-muted">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                @if(Auth::user()->role === 'siswa')
                                    <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}">
                                        <i class="bi bi-grid me-2"></i>Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.pemesanan') }}">
                                        <i class="bi bi-calendar-check me-2"></i>Pemesanan Saya
                                    </a></li>
                                @elseif(Auth::user()->role === 'tutor')
                                    <li><a class="dropdown-item" href="{{ route('tutor.dashboard') }}">
                                        <i class="bi bi-grid me-2"></i>Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('tutor.jadwal') }}">
                                        <i class="bi bi-calendar3 me-2"></i>Jadwal Saya
                                    </a></li>
                                @elseif(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Admin Panel
                                    </a></li>
                                @endif

                                <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================================
         FLASH MESSAGES
    ============================================================ --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div class="container mt-3" id="flash-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show tk-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show tk-alert" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show tk-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show tk-alert" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <footer class="tk-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="tk-footer-logo-dot"></div>
                        <span class="fw-500 text-white">TutorKu</span>
                    </div>
                    <p class="text-white-50 small mb-0">
                        Platform pemesanan guru privat terpercaya untuk pelajar Indonesia.
                    </p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white small fw-500 mb-3">Platform</h6>
                    <ul class="list-unstyled">
                        <li class="mb-1"><a href="#" class="tk-footer-link">Cari Tutor</a></li>
                        <li class="mb-1"><a href="#" class="tk-footer-link">Cara Kerja</a></li>
                        <li class="mb-1"><a href="#" class="tk-footer-link">Daftar Tutor</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white small fw-500 mb-3">Bantuan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-1"><a href="#" class="tk-footer-link">FAQ</a></li>
                        <li class="mb-1"><a href="#" class="tk-footer-link">Kontak</a></li>
                        <li class="mb-1"><a href="#" class="tk-footer-link">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white small fw-500 mb-3">Hubungi Kami</h6>
                    <p class="text-white-50 small mb-1">
                        <i class="bi bi-envelope me-2"></i>hello@tutorku.id
                    </p>
                    <p class="text-white-50 small">
                        <i class="bi bi-whatsapp me-2"></i>+62 812-3456-7890
                    </p>
                </div>
            </div>
            <hr class="border-white border-opacity-10 my-4">
            <p class="text-white-50 small text-center mb-0">
                &copy; {{ date('Y') }} TutorKu. Hak cipta dilindungi.
            </p>
        </div>
    </footer>

    {{-- ============================================================
         SCRIPTS
    ============================================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>

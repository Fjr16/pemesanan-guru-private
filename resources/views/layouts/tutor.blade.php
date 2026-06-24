<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorKu — Tutor Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6fb;
            color: #1a1a2e;
            margin: 0;
            min-height: 100vh;
        }

        .tk-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .tk-sidebar {
            width: 230px;
            flex-shrink: 0;
            background: #1e2d6b;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .tk-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            text-decoration: none;
        }
        .tk-brand-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #7f9cf5;
            flex-shrink: 0;
        }
        .tk-brand-name {
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .01em;
        }
        .tk-user-strip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .tk-user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(127,156,245,.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #c7d4fd;
            flex-shrink: 0;
        }
        .tk-user-name {
            font-size: 13px;
            font-weight: 500;
            color: #fff;
        }
        .tk-user-role {
            font-size: 11px;
            color: rgba(255,255,255,.4);
        }
        .tk-nav-section {
            padding: 14px 20px 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.3);
            font-weight: 600;
        }
        .tk-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: rgba(255,255,255,.55);
            font-size: 13px;
            font-weight: 400;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s;
        }
        .tk-nav-link:hover {
            background: rgba(255,255,255,.06);
            color: rgba(255,255,255,.85);
            text-decoration: none;
        }
        .tk-nav-link.active {
            background: rgba(127,156,245,.15);
            color: #c7d4fd;
            border-left-color: #7f9cf5;
            font-weight: 500;
        }
        .tk-nav-link .bi {
            font-size: 15px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }
        .tk-nav-badge {
            margin-left: auto;
            background: #f59e0b;
            color: #451a03;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
            line-height: 1.4;
        }
        .tk-sidebar-footer {
            margin-top: auto;
            padding: 12px 20px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .tk-logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            color: rgba(255,255,255,.35);
            font-size: 13px;
            font-family: inherit;
            padding: 6px 0;
            cursor: pointer;
            width: 100%;
            transition: color .15s;
        }
        .tk-logout-btn:hover { color: rgba(255,255,255,.65); }
        .tk-logout-btn .bi { font-size: 15px; }

        /* ── Main ── */
        .tk-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .tk-topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #e8eaf0;
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .tk-topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
        }
        .tk-topbar-subtitle {
            font-size: 12px;
            color: #8890a8;
            margin-left: 8px;
            font-weight: 400;
        }
        .tk-topbar-spacer { flex: 1; }
        .tk-topbar-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid #d0d5e8;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 13px;
            color: #1e2d6b;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }
        .tk-topbar-btn:hover { background: #f0f3ff; }
        .tk-topbar-btn .bi { font-size: 14px; }

        /* ── Content ── */
        .tk-content { padding: 24px 28px; flex: 1; }
    </style>
    @stack('styles')
</head>
<body>

<div class="tk-shell">

    <!-- SIDEBAR -->
    <aside class="tk-sidebar">
        <a href="{{ route('tutor.dashboard') }}" class="tk-brand">
            <span class="tk-brand-dot"></span>
            <span class="tk-brand-name">TutorKu</span>
        </a>

        <div class="tk-user-strip">
            @if(Auth::user()->tutor && Auth::user()->tutor->foto)
                <img src="{{ asset('storage/' . Auth::user()->tutor->foto) }}" alt=""
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
                <div class="tk-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'T', 0, 2)) }}</div>
            @endif
            <div>
                <div class="tk-user-name">{{ Auth::user()->name ?? 'Tutor' }}</div>
                <div class="tk-user-role">Tutor</div>
            </div>
        </div>

        <div class="tk-nav-section">Menu</div>
        @if(Auth::user()->tutor && Auth::user()->tutor->status !== 'active')
            <a href="{{ route('tutor.pending') }}" class="tk-nav-link {{ request()->routeIs('tutor.pending') ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i> Status Pendaftaran
            </a>
        @else
            <a href="{{ route('tutor.dashboard') }}" class="tk-nav-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('tutor.pemesanan') }}" class="tk-nav-link {{ request()->routeIs('tutor.pemesanan*') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i> Pesanan Masuk
                @if(($pendingCount ?? 0) > 0)
                    <span class="tk-nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('tutor.jadwal') }}" class="tk-nav-link {{ request()->routeIs('tutor.jadwal') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Jadwal Saya
            </a>
            <a href="{{ route('tutor.profil') }}" class="tk-nav-link {{ request()->routeIs('tutor.profil*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil
            </a>
        @endif

        <div class="tk-sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="tk-logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="tk-main">

        <!-- Topbar -->
        <div class="tk-topbar">
            <span class="tk-topbar-title">@yield('page-title', 'Tutor Panel')</span>
            <span class="tk-topbar-subtitle">{{ now()->translatedFormat('l, d F Y') }} · TutorKu</span>
            <div class="tk-topbar-spacer"></div>
            @yield('topbar-actions')
        </div>

        <!-- Content -->
        <div class="tk-content">
            @yield('content')
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

<script>
    flatpickr(".tanggal-input", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        locale: "id",
        maxDate: "today",
        disableMobile: true,
    });
</script>
</body>
</html>

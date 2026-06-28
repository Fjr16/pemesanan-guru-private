<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorKu — Admin Panel</title>

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

        /* ── Stat cards ── */
        .tk-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .tk-stat {
            background: #fff;
            border: 1px solid #e8eaf0;
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .tk-stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }
        .tk-stat-icon.blue   { background: #eff6ff; color: #1e40af; }
        .tk-stat-icon.indigo { background: #eef2ff; color: #3730a3; }
        .tk-stat-icon.amber  { background: #fffbeb; color: #b45309; }
        .tk-stat-icon.green  { background: #f0fdf4; color: #15803d; }
        .tk-stat-label { font-size: 12px; color: #8890a8; margin-bottom: 4px; }
        .tk-stat-value { font-size: 24px; font-weight: 600; color: #1a1a2e; line-height: 1.2; }
        .tk-stat-sub   { font-size: 11px; color: #8890a8; margin-top: 3px; }
        .tk-stat-sub.up   { color: #15803d; }
        .tk-stat-sub.warn { color: #b45309; }

        /* ── Mid cards ── */
        .tk-mid-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
            margin-bottom: 20px;
        }
        .tk-mid-card {
            background: #fff;
            border: 1px solid #e8eaf0;
            border-radius: 12px;
            padding: 20px 22px;
        }
        .tk-mid-card.accent-indigo { background: #1e2d6b; border-color: #1e2d6b; }
        .tk-mid-card.accent-indigo .tk-mid-label { color: rgba(255,255,255,.55); }
        .tk-mid-card.accent-indigo .tk-mid-value { color: #fff; }
        .tk-mid-card.accent-indigo .tk-mid-sub   { color: rgba(255,255,255,.45); }
        .tk-mid-card.accent-teal { background: #064e3b; border-color: #064e3b; }
        .tk-mid-card.accent-teal .tk-mid-label { color: rgba(255,255,255,.55); }
        .tk-mid-card.accent-teal .tk-mid-value { color: #fff; }
        .tk-mid-card.accent-teal .tk-mid-sub   { color: rgba(255,255,255,.45); }
        .tk-mid-label { font-size: 12px; color: #8890a8; margin-bottom: 10px; font-weight: 500; }
        .tk-mid-value { font-size: 32px; font-weight: 700; color: #1a1a2e; line-height: 1; margin-bottom: 8px; }
        .tk-mid-value.currency { font-size: 22px; }
        .tk-mid-sub   { font-size: 12px; color: #8890a8; }
        .up { color: #16a34a; }

        /* ── Bottom panels ── */
        .tk-bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .tk-panel {
            background: #fff;
            border: 1px solid #e8eaf0;
            border-radius: 12px;
            padding: 18px 20px;
        }
        .tk-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .tk-panel-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
        }
        .tk-panel-title .bi { font-size: 14px; color: #1e2d6b; }
        .tk-panel-link {
            font-size: 12px;
            color: #1e2d6b;
            text-decoration: none;
            font-weight: 500;
        }
        .tk-panel-link:hover { text-decoration: underline; }

        .tk-table { width: 100%; font-size: 12px; border-collapse: collapse; }
        .tk-table thead th {
            color: #8890a8;
            font-weight: 500;
            padding: 0 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e8eaf0;
        }
        .tk-table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #f0f2f8;
            color: #1a1a2e;
            vertical-align: middle;
        }
        .tk-table tbody tr:last-child td { border-bottom: none; }

        .tk-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            white-space: nowrap;
        }
        .tk-badge-pending { background: #fffbeb; color: #92400e; }
        .tk-badge-active  { background: #f0fdf4; color: #15803d; }
        .tk-badge-danger  { background: #fef2f2; color: #991b1b; }
        .tk-badge-info    { background: #eff6ff; color: #1e40af; }

        .tk-empty {
            text-align: center;
            padding: 28px 0;
            color: #8890a8;
            font-size: 13px;
        }
        .tk-empty .bi { font-size: 28px; display: block; margin-bottom: 8px; opacity: .4; }

        .tk-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #d0d5e8;
            background: #fff;
            color: #1e2d6b;
            text-decoration: none;
            transition: background .15s;
        }
        .tk-action-btn:hover { background: #f0f3ff; }

        .tk-right-col { display: flex; flex-direction: column; gap: 14px; }

        .tk-mapel-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
        }
        .tk-mapel-row + .tk-mapel-row { border-top: 1px solid #f0f2f8; }
        .tk-mapel-rank {
            width: 22px; height: 22px;
            border-radius: 50%;
            background: #eef2ff;
            color: #3730a3;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .tk-mapel-bar-track {
            width: 60px; height: 5px;
            border-radius: 3px;
            background: #f0f2f8;
            overflow: hidden;
            flex-shrink: 0;
        }
        .tk-mapel-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: #1e2d6b;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="tk-shell">

    <!-- SIDEBAR -->
    <aside class="tk-sidebar">
        <a href="#" class="tk-brand">
            <span class="tk-brand-dot"></span>
            <span class="tk-brand-name">TutorKu</span>
        </a>

        <div class="tk-user-strip">
            <div class="tk-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
            <div>
                <div class="tk-user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="tk-user-role">{{ Auth::user()->email ?? '' }}</div>
            </div>
        </div>

        <div class="tk-nav-section">Dashboard</div>
        <a href="{{ route('admin.dashboard') }}" class="tk-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="tk-nav-section" style="margin-top:4px">Manajemen</div>
        <a href="{{ route('admin.tutor') }}" class="tk-nav-link {{ request()->routeIs('admin.tutor*') ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i> Tutor
        </a>
        <a href="{{ route('admin.siswa') }}" class="tk-nav-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Siswa
        </a>
        <a href="{{ route('admin.mapel.index') }}" class="tk-nav-link {{ request()->routeIs('admin.mapel*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Mata Pelajaran
        </a>
        <a href="{{ route('admin.transaksi') }}" class="tk-nav-link {{ request()->routeIs('admin.transaksi*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Transaksi
        </a>

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
            <span class="tk-topbar-title">@yield('page-title', 'Admin Panel')</span>
            <span class="tk-topbar-subtitle">{{ now()->translatedFormat('l, d F Y') }} · TutorKu Management System</span>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

<script>
    flatpickr(".tanggal-input", {
        dateFormat: "Y-m-d",      // format yang dikirim ke server
        altInput: true,           // tampilkan format berbeda ke user
        altFormat: "d F Y",       // contoh: 15 Juni 2026
        locale: "id",
        maxDate: "today",         // cocok untuk tanggal lahir
        disableMobile: true,      // tetap pakai flatpickr di mobile (bukan native)
    });
</script>
</body>
</html>

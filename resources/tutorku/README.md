# TutorKu — Frontend Laravel Blade
## Batch 1: Layout, Auth & Homepage

---

## Struktur File yang Dibuat

```
tutorku/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php          ← Layout utama (navbar + footer)
│   │   └── guest.blade.php        ← Layout auth (split panel kiri-kanan)
│   ├── auth/
│   │   ├── login.blade.php        ← Halaman login (role tabs)
│   │   └── register.blade.php     ← Halaman register multi-step
│   ├── components/
│   │   └── tutor-card.blade.php   ← Card tutor reusable
│   └── home.blade.php             ← Homepage (hero + search + listing)
│
├── public/
│   ├── css/app.css                ← Semua styling custom (CSS vars, utility)
│   └── js/app.js                  ← Global JS (jQuery AJAX, helpers, toast)
│
├── routes/
│   └── web.php                    ← Semua routing (public/siswa/tutor/admin/api)
│
└── app/Http/
    ├── Controllers/
    │   ├── HomeController.php
    │   ├── Auth/
    │   │   ├── LoginController.php
    │   │   └── RegisterController.php
    │   └── Api/
    │       ├── TutorSearchController.php
    │       └── MataPelajaranController.php
    └── Middleware/
        └── CheckRole.php          ← Middleware proteksi role
```

---

## Setup Cepat

### 1. Daftarkan Middleware (Laravel 11 — bootstrap/app.php)
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

### 2. Atau Laravel 10 — app/Http/Kernel.php
```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### 3. Pastikan model User punya kolom `role`
```php
// Migration: tambah ke tabel users
$table->enum('role', ['siswa', 'tutor', 'admin'])->default('siswa');
```

### 4. Asset
```bash
# Tidak perlu npm/vite — semua CSS & JS sudah static
# Letakkan app.css di public/css/app.css
# Letakkan app.js  di public/js/app.js
```

---

## Halaman Selanjutnya (Batch 2)

- `tutor/show.blade.php`       — Profil detail tutor + tabel jadwal
- `siswa/dashboard.blade.php`  — Dashboard siswa
- `siswa/pemesanan.blade.php`  — Riwayat & status pemesanan
- `siswa/pembayaran.blade.php` — Halaman pembayaran
- `tutor/dashboard.blade.php`  — Dashboard tutor + konfirmasi booking
- `tutor/jadwal.blade.php`     — Atur jadwal ketersediaan
- `admin/dashboard.blade.php`  — Admin panel lengkap

---

## Dependency CDN (sudah di layout)
- Bootstrap 5.3.3
- Bootstrap Icons 1.11.3
- jQuery 3.7.1
- Google Fonts — Inter

Tidak ada npm build step yang diperlukan.

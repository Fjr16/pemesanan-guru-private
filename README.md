# TutorKu — Sistem Pemesanan Guru Privat

Platform pemesanan guru privat online dengan integrasi pembayaran Midtrans (QRIS & Transfer Bank).

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Vite 8, Tailwind CSS 4, jQuery 3.7, Bootstrap 5.3 |
| Database | MySQL |
| Payment | Midtrans (Snap) |

## Persiapan

Pastikan sudah terinstall:

- PHP >= 8.3 (XAMPP / Laragon)
- Composer
- Node.js & npm
- MySQL

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/pemesanan-guru-private.git
cd pemesanan-guru-private
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
copy .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Buka `.env`, sesuaikan kredensial database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_tutor
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `db_tutor` di MySQL:

```sql
CREATE DATABASE db_tutor;
```

Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

### 5. Konfigurasi Midtrans

Daftar di [Midtrans](https://midtrans.com), lalu dapatkan API keys dari dashboard.

Buka `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false
```

| Environment | `MIDTRANS_IS_PRODUCTION` | `MIDTRANS_SERVER_KEY` |
|-------------|-------------------------|-----------------------|
| Development | `false` | `SB-Mid-server-...` |
| Production  | `true` | `Mid-server-...` |

### 6. Build Asset

```bash
npm run build
```

### 7. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

## Konfigurasi Midtrans Dashboard

Untuk pembayaran berfungsi, set Notification URL di Midtrans Dashboard:

```
Settings → Payment Notification → Notification URL:

https://domain.com/api/midtrans/callback
```

Untuk development lokal, gunakan ngrok:

```bash
ngrok http 8000
```

Lalu set Notification URL ke URL ngrok:

```
https://xxxx.ngrok-free.dev/api/midtrans/callback
```

### Metode Pembayaran

Aktifkan di Midtrans Dashboard → Settings → Payment Types:

- QRIS
- BCA Virtual Account
- BNI Virtual Account
- BRI Virtual Account

## Testing Pembayaran

### Sandbox (Development)

| Metode | Cara Test |
|--------|----------|
| Transfer Bank (VA) | Midtrans Dashboard → Payment Simulator |
| QRIS | Midtrans Dashboard → Transactions → klik transaksi → Settlement |

### Alur Test

1. Login sebagai siswa
2. Buat pemesanan baru (booking tutor)
3. Login sebagai tutor, terima pesanan
4. Login kembali sebagai siswa, klik "Bayar"
5. Pilih metode di Snap popup, selesaikan pembayaran
6. Verifikasi status berubah di halaman pemesanan

## Alur Status Pesanan

```
pending → confirmed (menunggu bayar) → confirmed (sudah dibayar) → complete
   ↑              ↑                           ↑                        ↑
dibuat       tutor terima               siswa bayar              tutor klik Selesai
```

## Role Pengguna

| Role | Akses |
|------|-------|
| Siswa | Booking tutor, bayar sesi, lihat riwayat |
| Tutor | Kelola jadwal, konfirmasi pesanan, tandai selesai |
| Admin | Verifikasi tutor, kelola mata pelajaran, lihat transaksi |

## Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel
│   │   ├── Api/            # API endpoints (booking, search, midtrans callback)
│   │   ├── Auth/           # Login & register
│   │   ├── Siswa/          # Siswa dashboard, pemesanan, pembayaran
│   │   └── Tutor/          # Tutor dashboard, jadwal, pemesanan
│   └── Middleware/          # CheckRole, CheckTutorStatus
├── Models/                  # Eloquent models
└── Services/
    └── MidtransService.php  # Midtrans integration

config/
└── midtrans.php             # Midtrans configuration

routes/
├── web.php                  # Web routes
└── api.php                  # API routes (midtrans callback)
```

## License

MIT

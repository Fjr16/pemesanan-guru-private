# TutorKu — Sistem Pemesanan Guru Privat

Platform pemesanan guru privat online dengan integrasi pembayaran Midtrans (QRIS & Transfer Bank) dan notifikasi email.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Vite 8, Tailwind CSS 4, jQuery 3.7, Bootstrap 5.3 |
| Database | MySQL |
| Payment | Midtrans (Snap) |
| Email | Laravel Mail (Markdown Mailable) |
| Queue | Laravel Queue (Database Driver) |

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

### 6. Konfigurasi Email

Aplikasi menggunakan Laravel Mail dengan Markdown Mailable dan dikirim via queue. Konfigurasi email di `.env`:

```env
QUEUE_CONNECTION=database
```

#### Development (Mailpit)

Untuk development, gunakan [Mailpit](https://mailpit.axllent.org) sebagai SMTP server lokal. Email tidak benar-benar terkirim ke penerima, melainkan ditangkap dan ditampilkan di web UI Mailpit.

Install Mailpit (Linux amd64):

```bash
curl -sL https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz -o /tmp/mailpit.tar.gz
tar -xzf /tmp/mailpit.tar.gz -C /tmp/ mailpit
chmod +x /tmp/mailpit
```

Jalankan Mailpit:

```bash
nohup /tmp/mailpit -s "[::]:1025" -l "[::]:8025" > /tmp/mailpit.log 2>&1 &
```

Konfigurasi `.env` untuk Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@pemesanan-guru.test"
MAIL_FROM_NAME="${APP_NAME}"
```

Akses Mailpit UI di browser: **http://localhost:8025**

#### Production (SMTP)

Untuk production, gunakan SMTP provider (Gmail, Mailgun, dll):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="TutorKu"
```

### 7. Build Asset

```bash
npm run build
```

### 8. Jalankan Aplikasi

Gunakan `composer dev` untuk menjalankan server, queue worker, logs, dan Vite sekaligus:

```bash
composer dev
```

Atau jalankan secara manual di terminal terpisah:

```bash
# Terminal 1: Server & Vite
php artisan serve
npm run dev

# Terminal 2: Queue Worker (wajib untuk email)
php artisan queue:listen --tries=1 --timeout=0
```

Buka browser: `http://localhost:8000`

> **Penting:** Queue worker (`php artisan queue:listen`) harus selalu berjalan agar email terkirim. Tanpa queue worker, email menumpuk di tabel `jobs` dan tidak diproses.

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

## Alur Notifikasi Email

Sistem mengirim email notifikasi ke siswa pada 2 titik:

```
┌──────────────────────────────────────────────────────────────────┐
│                       ALUR PEMESANAN                             │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ① Siswa booking tutor                                           │
│         │                                                        │
│         ▼                                                        │
│  ② Tutor konfirmasi / tolak                                      │
│         │                                                        │
│         ├── DITERIMA ──► 📧 Email #1: "Pesanan dikonfirmasi"    │
│         │                  + detail sesi                          │
│         │                  + tombol "Bayar Sekarang"              │
│         │                                                        │
│         └── DITOLAK ───► 📧 Email #1: "Pesanan ditolak"         │
│                           + tombol "Lihat Detail Pesanan"        │
│                                                                  │
│  ③ Siswa bayar via Midtrans                                      │
│         │                                                        │
│         ▼                                                        │
│  ④ Pembayaran sukses (Midtrans callback)                         │
│         │                                                        │
│         ▼                                                        │
│  ⑤ 📧 Email #2: "Pembayaran Berhasil"                           │
│        + detail pesanan & jadwal sesi                             │
│        + tombol "Lihat Detail Pesanan"                           │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Detail Email

| # | Trigger | Mailable Class | Template | Penerima |
|---|---------|---------------|----------|----------|
| 1a | Tutor klik "Terima" | `OrderConfirmedMail` | `emails/order-confirmed` | Siswa |
| 1b | Tutor klik "Tolak" | `OrderRejectedMail` | `emails/order-rejected` | Siswa |
| 2 | Midtrans callback (settlement/capture) | `PaymentSuccessMail` | `emails/payment-success` | Siswa |

### Cara Kerja Queue

Email dikirim via queue (`Mail::to()->queue()`), bukan langsung (`Mail::to()->send()`). Alur:

```
Controller → Mail::to()->queue() → tabel jobs → Queue Worker → SMTP → Inbox
```

Kelebihan: response HTTP tetap cepat karena email dikirim di background.

## Testing Email

### 1. Pastikan Mailpit Berjalan

```bash
curl -s http://localhost:8025/api/v1/messages
```

Jika berhasil, akan return JSON. Jika gagal, jalankan Mailpit:

```bash
nohup /tmp/mailpit -s "[::]:1025" -l "[::]:8025" > /tmp/mailpit.log 2>&1 &
```

### 2. Pastikan Queue Worker Berjalan

```bash
php artisan queue:listen --tries=1 --timeout=0
```

Atau gunakan `composer dev` yang sudah termasuk queue worker.

### 3. Alur Test Email

1. Login sebagai siswa, buat pemesanan baru
2. Login sebagai tutor, klik **"Terima"** pada pesanan
3. Buka **http://localhost:8025** — cek email masuk dengan subject "Pesanan Dikonfirmasi"
4. Login kembali sebagai siswa, lakukan pembayaran via Midtrans
5. Selesaikan pembayaran di Snap popup
6. Buka Mailpit — cek email masuk dengan subject "Pembayaran Berhasil"

### 4. Test Manual via Tinker

```bash
php artisan tinker
```

```php
use App\Models\Order;
use App\Mail\OrderConfirmedMail;
use Illuminate\Support\Facades\Mail;

$order = Order::with(['tutor.user', 'student.user', 'orderDetails'])->first();
Mail::to($order->student->user->email)->send(new OrderConfirmedMail($order));
```

Cek hasilnya di http://localhost:8025

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
├── Mail/
│   ├── OrderConfirmedMail.php    # Email: pesanan dikonfirmasi tutor
│   ├── OrderRejectedMail.php     # Email: pesanan ditolak tutor
│   └── PaymentSuccessMail.php    # Email: pembayaran berhasil
├── Models/                  # Eloquent models
└── Services/
    └── MidtransService.php  # Midtrans integration

config/
├── mail.php                 # Mail configuration
└── midtrans.php             # Midtrans configuration

resources/views/emails/
├── order-confirmed.blade.php    # Template: pesanan dikonfirmasi
├── order-rejected.blade.php     # Template: pesanan ditolak
└── payment-success.blade.php    # Template: pembayaran berhasil

routes/
├── web.php                  # Web routes
└── api.php                  # API routes (midtrans callback)
```

## License

MIT

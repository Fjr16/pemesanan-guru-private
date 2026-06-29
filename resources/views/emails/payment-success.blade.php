<x-mail::message>
# Pembayaran Berhasil

Halo **{{ $siswa->name }}**,

Pembayaran Anda telah berhasil diterima. Berikut adalah detail pesanan Anda sebagai pengingat jadwal.

## Detail Pesanan #{{ $order->id }}

| | |
|---|---|
| **Tutor** | {{ $tutor->name }} |
| **Tanggal** | {{ $order->tanggal }} |
| **Jam** | {{ $order->jam_range }} |
| **Durasi** | {{ $order->jumlah_jam }} jam |
| **Total Bayar** | Rp {{ number_format($order->total_payment, 0, ',', '.') }} |

### Jadwal Sesi
@foreach ($details as $detail)
- **{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('l, d F Y') }}** — {{ $detail->jam_start }} – {{ $detail->jam_end }} WIB
@endforeach

Pastikan Anda hadir tepat waktu sesuai jadwal yang tertera.

<x-mail::button :url="$detailUrl" color="primary">
Lihat Detail Pesanan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

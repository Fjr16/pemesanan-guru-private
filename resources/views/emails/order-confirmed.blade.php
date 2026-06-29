<x-mail::message>
# Pesanan Dikonfirmasi

Halo **{{ $siswa->name }}**,

Kabar baik! Tutor **{{ $tutor->name }}** telah mengkonfirmasi pesanan Anda.

## Detail Pesanan

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

<x-mail::button :url="$paymentUrl" color="success">
Bayar Sekarang
</x-mail::button>

Silakan lakukan pembayaran dalam **24 jam** sebelum pesanan otomatis dibatalkan.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

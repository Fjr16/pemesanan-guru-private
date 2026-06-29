<x-mail::message>
# Pesanan Ditolak

Halo **{{ $siswa->name }}**,

Mohon maaf, Tutor **{{ $tutor->name }}** tidak dapat memenuhi pesanan Anda.

## Detail Pesanan

| | |
|---|---|
| **Tutor** | {{ $tutor->name }} |
| **Tanggal** | {{ $order->tanggal }} |
| **Jam** | {{ $order->jam_range }} |
| **Status** | Ditolak |

Anda dapat mencari tutor lain yang tersedia atau melihat detail pesanan di bawah ini.

<x-mail::button :url="$detailUrl" color="primary">
Lihat Detail Pesanan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

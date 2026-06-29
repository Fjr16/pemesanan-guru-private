<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load(['tutor.user', 'student.user', 'orderDetails']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Ditolak - Tutor '.$this->order->tutor->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-rejected',
            with: [
                'order' => $this->order,
                'siswa' => $this->order->student,
                'tutor' => $this->order->tutor,
                'details' => $this->order->orderDetails,
                'detailUrl' => route('siswa.pemesanan.show', $this->order->id),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

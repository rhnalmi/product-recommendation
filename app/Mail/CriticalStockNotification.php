<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CriticalStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $lowStockItems;

    /**
     * Create a new message instance.
     * Kita akan mengirim data produk yang stoknya menipis ke email.
     */
    public function __construct(Collection $lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Stok Kritis',
        );
    }

    /**
     * Get the message content definition.
     * Arahkan ke file view untuk template email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.critical-stock',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
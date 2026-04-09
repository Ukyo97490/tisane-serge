<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Order  $order
     * @param string $type '24h' ou '1h'
     */
    public function __construct(
        public Order $order,
        public string $type = '24h',
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->type === '1h' ? '1 heure' : 'demain';

        return new Envelope(
            subject: 'Rappel : récupération de votre commande ' . $this->order->reference . ' ' . $label . ' - Tisane Lontan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-reminder',
        );
    }
}

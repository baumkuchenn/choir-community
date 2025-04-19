<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $purchase, $user, $concert, $event, $choirs, $purchaseDetails, $invoice, $tickets;

    /**
     * Create a new message instance.
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
        $this->user = $purchase->user;
        $this->concert = $purchase->concert;
        $this->event = $this->concert->event;
        $this->choirs = $this->event->choirs;
        $this->purchaseDetails = $purchase->ticketTypes;
        $this->invoice = $purchase->invoice;
        $this->tickets = $this->invoice->tickets;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Terima kasih untuk pembelian eticket melalui Choir Community',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'eticketing.emails.invoice',
            with: [
                'purchase' => $this->purchase,
                'user' => $this->user,
                'concert' => $this->concert,
                'event' => $this->event,
                'choirs' => $this->choirs,
                'purchaseDetails' => $this->purchaseDetails,
                'invoice' => $this->invoice,
                'tickets' => $this->tickets,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

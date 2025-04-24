<?php

namespace App\Mail;

use App\Models\TicketInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tickets, $invite, $concert, $event, $choir;

    /**
     * Create a new message instance.
     */
    public function __construct(TicketInvitation $invite)
    {
        $this->invite = $invite;
        $this->tickets = $invite->tickets;
        $this->concert = $this->tickets[0]->ticket_type->concert;
        $this->event = $this->concert->event;
        $this->choir = $this->event->choirs->firstWhere('pivot.penyelenggara', 'ya');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Undangan ' . $this->event->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'eticketing.emails.invitation',
            with: [
                'invite' => $this->invite,
                'tickets' => $this->tickets,
                'concert' => $this->concert,
                'event' => $this->event,
                'choir' => $this->choir,
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

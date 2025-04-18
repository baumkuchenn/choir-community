<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeliTiketNotification extends Notification
{
    use Queueable;
    protected $concert;

    /**
     * Create a new notification instance.
     */
    public function __construct($concert)
    {
        $this->concert = $concert;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pembeli Tiket Baru',
            'message' => 'Terdapat pembeli tiket baru pada kegiatan ' . $this->concert->event->nama,
            'button_text' => 'Lihat Detail',
            'url' => route('events.show', $this->concert->event->id),
        ];
    }
}

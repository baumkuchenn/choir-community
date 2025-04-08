<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DaftarEventNotification extends Notification
{
    use Queueable;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
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
            'title' => 'Kegiatan Baru',
            'message' => 'Ada kegiatan baru: ' . $this->event->nama,
            'button_text' => 'Lihat Detail',
            'modal_id' => 'daftar',
            'event_id' => $this->event->id,
            'event_nama' => $this->event->nama,
            'event_tanggal' => Carbon::parse($this->event->tanggal_mulai)->translatedFormat('d F Y') .
                ($this->event->tanggal_mulai != $this->event->tanggal_selesai
                    ? ' - ' . Carbon::parse($this->event->tanggal_selesai)->translatedFormat('d F Y')
                    : ''),
            'event_lokasi' => $this->event->lokasi,
        ];
    }
}

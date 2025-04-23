<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LatihanUpdatedNotification extends Notification
{
    use Queueable;
    protected $latihan;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct($latihan, $event)
    {
        $this->latihan = $latihan;
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
            'tipe' => 'manajemen',
            'title' => 'Perubahan Jadwal Latihan',
            'message' => 'Terdapat perubahan pada jadwal latihan untuk kegiatan ' . $this->event->nama . ' menjadi tanggal ' . $this->latihan->tanggal,
            'button_text' => 'Lihat Detail',
            'url' => route('management.calendar.index'),
        ];
    }
}

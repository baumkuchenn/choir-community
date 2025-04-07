<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventUpdatedNotification extends Notification
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
            'title' => 'Perubahan Informasi Kegiatan',
            'message' => 'Terdapat perubahan informasi pada kegiatan ' . $this->event->nama,
            'button_text' => 'Lihat Detail',
            'url' => route('management.calendar.show'),
        ];
    }
}

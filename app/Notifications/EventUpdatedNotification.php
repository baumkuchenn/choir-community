<?php

namespace App\Notifications;

use Carbon\Carbon;
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
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'tipe' => 'manajemen',
            'title' => 'Perubahan Informasi Kegiatan',
            'message' => 'Terdapat perubahan informasi pada kegiatan ' . $this->event->nama,
            'button_text' => 'Lihat Detail',
            'url' => route('management.calendar.index'),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Perubahan Informasi Kegiatan')
            ->greeting("Hai, {$notifiable->name}!")
            ->line('Terdapat perubahan informasi pada kegiatan ' . $this->event->nama . '.')
            ->line('Tanggal: ' . Carbon::parse($this->event->tanggal_mulai)->format('d M Y') . ' - ' . Carbon::parse($this->event->tanggal_selesai)->format('d M Y'))
            ->line('Jam: ' . Carbon::parse($this->event->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($this->event->jam_selesai)->format('H:i'))
            ->line('Lokasi: ' . $this->event->lokasi)
            ->action('Lihat Detail', route('management.calendar.index'));
    }
}

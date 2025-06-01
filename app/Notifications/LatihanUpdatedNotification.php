<?php

namespace App\Notifications;

use Carbon\Carbon;
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
        return ['database', 'mail'];
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

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Perubahan Jadwal Latihan')
            ->greeting("Hai, {$notifiable->name}!")
            ->line('Terdapat perubahan pada jadwal latihan untuk kegiatan ' . $this->event->nama . '.')
            ->line('Tanggal latihan: ' . Carbon::parse($this->latihan->tanggal)->format('d M Y'))
            ->line('Jam latihan: ' . Carbon::parse($this->latihan->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($this->latihan->jam_selesai)->format('H:i'))
            ->line('Lokasi: ' . $this->latihan->lokasi)
            ->action('Lihat Detail', route('management.calendar.index'));
    }
}

<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LatihanNotification extends Notification
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
            'title' => 'Jadwal Latihan Baru',
            'message' => 'Terdapat jadwal latihan baru untuk kegiatan ' . $this->event->nama . ' pada tanggal ' . $this->latihan->tanggal,
            'button_text' => 'Lihat Detail',
            'url' => route('management.calendar.index'),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Jadwal Latihan Baru')
            ->greeting("Hai, {$notifiable->name}!")
            ->line('Terdapat jadwal latihan baru untuk kegiatan ' . $this->event->nama . '.')
            ->line('Tanggal latihan: ' . Carbon::parse($this->latihan->tanggal)->format('d M Y'))
            ->line('Jam latihan: ' . Carbon::parse($this->latihan->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($this->latihan->jam_selesai)->format('H:i'))
            ->line('Lokasi: ' . $this->latihan->lokasi)
            ->action('Lihat Detail', route('management.calendar.index'));
    }
}

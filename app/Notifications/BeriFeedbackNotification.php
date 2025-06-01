<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeriFeedbackNotification extends Notification
{
    use Queueable;
    protected $concert;
    protected $event;
    protected $purchase;
    /**
     * Create a new notification instance.
     */
    public function __construct($concert, $purchase)
    {
        $this->concert = $concert;
        $this->event = $concert->event;
        $this->purchase = $purchase;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'tipe' => 'eticket',
            'concert_id' => $this->concert->id,
            'purchase_id' => $this->purchase->id,
            'title' => 'Terima kasih telah menghadiri konser ' . $this->event->nama,
            'message' => 'Kami akan sangat senang jika anda mau memberikan feedback.',
            'button_text' => 'Beri Feedback',
            'url' => route('eticket.feedback', $this->purchase->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Terima kasih telah menghadiri konser ' . $this->event->nama)
            ->greeting("Hai, {$notifiable->name}!")
            ->line("Kami harap anda menikmati konser \"{$this->event->nama}\".")
            ->line('Kami akan sangat senang jika anda mau memberikan feedback.')
            ->action('Beri Feedback Sekarang', route('eticket.feedback', $this->purchase->id))
            ->line('Terima kasih atas dukunganmu!');
    }
}

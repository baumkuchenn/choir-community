<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KomentarPostNotification extends Notification
{
    use Queueable;
    public $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'tipe' => 'forum',
            'title' => 'Ada komentar baru',
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->parent_id,
            'commenter' => $this->comment->creator->name,
            'message' => $this->comment->isi,
        ];
    }
}

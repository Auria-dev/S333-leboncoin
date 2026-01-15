<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomNotification extends Notification
{
    use Queueable;

    public $message;
    public $link;

    public function __construct(string $message, string $link = null)
    {
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable) : Array
    {
        return ['database', 'mail']; // saves to notifications table + sends mail
    }

    public function toDatabase($notifiable) : Array
    {
        return [
            'message' => $this->message,
            'link' => $this->link,
        ];
    }

    public function toMail($notifiable) : MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Nouvelle notification')
            ->greeting('Bonjour !')
            ->line($this->message);

        if ($this->link) {
            $mail->action('Voir les détails', $this->link);
        }

        return $mail->salutation('Cordialement, Lemauvaiscoin');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


class EmailAvalNotification extends Notification
{
    public $subject, $line1, $line2, $line3, $action, $val, $emailcc, $tipouser;
    public function __construct($subject, $line1, $line2, $line3, $action, $val, $emailcc, $tipouser)
    {
        $this->subject = $subject;
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->action = $action;
        $this->val = $val;
        $this->emailcc = $emailcc;
        $this->tipouser = $tipouser;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->emailcc == '' || is_null($this->emailcc) || $this->emailcc == 0) {
            return (new MailMessage)
                ->greeting('Señor(a)')
                ->subject($this->subject)
                ->line($this->line1)
                ->action($this->action, $this->val)
                ->line($this->line2)
                ->line($this->line3);
        } else {
            return (new MailMessage)
                ->greeting('Señor(a)')
                ->cc($this->emailcc)
                ->subject($this->subject)
                ->line($this->line1)
                ->action($this->action, $this->val)
                ->line($this->line2)
                ->line($this->line3);
        }
    }
}

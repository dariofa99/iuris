<?php

namespace App\Notifications;

use App\Conciliacion;
use App\ConciliacionEstado;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class SendMailAndNotificationGeneral extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $concepto;
    public $user_created;
    public $subject;
    public $url;

    public function __construct($concepto, $user_created, $subject, $url = null)
    {
        $this->concepto = $concepto;
        $this->user_created = $user_created;
        $this->subject = $subject;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            return ['mail'];
        }
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage($notifiable))
            ->subject($this->subject)
            ->view('myforms.mails.formato_correo', [
                'mensaje' => $this->concepto,
                'url' => $this->url != null ? url($this->url) : null,
                'user_created' => $this->user_created
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type_notification' => $this->subject,
            'message' => substr($this->concepto, 0, 60) . '...',
            'url' => $this->url != null ? url($this->url) : "#",
            'created_at' => date("Y-m-d H:i:s"),
            'icon' => 'fas fa-user'


        ];
    }
}

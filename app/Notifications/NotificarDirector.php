<?php

namespace App\Notifications;


use App\Expediente;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class NotificarDirector extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $expediente;
    public $message;
    public function __construct(Expediente $expediente,$message)
    {
        $this->expediente = $expediente;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject('Nuevo caso creado')
            ->view('myforms.mails.formato_correo', [
                'mensaje' => $this->message,
                'url' => url('/expedientes/' . $this->expediente->expid . '/edit#case_data')
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
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\CitacionEstudiantesQueve as Mailable;

class CitacionEstudiantesQueve extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        
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
      
        return (new Mailable($notifiable))
        ->subject('(CORREGIDO) Citaciones revisión casos IURIS - Jornada Tarde')
        ->to($notifiable->to);
      
/* 

        return (new MailMessage)->view(
            'myforms.mails.frm_citacion_estudiante',compact('notifiable') 
        );
        
        $url = url('/invoice/'.$notifiable->idnumber);
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'.$url))
                    ->line('Thank you for using our application!'); */
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
      
        return "";
    
    }
}

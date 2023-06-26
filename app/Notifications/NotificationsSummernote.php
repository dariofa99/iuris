<?php

namespace App\Notifications;

use App\Conciliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class NotificationsSummernote extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $mensaje;
    public $conciliacion;
    public $asunto;
    public function __construct($mensaje,$conciliacion,$asunto)
    {
       $this->mensaje = $mensaje; 
       $this->asunto = $asunto;  
       $this->conciliacion = $conciliacion;        
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
        ->subject($this->asunto)
        ->view('myforms.mails.formato_correo',[
                'mensaje'=>$this->mensaje,
                'url'=>url('/conciliaciones/'.$this->conciliacion->id.'/edit')
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
           'type_notification'=>'Respuesta de radicado conciliación',
           'link_to'=>'/conciliaciones/'.$this->conciliacion->id.'/edit',
           'mensaje'=>auth()->user()->name.' '.auth()->user()->lastname
        ];
    }
}

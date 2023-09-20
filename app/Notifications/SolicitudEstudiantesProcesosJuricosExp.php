<?php

namespace App\Notifications;

use App\Conciliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class SolicitudEstudiantesProcesosJuricosExp extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $mensaje;
    public $expediente;
    public function __construct($mensaje,$expediente)
    {
       $this->mensaje = $mensaje;  
       $this->expediente = $expediente;        
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
        ->subject('Solicitud de proceso jurídico')
        ->view('myforms.mails.frm_notificaciones_procjudexp',[
                'mensaje'=>$this->mensaje,
                'url'=>url('/expedientes/'.$this->expediente->expid.'/edit')
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
        /*    'type_notification'=>'Solicitud de proceso jurídico',
           'link_to'=>'/expedientes/'.$this->expediente->id.'/edit',
           'mensaje'=>"Solicitud de proceso jurídico" */

           'type_notification'=>'Solicitud de proceso jurídico',          
           'message'=>"Solicitud de proceso jurídico",
           'url'=>'/expedientes/'.$this->expediente->expid.'/edit',
           'created_at'=>date("Y-m-d H:i:s"),
           'icon'=>'fas fa-user'

        ];
    }
}

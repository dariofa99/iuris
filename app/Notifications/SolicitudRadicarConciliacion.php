<?php

namespace App\Notifications;

use App\Conciliacion;
use App\ConciliacionEstado;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class SolicitudRadicarConciliacion extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $conciliacion;
    public $user_created;
    public function __construct(ConciliacionEstado $conciliacion,$user_created)
    {
       $this->conciliacion = $conciliacion;
       $this->user_created = $user_created;        
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
        ->subject('Solicitud de radicado conciliación')
        ->view('myforms.mails.formato_correo',[
                'mensaje'=>$this->conciliacion->concepto,
                'url'=>url('/conciliaciones/'.$this->conciliacion->conciliacion_id.'/edit'),
                'user_created'=>$this->user_created
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
          /*  'type_notification'=>'Solicitud de radicado conciliación',
           'link_to'=>'/conciliaciones/'.$this->conciliacion->conciliacion_id.'/edit',
           'mensaje'=>auth()->user()->name.''.auth()->user()->lastname */

           'type_notification'=>'Solicitud de radicado conciliación',          
           'message'=>auth()->user()->name.''.auth()->user()->lastname,
           'url'=>'/conciliaciones/'.$this->conciliacion->conciliacion_id.'/edit',
           'created_at'=>date("Y-m-d H:i:s"),
           'icon'=>'fas fa-user'


        ];
    }
}

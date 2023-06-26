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
    public function __construct(ConciliacionEstado $conciliacion)
    {
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
        ->subject('Solicitud de radicado conciliación')
        ->view('myforms.mails.solicitud_radicado_conciliacion',[
                'mensaje'=>$this->conciliacion->concepto,
                'url'=>url('/conciliaciones/'.$this->conciliacion->conciliacion_id.'/edit')
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
           'type_notification'=>'Solicitud de radicado conciliación',
           'link_to'=>'/conciliaciones/'.$this->conciliacion->conciliacion_id.'/edit',
           'mensaje'=>auth()->user()->name.''.auth()->user()->lastname
        ];
    }
}

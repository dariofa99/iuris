<?php

namespace App\Mail;

use App\Conciliacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegConciliacionSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Conciliacion $notification)
    {
       $this->notification = $notification;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = "<br><br>
        Hemos recibido tu solicitud de conciliación.<br>        
        Con el siguiente enlace puedes seguir completando la solicitud.";
        return $this->view('myforms.mails.formato_correo',[
            "mensaje"=> $message,
            "url"=>url("/solicitudes/recepcion/conciliacion/".$this->notification->token."?id=".$this->notification->id."&paso=2")
        ])
        ->subject("Solicitud de conciliación");
    }
}

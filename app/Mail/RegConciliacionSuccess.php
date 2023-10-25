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
        Hemos recibido tu solicitud de conciliación y queremos asegurarte que estamos aquí para ayudarte.
        Entendemos lo importante que es resolver este asunto de manera justa y equitativa, por lo que nos comprometemos a trabajar de cerca contigo para buscar una solución amigable y satisfactoria para todas las partes involucradas.<br>
        Recuerda estar pendiente de tus datos de contacto para comunicarnos.";
        return $this->view('myforms.mails.formato_correo',[
            "mensaje"=> $message,
            "url"=>url("/solicitudes/recepcion/conciliacion/".$this->notification->token."?id=".$this->notification->id."&paso=2")
        ])
        ->subject("Solicitud de conciliación");
    }
}

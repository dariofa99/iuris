<?php

namespace App\Mail;

use App\Conciliacion;
use App\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegSolicitudExp extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Solicitud $notification)
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
        Hemos recibido tu solicitud de caso y queremos asegurarte que estamos aquí para ayudarte.
        Entendemos lo importante que es resolver este asunto de manera eficaz, por lo que nos comprometemos a trabajar de cerca contigo para buscar una solución amigable y satisfactoria para todas las partes involucradas.<br>
        Recuerda estar pendiente de tus datos de contacto para comunicarnos.<br>
        Número de solicitud: ". $this->notification->number;
        return $this->view('myforms.mails.formato_correo',[
            "mensaje"=> $message,
            "url"=>url("/solicitudes/recepcion/expedientes/".$this->notification->token."?paso=2")
        ])
        ->subject("Solicitud de caso");
    }
}

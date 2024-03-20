<?php

namespace App\Mail;

use App\Conciliacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegConcEncuestaSatSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = "<br><br>
        Hemos recibido la encuesta de satisfaccion. <br>
        Recuerde que para el Centro de Conciliación Eduardo Alvarado Hurtado
        es muy importante su opinión sobre el acceso y la atención brindados.<br>
        <br>
        Estaremos atentos a las recomendaciones brindadas.";
        return $this->view('myforms.mails.formato_correo', [
            "mensaje" => $message,
        ])
            ->subject("Recepción de encuesta de satisfacción");
    }
}

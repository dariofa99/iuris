<?php

namespace App\Mail;

use App\Conciliacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegConciliacionCorregir extends Mailable
{
    use Queueable, SerializesModels;

    protected $users;
    protected $cuerpo_correo;
    protected $conciliacion;
    protected $asunto;
    protected $user_created;
   
    public function __construct(
        $users,
        $cuerpo_correo,
        Conciliacion $conciliacion,
        $asunto,
        $user_created
    ) {
       // Log::info($users);
        $this->users = $users;
        $this->cuerpo_correo = $cuerpo_correo;
        $this->conciliacion = $conciliacion;
        $this->asunto = $asunto;
        $this->user_created = $user_created;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
        return $this->view('myforms.mails.formato_correo',[
            "mensaje"=>$this->cuerpo_correo,
            "url"=>url("/solicitudes/recepcion/conciliacion/".$this->conciliacion->token."?id=".$this->conciliacion->id."&paso=2"),
            'user_created'=>$this->user_created
        ])
        ->subject($this->asunto);
    }
}

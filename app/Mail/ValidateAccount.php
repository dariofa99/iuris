<?php

namespace App\Mail;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class ValidateAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $notification,$email)
    {
       $this->notification = $notification;
       $this->email = $email;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = "<br><br>
        Hola, {$this->notification->name} {$this->notification->lastname}, nos alegra que estes con nosotros.<br><br>
        Para que puedas continuar necesitamos que confirmes y actives tu cuenta presionando el siguiente botón.
        <br><br>

        ";
        return $this->view('myforms.mails.formato_correo',[
            "buttonMessage"=>"ACTIVAR CUENTA",
            "mensaje"=> $message,
            "url"=>url("/usuarios/active/account/".$this->notification->confirm_token."/?email={$this->email}")
        ])
        ->subject("Activar cuenta","IURIS");
    }
}

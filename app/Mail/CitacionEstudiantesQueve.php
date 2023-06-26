<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User as Citacion;

class CitacionEstudiantesQueve extends Mailable
{
    use Queueable, SerializesModels;
    
    public $notification;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Citacion $notification)
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
         return $this->view('myforms.mails.frm_citacion_estudiante_gen'); 
    }
}

<?php

namespace App\Mail;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;



class RecoveryAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $notification)
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
       
        return $this->view('myforms.mails.recovery_account',[            
            "user"=> $this->notification,            
        ])
        ->subject("Recuperar cuenta", config('app.name'));
    }
}

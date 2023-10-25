<?php

namespace App\Notifications;

use App\Conciliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;

class NotificationsSummernote extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $mensaje;
    public $conciliacion;
    public $asunto;
    protected $user_created;
    public function __construct($mensaje,$conciliacion,$asunto,$user_created)
    {
       $this->mensaje = $mensaje; 
       $this->asunto = $asunto;  
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
        ->subject($this->asunto)
        ->view('myforms.mails.formato_correo',[
                'mensaje'=>$this->mensaje,
                'url'=>url('/conciliaciones/'.$this->conciliacion->id.'/edit'),
                'user_created'=> $this->user_created
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
       /*     'type_notification'=>'summernote_notification',
           'link_to'=>'/conciliaciones/'.$this->conciliacion->id.'/edit',
           'mensaje'=>$this->asunto */

                   
           'type_notification'=>'summernote_notification',          
           'message'=>$this->asunto,
           'url'=>'/conciliaciones/'.$this->conciliacion->id.'/edit',
           'created_at'=>date("Y-m-d H:i:s"),
           'icon'=>'fas fa-user'

        ];
    }
}

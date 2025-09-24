<?php

namespace App\Notifications;

use App\Conciliacion;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    protected $getRealPath;
    protected $getClientOriginalName;

    public function __construct($mensaje, $conciliacion, $asunto, $user_created, $getRealPath = null)
    {
        $this->mensaje = $mensaje;
        $this->asunto = $asunto;
        $this->conciliacion = $conciliacion;
        $this->user_created = $user_created;
        $this->getRealPath = $getRealPath;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $mail = (new MailMessage($notifiable))->subject($this->asunto)
            ->view('myforms.mails.formato_correo', [
                'mensaje' => $this->mensaje,
                'url' => url('/conciliaciones/' . $this->conciliacion->id . '/edit'),
                'user_created' => $this->user_created
            ]);

        // 👇 Adjuntar archivo si existe
        Log::info('Adjuntando archivo: ' . $this->getRealPath);

        if ($this->getRealPath != "" && file_exists($this->getRealPath)) {
            $mail->attach($this->getRealPath, [
                'as' => basename($this->getRealPath),
                'mime' => mime_content_type($this->getRealPath),
            ]);
           
        }

        return $mail;
        /*  
        (new MailMessage($notifiable))
        ->subject($this->asunto)
        ->view('myforms.mails.formato_correo',[
                'mensaje'=>$this->mensaje,
                'url'=>url('/conciliaciones/'.$this->conciliacion->id.'/edit'),
                'user_created'=> $this->user_created
        ]);
 */
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


            'type_notification' => 'summernote_notification',
            'message' => $this->asunto,
            'url' => '/conciliaciones/' . $this->conciliacion->id . '/edit',
            'created_at' => date("Y-m-d H:i:s"),
            'icon' => 'fas fa-user'

        ];
    }
}

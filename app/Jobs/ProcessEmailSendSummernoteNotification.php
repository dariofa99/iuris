<?php

namespace App\Jobs;

use App\Conciliacion;
use App\Notifications\NotificationsSummernote;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessEmailSendSummernoteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $users;
    protected $cuerpo_correo;
    protected $conciliacion;
    protected $asunto;
    protected $user_created;
    protected $getRealPath;
    protected $getClientOriginalName;


    public function __construct(
        $users,
        $cuerpo_correo,
        Conciliacion $conciliacion,
        $asunto,
        $user_created,
        $getRealPath = ""
       

    ) {
       // Log::info($users);
        $this->users = $users;
        $this->cuerpo_correo = $cuerpo_correo;
        $this->conciliacion = $conciliacion;
        $this->asunto = $asunto;
        $this->user_created = $user_created;
        $this->getRealPath = $getRealPath;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Iniciando proceso de notificación por correo...{$this->getRealPath}");
      //  Log::info("Usuarios a notificar 2: ".implode(", ", $this->users)); 
        Notification::send($this->users, new NotificationsSummernote(
            $this->cuerpo_correo,
            $this->conciliacion,
            $this->asunto,
            $this->user_created,
            $this->getRealPath
            
        ));
    }
}

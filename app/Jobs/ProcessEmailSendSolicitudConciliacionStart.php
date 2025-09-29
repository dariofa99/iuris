<?php

namespace App\Jobs;

use App\Conciliacion;
use App\Mail\RegConciliacionStart;
use App\Mail\RegConciliacionSuccess;
use App\Notifications\NotificationsSummernote;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ProcessEmailSendSolicitudConciliacionStart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $users;
    protected $cuerpo_correo;
    protected $conciliacion;
    protected $asunto;
    protected $user_created;
   
    public function __construct(
        $user,        
        Conciliacion $conciliacion
        
    ) {
       
        $this->users = $user;
       
        $this->conciliacion = $conciliacion;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Enviando correo de registro de conciliacion a " . $this->users);
       // Mail::to("centrodeconciliacion@udenar.edu.co")->send(new RegConciliacionStart($this->conciliacion)); 
        Mail::to($this->users)->send(new RegConciliacionSuccess($this->conciliacion));

        
    }
}

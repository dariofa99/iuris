<?php

namespace App\Jobs;

use App\Conciliacion;
use App\Expediente;
use App\Notifications\NotificationsSummernote;
use App\Notifications\SolicitudEstudiantesProcesosJuricosExp;
use App\Services\UsersService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessEmailSendSummernoteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $users;
    protected $cuerpo_correo;
    protected $conciliacion;
    protected $asunto;
   
    public function __construct(
        $users,
        $cuerpo_correo,
        Conciliacion $conciliacion,
        $asunto
    ) {
       // Log::info($users);
        $this->users = $users;
        $this->cuerpo_correo = $cuerpo_correo;
        $this->conciliacion = $conciliacion;
        $this->asunto = $asunto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // Log::info($this->cuerpo_correo);
        Notification::send($this->users, new NotificationsSummernote(
            $this->cuerpo_correo,
            $this->conciliacion,
            $this->asunto
        ));
    }
}

<?php

namespace App\Jobs;

use App\Expediente;
use App\Notifications\SolicitudEstudiantesProcesosJuricosExp;
use App\Services\UsersService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessEmailSendPJ implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $expediente;
    protected $userService;
    protected $status;
    /**
     * Create a new job instance.
     * @param Expediente $expediente
     * @return void
     */
    public function __construct(Expediente $expediente,$status)
    {
        Log::info($expediente);
        $this->expediente = $expediente;
        $this->status = $status;
        $this->userService = App::make(UsersService::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asignacion_caso = $this->expediente->asignacion;;
         $estado_caso_old = $asignacion_caso->procesojud_id;
        $users = $this->userService->getUsersByPermissionName('rec_mail_procjuridicoexp');
        if ($estado_caso_old == 244 and $this->status == 246) {
            $mensaje = getMessagesForPro(001, $this->expediente->expid);
        } else {
            $mensaje = getMessagesForPro($asignacion_caso->procesojud_id, $this->expediente->expid);
        }
        $estudiante = $this->expediente->estudiante;
        $docente = $this->expediente->getDocenteAsig();
        Notification::send($estudiante, new SolicitudEstudiantesProcesosJuricosExp($mensaje, $this->expediente));
        Notification::send($docente, new SolicitudEstudiantesProcesosJuricosExp($mensaje, $this->expediente));
        if (count($users) > 0) {
            Notification::send($users, new SolicitudEstudiantesProcesosJuricosExp($mensaje, $this->expediente));
            
        } 
    }
}

<?php

namespace App\Jobs;

use App\Expediente;
use App\Notifications\NotificarDirector;
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

class ProcessEmailSendNotificarDirector implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $expediente;
    protected $userService;
    protected $user;
    /**
     * Create a new job instance.
     * @param Expediente $expediente
     * @return void
     */
    public function __construct(Expediente $expediente,$user)
    {
        Log::info($expediente);
        $this->expediente = $expediente;
        $this->user = $user;
        $this->userService = App::make(UsersService::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $message = "<h3>Se ha creado un nuevo expediente!</h3>";
        $message .= "<h4>Número: " . $this->expediente->expid . "<br>";
        $message .= "Rama del Derecho: " . $this->expediente->rama_derecho->ramadernombre . "<br>";
        $message .= "Estudiante: " . $this->expediente->estudiante->name . " " . $this->expediente->estudiante->lastname . "<br>";
        $message .= "Docente: " . $this->expediente->getDocenteAsig()->name . " " . $this->expediente->getDocenteAsig()->lastname . "<br></h4>";
        Notification::send($this->user, new NotificarDirector($this->expediente,$message));
       
    }
}

<?php

namespace App\Console\Commands;

use App\Expediente;
use App\ExpedientePausas;
use App\Notifications\UserNotification;
use App\Services\EstadosCasoService;
use App\Services\ExpedientesService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinishExpPause extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finishexp:pause';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cerrar casos pausados';
    private $expedienteService;
    private $estadoCasoService;
    private $request;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ExpedientesService $expedienteService,
        EstadosCasoService $estadoCasoService
    ) {
        $this->expedienteService = $expedienteService;
        $this->estadoCasoService = $estadoCasoService;
        $this->request = App::make(Request::class);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $exps = DB::table("expedientes")->join("asignacion_caso", "expedientes.expid", "=", "asignacion_caso.asigexp_id")
            ->join("expedientes_pausa", "expedientes_pausa.asig_caso_id", "=", "asignacion_caso.id")
            ->select("expedientes.id as exp_id", "expedientes.expestado_id", "asignacion_caso.id", "expedientes.expid", "expedientes_pausa.fecha_final")
            ->whereDate("expedientes_pausa.fecha_final", "<", Carbon::now())
            ->where("expedientes.expestado_id", 6)
            ->where("expedientes_pausa.estado_id", 249)
            ->get();
        $user = User::find(1);
        foreach ($exps as $key => $exped) {
            $expediente = Expediente::find($exped->exp_id);
            $asignacion = $expediente->asignacion;
            $expediente->expestado_id = 1;
            $expediente->save();
            $this->request['useridnumber'] = $user->idnumber;
            $this->request['comentario'] = 'Fecha de pausa caducada';
            $this->request['expidnumber'] = $expediente->expid;
            $this->request['ref_estado_id'] = $expediente->expestado_id;
            $this->request['ref_motivo_estado_id'] = 11;
            $estado_caso = $this->estadoCasoService->store($this->request);
            $user = $expediente->estudiante;
            $user->notification = 'Nueva notificación de caso';
            $user->link_to = '/expedientes/' . $expediente->expid . '/edit';
            $user->mensaje = 'Se ha vencido la pausa. Exp: ' . $expediente->expid;
            $user->notify(new UserNotification($user));
            $exp = ExpedientePausas::where("asig_caso_id", $asignacion->id)
                ->update([
                    "estado_id" => 250
                ]);
        };
    }
}

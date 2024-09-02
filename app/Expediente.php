<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\ColorTurnos;
use Illuminate\Support\Facades\Event;
use App\User;
use App\Traits\AsigNotas;
use App\Traits\UploadFile;
use App\Segmento;
use App\HistorialDatosCaso;
use App\Services\ExpedientesService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Expediente extends Model
{
    use Notifiable;
    use ColorTurnos;
    use AsigNotas;
    use UploadFile;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expedientes';
    private $origen = 1;
    private $disk = 'exp_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expid',
        'expramaderecho_id',
        'expidnumber',
        'expestado_id',
        'exptipoproce_id',
        'expfecha',
        'expusercreated',
        'expuserupdated',
        'exptipocaso_id',
        'expdesccorta',
        'expidnumberest',
        'expdepto_id',
        'expmunicipio_id',
        'exptipovivien_id',
        'expperacargo',
        'expingremensual',
        'expegremensual',
        'exphechos',
        'exprtaest',
        'expjuzoent',
        'expnumproc',
        'exppersondemandante',
        'exppersondemandada',
        'expfechalimite',
        'expfecha_res'

    ];


    /*  public $periodoService;
    public $segmentoService;
    public function __construct()
    {
        
        $this->periodoService = App::make(PeriodosService::class);
        $this->segmentoService = App::make(SegmentosService::class);
    }
 */

    public static function boot()
    {
        parent::boot();
        static::created(function ($item) {
            Event::dispatch('expediente.created', $item);
        });
        static::updated(function ($item) {
            Event::dispatch('expediente.updated', $item);
        });
        static::deleted(function ($item) {
            Event::dispatch('expediente.deleted', $item);
        });
    }
    public function conciliaciones()
    {
        return $this->belongsToMany(Conciliacion::class, 'conc_has_exp', 'exp_id', 'conciliacion_id')
            ->withPivot('id', 'conciliacion_id', 'exp_id', 'type_status_id', 'user_id')
            ->withTimestamps();
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'expidnumberest', 'idnumber');
    }
    public function rama_derecho()
    {
        return $this->belongsTo(RamaDerecho::class, 'expramaderecho_id', 'id');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'expidnumber', 'idnumber');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCaso::class, 'asigexp_id', 'expid');
    }

    public function asignacion()
    {
        return $this->hasOne(AsignacionCaso::class, 'asigexp_id', 'expid')
            ->where('asigest_id', $this->estudiante->idnumber)
            ->where('activo', 1);
    }

    public function requerimientos()
    {
        return $this->hasMany(Requerimiento::class, 'reqexpid', 'expid');
    }

    public function actuacion()
    {
        return $this->hasMany(Actuacion::class, 'actexpid', 'expid');
    }
    public function actuaciones()
    {
        return $this->hasMany(Actuacion::class, 'actexpid', 'expid');
    }

    public function logs()
    {
        return $this->hasMany(CaseLog::class, 'exp_id', 'id');
    }

    public function asesorias_docente()
    {
        return $this->hasMany(AsesoriaDocente::class, 'expidnumber', 'expid');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'expidnumber', 'expid');
    }

    public function estados()
    {
        return $this->hasMany(EstadoCaso::class, 'expidnumber', 'expid');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'expestado_id', 'id');
    }



    public function solicitudes()
    {
        return $this->belongsToMany(Solicitud::class, 'solicitud_has_exp', 'exp_id')
            ->withPivot('solicitud_id', 'exp_id')
            ->withTimestamps();
    }

    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 'sede_expedientes', 'expediente_id', 'sede_id')
            ->withPivot('id', 'sede_id', 'expediente_id')
            ->withTimestamps();
    }

    private function getPeriodoActivo()
    {
        $service = App::make(PeriodosService::class);
        return $service->getPeriodoActivo();
    }

    private function getSegmentoActivo()
    {
        $service = App::make(SegmentosService::class);
        return $service->getSegmentoActivo();
    }

    private function getSegmentoAsignacion($asignacion)
    {
        $service = App::make(SegmentosService::class);
        return $service->getSegmentoAsignacion($asignacion);
    }
    public function getSegmentoEvaluacion($asignacion)
    {
        $service = App::make(SegmentosService::class);
        return $service->getSegmentoEvaluacion($asignacion);
    }
    public function getActuaciones($only)
    {
        $service = App::make(ExpedientesService::class);
        return $service->getActuacions($this, $only);
    }

    function getDocenteAsig()
    {
        $asig = $this->getAsignacion();
        try {
            if ($asig) {
                $docente = $asig
                    ->asig_docente()
                    ->where('asignacion_docente_caso.activo', 1)
                    ->first()->docente;

                return $docente;
            }
            $user = new User();
            $user->name = 'Sin asignacion de caso';
            $user->idnumber = '';
            return $user;
        } catch (\ErrorException $e) {
            $user = new User();
            $user->name = 'Sin asignar';
            $user->idnumber = 'Sin asignar';
            return $user;
        }
    }

    function getAsignacion()
    {
        $asig = $this->asignaciones()
            ->where('asigest_id', $this->estudiante->idnumber)
            ->where('activo', 1)
            ->first();

        try {
            return $asig;
        } catch (\ErrorException $e) {
            return 'Error';
        }
    }

    function getDaysOrColorForClose($item = '', $now = null)
    {
        /* $asig = $this->asignaciones()->where('asigest_id',$this->estudiante->idnumber)
        ->where('periodo_id',$periodo->id)
        ->orderBy('fecha_asig','desc')->first();  */
        $asig = $this->getAsignacion();
        $periodo = $this->getPeriodoActivo();


        try {
            $periodo = $this->getPeriodoActivo();
            $now = Carbon::now();
            $asig = $this->getAsignacion();
            $estamosVacaciones = DB::table("vacaciones_periodo")
                ->whereDate('fecha_inicio', '<=', $now)
                ->whereDate('fecha_fin', '>=', $now)
                ->where("periodo_id", $periodo->id)
                ->orderBy('created_at', 'desc')->first();
            $huboVacaciones = DB::table("vacaciones_periodo")
                ->whereDate('fecha_inicio', '>=', $asig->fecha_asig)
                ->whereDate('fecha_fin', '<=', $now)
                ->where("periodo_id", $periodo->id)
                ->orderBy('created_at', 'desc')->get();
            if ($estamosVacaciones) {
                $dias_v = 0;
                if ($huboVacaciones) {
                    foreach ($huboVacaciones as $key => $vacacion) {
                        $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
                    }
                }
                $dias = ($this->difDays($asig->fecha_asig, $estamosVacaciones->fecha_inicio) - $dias_v);
                //dd($asig,$dias_v,$estamosVacaciones);
            } elseif ($huboVacaciones) {
                $dias_v = 0;
                foreach ($huboVacaciones as $key => $vacacion) {
                    $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
                }
                $dias_asig  = $this->getDaysAfterAsig();
                $dias = $dias_asig - $dias_v;
            } else {
                $dias = $this->getDaysAfterAsig();
            }
            $days = (30 - $dias);
            $mgs = $days . ' Días';
            if ($days >= 1) $color = '#DA443F !important';
            if ($days >= 10) $color = '#F4D03F !important';
            if ($days >= 20) $color = '#0CA418 !important';
            if ($days < 1) {
                $color = 'gray !important';
                $mgs = "Evaluado por sistema";
            }

            switch ($item) {
                case 'color':
                    return $color;
                    break;
                case 'mensaje':
                    $d = "(días: $days)";
                    if ($days < 0) {
                        $d = "(evaluado)";
                    }
                    return $estamosVacaciones ? "Vacaciones $d" : $mgs;
                    break;
                case 'dias':
                    return $days;
                    break;
                default:
                    return "Sin argumento";
                    break;
            }
            return "dias";
        } catch (\ErrorException $e) {
            return 'Error';
        }
    }

    function getActuacions($expid)
    {
        $acts = DB::table('actuacions')
            ->join('revisiones_actuacion as rv', 'rv.rev_actid', '=', 'actuacions.id')
            ->join('expedientes', 'expedientes.expid', '=', 'actuacions.actexpid')
            ->select(
                DB::raw('SUM(if(parent_rev_actid = rv.rev_actid, 1, 0)) AS padre'),
                DB::raw('SUM(if(actestado_id="138" OR actestado_id="136", if(parent_rev_actid = rv.rev_actid, 1, 0), if(actestado_id="104" OR actestado_id="139", 1, 0))) AS aprobado'),
                DB::raw('SUM(if(actestado_id="101", 1, 0)) AS pendiente'),
                DB::raw('SUM(if(actestado_id="102", if(DATEDIFF(`fecha_limit`, now())>0 AND DATEDIFF(`fecha_limit`, now())<3, 1, 0), if(actestado_id="140", if(DATEDIFF(`fecha_limit`, now())>0 AND DATEDIFF(`fecha_limit`,now())<3, 1, 0), 0))) AS time
         '),
            )
            ->where(['actuacions.actexpid' => $expid])
            ->whereRaw('expedientes.expidnumberest = actuacions.actidnumberest')
            ->first();

        $circle = [];
        if ($acts->padre > $acts->aprobado and $acts->pendiente == 0) {
            if ($acts->time > 0) {
                $circle = [0 => 'circle-red', 1 => 'Correciones por vencerse'];
                return $circle;
                // return 'circle-red';
            }
        }

        if ($acts->pendiente > 0) {
            if (Auth::user()->hasRole('estudiante')) {
                $var = $acts->aprobado + $acts->pendiente;

                if ($acts->padre > $var) {
                    $circle = [0 => 'circle-black', 1 => 'Corregir estudiante'];
                    return $circle;
                    //return 'circle-black';
                }
            }
            $circle = [0 => 'circle-white', 1 => 'Revisar docente'];
            return $circle;
            //return 'circle-white';
        }

        if ($acts->padre > $acts->aprobado) {
            $circle = [0 => 'circle-black', 1 => 'Corregir estudiante'];
            return $circle;
            //return 'circle-black';
        }
        $circle = [0 => 'circle-none', 1 => ''];
        return $circle;
        //return 'circle-none';
    }

    public function verifyNotReq($date = null)
    {
        if ($date == null) {
            $date = Carbon::now();
            $date = $date->subDays(15);
            $date = $date->format('Y-m-d');
        }
        $reqs = DB::table('requerimientos')
            ->where([
                'reqentregado' => false,
                'reqidest' => $this->expidnumberest,
                'reqexpid' => $this->expid,
            ])
            ->select('requerimientos.id')
            ->get();

        return $reqs;
    }

    public function verifyNotAct($date = null)
    {
        $padresAct = DB::table('actuacions')
            ->join('revisiones_actuacion', 'actuacions.id', '=', 'revisiones_actuacion.parent_rev_actid')
            ->where([
                ['actestado_id', '<>', '136'],
                ['actestado_id', '<>', '138'],
                ['actestado_id', '<>', '139'],
                ['actestado_id', '<>', 234],
                ['actestado_id', '<>', 235],
                ['actidnumberest', $this->expidnumberest],
                ['actexpid', $this->expid]
            ])
            ->select('actuacions.id')
            ->groupBy('actuacions.id')
            ->get();

        $hijos = [];
        foreach ($padresAct as $key => $actpa) {
            if ($date == null) {
                $date = Carbon::now();
                $date = $date->subDays(15);
                $date = $date->format('Y-m-d');
            }

            $hijosAct = DB::select(
                DB::raw("SELECT rev_actid, actestado_id, actuacions.actfecha,actnombre FROM actuacions, revisiones_actuacion
        WHERE actuacions.id = revisiones_actuacion.rev_actid
        AND parent_rev_actid = $actpa->id
        AND actestado_id <> 136 AND actestado_id <> 138 
        AND actestado_id <> 234
        ORDER BY rev_actid DESC LIMIT 1"),
            );

            if (count($hijosAct) > 0 and $hijosAct[0]->actestado_id != 104 and $hijosAct[0]->actestado_id != 139 and $hijosAct[0]->actfecha <= $date and $hijosAct[0]->actfecha >= '2018-08-21') {
                $hijos[] = $hijosAct;
            }
        }

        return $hijos;
    }

    public function verifyActuacionForCreate()
    {
        $actuacions = $this->getActuaciones(1);
        //return $actuacions;
        $validForCreate = true;
        $act_abi = 0;
        if (($actuacions)) {
            foreach ($actuacions as $key => $actuacion) {
                if ($actuacion->actusercreated == $this->expidnumberest) {
                    if (
                        $actuacion->actestado_id == 101
                        || $actuacion->actestado_id == 102
                        || $actuacion->actestado_id == 140
                    ) {
                        $validForCreate = false;
                        $act_abi = $act_abi + 1;
                        if (count($actuacion->getHijos($actuacion)) > 0) {
                            foreach ($actuacion->getHijos($actuacion) as $key => $hijo) {
                                if (
                                    $hijo->actestado_id == 104
                                    || $hijo->actestado_id == 139
                                    || $hijo->actestado_id == 234
                                ) {
                                    $validForCreate = true;
                                    $act_abi = $act_abi - 1;
                                    //return $validForCreate;
                                }
                            }
                        }
                    }
                }
            }

            return $act_abi;

            //return $validForCreate;
        }

        return $validForCreate;
    }

    public function verifyActuacionAnexoForCreate()
    {
        $actuacions = $this->getActuaciones(1);
        // return $actuacions;
        $validForCreate = true;
        $act_ab = 0;
        if (($actuacions)) {
            foreach ($actuacions as $key => $actuacion) {
                if ($actuacion->actusercreated == $this->expidnumberest) {
                    if ($actuacion->actestado_id == 136) {
                        $act_ab += 1;
                        $validForCreate = false;
                    }
                    if (count($actuacion->getHijos($actuacion)) > 0) {

                        //$validForCreate = true;                
                        foreach ($actuacion->getHijos($actuacion) as $key => $hijo) {
                            if ($hijo->actestado_id == 136) {
                                $validForCreate = false;
                                $act_ab += 1;
                            }
                        }
                    }
                }
            }



            return $act_ab;
        }

        return $act_ab;
    }

    public function setNotActLimit($date = null)
    {

        $fecha_limit = Carbon::now();
        $padresAct = DB::table('actuacions')
            ->join('revisiones_actuacion', 'actuacions.id', '=', 'revisiones_actuacion.parent_rev_actid')
            ->where([
                ['actestado_id', '<>', '136'],
                ['actestado_id', '<>', '138'],
                ['actestado_id', '<>', '139'],
                ['actestado_id', '<>', '174'],
                ['actestado_id', '<>', '175'],
                ['actestado_id', '<>', '176'],
                ['actestado_id', '<>', '177'],
                ['actestado_id', '<>', '178'],
                ['actidnumberest', $this->expidnumberest],
                ['actexpid', $this->expid]
            ])
            ->select('actuacions.id')
            ->groupBy('actuacions.id')
            ->get();

        $hijos = [];
        $segmento = $this->getSegmentoActivo();

        if (count($padresAct) > 0) {
            $periodo = $this->getPeriodoActivo();
            $vacaciones = DB::table("vacaciones_periodo")
                ->where("periodo_id", $periodo->id)->get();

            foreach ($padresAct as $key => $actpa) {

                $hijosAct = DB::select(
                    DB::raw("SELECT rev_actid, actestado_id, actuacions.actfecha,actnombre,fecha_limit FROM actuacions, revisiones_actuacion
                WHERE actuacions.id = revisiones_actuacion.rev_actid
                AND parent_rev_actid = $actpa->id
                AND actestado_id <> 136 AND actestado_id <> 138 and actestado_id <> 235
                ORDER BY rev_actid DESC LIMIT 1"),
                );

                // 
                if (count($hijosAct) > 0 and $hijosAct[0]->fecha_limit !== null) {
                    $percent = 100;
                    $date = Carbon::now()->format('Y-m-d');
                    $fecha_limit = Carbon::parse($hijosAct[0]->fecha_limit);
                    if (count($vacaciones) > 0) {
                        $days_vac = 0;
                        foreach ($vacaciones as $key => $vacacion) {
                            //if( $key==2) dd($fecha_limit ,  $key,$vacacion->fecha_fin); 
                            if (
                                $vacacion->fecha_inicio <= $fecha_limit && $vacacion->fecha_fin >= $fecha_limit ||
                                ($vacacion->fecha_inicio <= $fecha_limit && $vacacion->fecha_fin <= $fecha_limit)
                            ) {
                                $inicio = Carbon::parse($vacacion->fecha_inicio); //moment(vacaciones[0].fecha_inicio, 'YYYY-MM-DD');
                                $fin = Carbon::parse($vacacion->fecha_fin); //moment(vacaciones[0].fecha_fin, 'YYYY-MM-DD');
                                $days_vac =  $inicio->diffInDays($fin, false);
                                $fecha_limit->addDays($days_vac);
                                //if( $key==2) dd($fecha_limit ,  $key);                                                     
                            }
                        }
                    }

                    if (count($hijosAct) > 0 and $hijosAct[0]->actestado_id != 104 and $hijosAct[0]->actestado_id != 101 and $hijosAct[0]->actestado_id != 139 and $hijosAct[0]->fecha_limit !== null and $hijosAct[0]->fecha_limit < $date) {

                        $hijos[] = $hijosAct;
                        $actuacion = Actuacion::find($hijosAct[0]->rev_actid);
                        $data = [
                            'ntaaplicacion' => 0,
                            'ntaconocimiento' => 0,
                            'ntaetica' => 0,
                            'ntaconcepto' => 'Evaluado por sistema (fecha límite vencida)',
                            'orgntsid' => 2,
                            'segid' => $segmento->segmento_id,
                            'perid' => $segmento->perid,
                            'tpntid' => 1,
                            'expidnumber' => $actuacion->actexpid,
                            'estidnumber' => $actuacion->actidnumberest,
                            'docidnumber' => Auth::user()->idnumber,
                            'tbl_org_id' => $actuacion->id,
                        ];
                        //
                        $actuacion->actestado_id = 139;
                        $actuacion->actuserupdated = Auth::user()->idnumber;
                        $actuacion->save();
                        $actuacion->asignarNotas($data);
                    }
                }
            }

            return  $fecha_limit;
        }
        // dd($hijos);
        // return $hijos;
    }

    public function scopeCriterio($query, $request, $search_all_exp = false)
    {
        if ($request->tipo_busqueda == "adv") {


            return $query->where(['expidnumberest' => $request->expidnumberest])
                ->where('exptipoproce_id', $request->exptipoproce_id)
                ->where('expestado_id', $request->expestado_id);

            if ($request->estado_id) {
                //return $query->where('expestado_id', $data);
            }
            //dd("dd00");
        }

        if ($request->has("data") and trim($request->data) != '') {

            $data = $request->data;
            switch ($request->tipo_busqueda) {

                case 'codido_exp':
                    return $query->where('expid', $data);
                    break;
                case 'estudiante':
                case 'estudiante_num':
                    return $query->where(['expidnumberest' => $data]);
                    break;
                case 'idnumber_doc':
                    return $query->where(function ($qu) use ($data) {
                        $qu->whereHas('asignaciones.asig_docente', function ($q) use ($data) {
                            $q->where('asignacion_docente_caso.docidnumber', $data);
                        });
                    });



                    break;
                case 'solicitante':
                case 'solicitante_num':
                    return $query->where('expidnumber', $data);
                    break;
                case 'estado':
                    return $query->where('expestado_id', $data);
                    break;
                case 'tipo_consulta':
                    return $query->where('exptipoproce_id', $data);
                    break;
                case 'fecha_creacion':
                    return $query->where('expfecha', $data);
                    break;
                case 'rama_derecho':
                    return $query->where('expramaderecho_id', $data);
                    break;
                case 'color':
                    $now = Carbon::now();
                    $now2 = Carbon::now();
                    if ($data == 'verde') {
                        return $query
                            ->where('exptipoproce_id', 1)
                            ->where('expedientes.expestado_id', '!=', 2)
                            ->where('asignacion_caso.fecha_asig', '>=', $now->subDays(11));
                    } elseif ($data == 'amarillo') {
                        return $query
                            ->where('exptipoproce_id', 1)
                            ->where('expedientes.expestado_id', '!=', 2)
                            ->whereBetween('asignacion_caso.fecha_asig', [$now->subDays(20), $now2->subDays(11)]);
                    } elseif ($data == 'rojo') {
                        return $query
                            ->where('exptipoproce_id', 1)
                            ->where('expedientes.expestado_id', '!=', 2)
                            ->whereBetween('asignacion_caso.fecha_asig', [$now->subDays(30), $now2->subDays(20)]);
                    } elseif ($data == 'gris') {
                        return $query
                            ->where('exptipoproce_id', 1)
                            ->where('expedientes.expestado_id', '!=', 2)
                            ->where('asignacion_caso.fecha_asig', '<=', $now->subDays(30));
                    }
                    break;
            }
        }
    }


    public function scopeRangoFechas($query, $fechaini, $fechafin)
    {
        if ($fechaini != '' and $fechafin != '') {
            $query->whereBetween('asignacion_caso.created_at', [$fechaini, $fechafin])->get();
        }
    }

    function getIds()
    {
        $ids = substr($this->expid, 6);
        $id = strlen($ids);

        $ind = $id + 1;
        $letra = substr($this->expid, 4, -$ind);
        if ($letra == 'B') {
            return $ids;
        }
    }


    function get_nota_prov($concepto)
    {
        $notas = $this->notas()
            ->where(['orgntsid' => 1])
            ->get();
        $periodo = $this->getPeriodoActivo();
        $n_conocimiento = [];
        $n_etica = [];
        $n_aplicacion = [];

        if ($periodo) {
            foreach ($notas as $key => $nota) {
                if ($nota->perid == $periodo->id) {
                    //Provisionales
                    if ($nota->orgntsid == 1 and $nota->tpntid == 2) {
                        // echo $nota->nota."<br>";
                        //echo $nota->segmento->segnombre."<br>";
                        if ($nota->cptnotaid == 1 and $nota->estidnumber == $this->expidnumberest) {
                            $n_conocimiento[] = [
                                'nota' => $nota->nota,
                                'id' => $nota->id,
                            ];
                        }
                        if ($nota->cptnotaid == 2 and $nota->estidnumber == $this->expidnumberest) {
                            $n_aplicacion[] = [
                                'nota' => $nota->nota,
                                'id' => $nota->id,
                            ];
                        }
                        if ($nota->cptnotaid == 3 and $nota->estidnumber == $this->expidnumberest) {
                            $n_etica[] = [
                                'nota' => $nota->nota,
                                'id' => $nota->id,
                            ];
                        }
                    }
                }
            }

            switch ($concepto) {
                case 'conocimiento':
                    $promedio = $this->get_promedio($n_conocimiento);

                    break;
                case 'aplicacion':
                    $promedio = $this->get_promedio($n_aplicacion);
                    break;
                case 'etica':
                    $promedio = $this->get_promedio($n_etica);
                    break;
                case 'final':
                    $promedio1 = $this->get_promedio($n_etica);
                    $promedio2 = $this->get_promedio($n_aplicacion);
                    $promedio3 = $this->get_promedio($n_conocimiento);
                    $final = [];
                    $final[] = ['nota' => $promedio1];
                    $final[] = ['nota' => $promedio2];
                    $final[] = ['nota' => $promedio3];

                    $promedio = $this->get_promedio($final);

                    break;
            }
            $response = [
                'promedio' => $promedio,
                'id' => 00,
            ];
            return $response;
            //  echo "$promedio";
        }
        return 0;
    }

    function get_notas_caso($periodo = null)
    {
        //obtiene las notas de totales del caso//en todos los segmentos
        $periodo = $this->getPeriodoActivo();
        $segmentos = Segmento::join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
            ->where('sg.sede_id', session('sede')->id_sede)
            ->where('perid', $periodo->periodo_id)
            ->get();
        $notas = [];
        //  dd($segmentos);
        foreach ($segmentos as $key => $segmento) {
            //dd($segmento);
            if ($segmento->fecha_fin >= $this->expfecha || $segmento->estado) {
                $nota_conocimiento = $this->get_nota_corte('conocimiento', $segmento->segmento_id);
                $nota_concepto = $this->get_nota_corte('concepto', $segmento->segmento_id);
                $nota_aplicacion = $this->get_nota_corte('aplicacion', $segmento->segmento_id);
                $nota_etica = $this->get_nota_corte('etica', $segmento->segmento_id);
                $nota_final = $this->get_nota_corte('final', $segmento->segmento_id);
                $notas[] = [
                    'segmento_id' => $segmento->segmento_id,
                    'segmento' => $segmento->segnombre,
                    'nota_conocimiento' => $nota_conocimiento,
                    'nota_aplicacion' => $nota_aplicacion,
                    'nota_etica' => $nota_etica,
                    'nota_final' => $nota_final,
                    'nota_concepto' => $nota_concepto,
                ];
            }
        }
        //dd($segmentos);
        return $notas;
    }

    public function get_has_nota_final()
    {
        $nota_f = [];
        $notas = $this->get_notas_caso();
        if (count($notas) > 0) {
            foreach ($notas as $key => $nota) {
                if (count($nota['nota_etica']) > 0) {
                    if ($nota['nota_etica']['tipo_id'] == 1) {
                        $nota_f = $nota;
                        return $nota_f;
                    }
                }
            }
        }
        return $nota_f;
    }

    ///Eventos

    //////////////////////////
    public function difDays($fecha_ini, $fecha_fin)
    {
        $fecha_ini = Carbon::parse($fecha_ini);
        return $fecha_ini->diffInDays($fecha_fin, false);
    }
    public function fechaHistorialDatosCaso($tipo)
    {
        $asig = $this->asignacion;

        if ($asig) {

            $historial = HistorialDatosCaso::where('hisdc_expidnumber', $this->expid)
                ->where('hisdc_tipo_datos_caso', $tipo)
                ->where('hisdc_idnumberest_id', $this->expidnumberest)
                ->where('created_at', '>=', Carbon::parse($asig->fecha_asig)->startOfDay())
                ->orderBy('id', 'DESC')
                ->first();
            if ($historial) {
                $his_fecha = $historial->created_at;
                $his_fecha = $his_fecha->format('d-m-Y');
                return $his_fecha;
            }
        }

        return false;
    }

    public function getDaysAfterAsig()
    {
        $asig = $this->asignacion;
        if ($asig) {
            $fecha_ini = Carbon::now();
            return $fecha_ini->diffInDays($asig->fecha_asig, false) * -1;
        }
        return 0;
    }

    public function fechaVigente($fecha_db)
    {
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

        $now = Carbon::now();
        $now = $now->format('d-m-Y');
        $fecha_db = Carbon::parse($fecha_db);
        $fecha2_db = $fecha_db;
        $fecha_db = $fecha_db->format('d-m-Y');
        if ($now < $fecha_db) {
            return $dias[$fecha2_db->dayOfWeek] . ', ' . $fecha2_db->day . ' de ' . $meses[$fecha2_db->month] . ' del ' . $fecha2_db->year;
        }
        return false;
    }

    public function getTextForTH($item)
    {
        $periodo = $this->getPeriodoActivo();
        $now = Carbon::now();
        $asig = $this->getAsignacion();
        $estamosVacaciones = DB::table("vacaciones_periodo")
            ->whereDate('fecha_inicio', '<=', $now)
            ->whereDate('fecha_fin', '>=', $now)
            ->where("periodo_id", $periodo->id)
            ->orderBy('created_at', 'desc')->first();
        $huboVacaciones = DB::table("vacaciones_periodo")
            ->whereDate('fecha_inicio', '>=', $asig->fecha_asig)
            ->whereDate('fecha_fin', '<=', $now)
            ->where("periodo_id", $periodo->id)
            ->orderBy('created_at', 'desc')->get();
        if ($estamosVacaciones) {
            $dias_v = 0;
            if ($huboVacaciones) {
                foreach ($huboVacaciones as $key => $vacacion) {
                    $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
                }
            }
            $dias = ($this->difDays($asig->fecha_asig, $estamosVacaciones->fecha_inicio) - $dias_v);
            $text = "Periodo de vacaciones activo. Días: " . ($dias);
        } elseif (count($huboVacaciones) > 0) {

            $dias_v = 0;
            foreach ($huboVacaciones as $key => $vacacion) {
                $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
            }
            $dias_asig  = $this->getDaysAfterAsig();
            $dias = $dias_asig - $dias_v;
            $text =  "<b>Días transcurridos desde la asignación:</b> " . $dias;
        } else if ($asig->periodo_id != $periodo->id) {
            //SI tuvo pausas
            $pausa = ExpedientePausas::whereHas("asignacion", function ($query) {
                $query->where('asigexp_id', $this->expid);
            })->orderBy('expedientes_pausa.created_at', 'desc')
                ->first();
            $dias = $this->difDays($periodo->prdfecha_inicio, now());
            $text =  "<b>Días transcurridos desde el inicio de periodo:</b> " . $dias;
            if ($pausa) {
                $dias = $this->difDays(date("2024-09-02"), now());
                $text =  "<b>Días transcurridos desde fin de pausa:</b> " . $dias;
            }
        } else {
            $dias = $this->getDaysAfterAsig();
            $text =  "<b>Días transcurridos desde la asignación:</b> " . $dias;
        }

        switch ($item) {
            case 'dias':
                return $dias;
                break;
            case 'mensaje':
                return $text;
                break;
            default:
                return $text;
                break;
        }
    }

    public function getDaysForNexAct()
    {
        $pausa = $this->asignacion->pausas()->orderBy('created_at', 'desc')->first();
        if ($this->expestado_id == 6) {
            if ($pausa) {
                $fecha = "desde " . getSmallDate($pausa->fecha_inicial) . " hasta " . getSmallDate($pausa->fecha_final);
                return $text =  "<b>El expediente estará en pausa " . $fecha . "</b>";
            }
            return $text =  "<b>El expediente esta en pausa</b>";
        }

        $act = $this->actuacion()
            ->where(['actusercreated' => $this->expidnumberest])
            ->where(function ($q) {
                $q->orwhere('actestado_id', '=', 101)
                    ->orwhere('actestado_id', '=', 102)
                    ->orwhere('actestado_id', '=', 138)
                    ->orwhere('actestado_id', '=', 139)
                    ->orwhere('actestado_id', '=', 104);
            })
            ->orderBy('actuacions.actfecha', 'desc')->first();
        $color = 'green';
        $dias = 0;
        $periodo = $this->getPeriodoActivo();
        $periodo->prdfecha_inicio = date("2024-08-26");
        $now = Carbon::now();
        $estamosVacaciones = DB::table("vacaciones_periodo")
            ->whereDate('fecha_inicio', '<=', $now)
            ->whereDate('fecha_fin', '>=', $now)
            ->where("periodo_id", $periodo->id)
            ->orderBy('created_at', 'desc')->first();
        if ($estamosVacaciones) {
            if ($act and ($act->actfecha < $estamosVacaciones->fecha_inicio)) {
                $dias = $this->difDays($act->actfecha, $estamosVacaciones->fecha_inicio);
                $text =  "<b>Periodo de vacaciones activo. Días: $dias</b>";
            } else if ($act) {
                $text =  "<b>Periodo de vacaciones activo</b>";
            } else {
                $asig = $this->getAsignacion();
                $dias = $this->difDays($asig->fecha_asig, $estamosVacaciones->fecha_inicio);
                $text = "Periodo de vacaciones activo. Días: " . $dias;
            }
            return $text;
        } else if ($pausa) {
            if ($act and ($act->actfecha > $pausa->fecha_final)) {
                $dias = $this->difDays($act->actfecha, date('Y-m-d'));
                $text =  "<b>Días transcurridos desde última actuación:</b>";
            } else {
                $dias = $this->difDays($pausa->fecha_final, date('Y-m-d'));
                $text =  "<b>Días transcurridos desde final de pausa:</b>";
            }
        } else {
            if ($act) {
                $huboVacaciones = DB::table("vacaciones_periodo")
                    ->whereDate('fecha_inicio', '>=', $act->actfecha)
                    ->whereDate('fecha_fin', '<=', $now)
                    ->where("periodo_id", $periodo->id)
                    ->orderBy('created_at', 'desc')->get();
                $dias_v = 0;
                if (count($huboVacaciones) > 0) {
                    foreach ($huboVacaciones as $key => $vacacion) {
                        $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
                    }
                    $dias = $this->difDays($act->actfecha, date('Y-m-d')) - $dias_v;
                } else {
                    $hizoEnVacaciones = DB::table("vacaciones_periodo")
                        ->whereDate('fecha_inicio', '<=', $act->actfecha)
                        ->whereDate('fecha_fin', '>=', $act->actfecha)
                        ->where("periodo_id", $periodo->id)
                        ->orderBy('created_at', 'desc')->first();
                    if (($hizoEnVacaciones)) {
                        $dias = $this->difDays($hizoEnVacaciones->fecha_fin, date('Y-m-d'));
                    } else {
                        //dd($act->actfecha , $periodo->prdfecha_inicio);
                        if ($act->actfecha < $periodo->prdfecha_inicio) {
                            $dias = $this->difDays($periodo->prdfecha_inicio, date('Y-m-d'));
                            $text =  "<b>Días transcurridos desde inicio de corte:</b>";
                        } else {
                            $dias = $this->difDays($act->actfecha, date('Y-m-d'));
                            $text =  "<b>Días transcurridos desde última actuación:</b>";
                        }
                    }
                }
            } else {
                $asignacion = $this->asignacion;
                $huboVacaciones = DB::table("vacaciones_periodo")
                    ->whereDate('fecha_inicio', '>=', $asignacion->fecha_asig)
                    ->whereDate('fecha_fin', '<=', $now)
                    ->where("periodo_id", $periodo->id)
                    ->orderBy('created_at', 'desc')->get();

                if ($huboVacaciones) {
                    $dias_v = 0;
                    foreach ($huboVacaciones as $key => $vacacion) {
                        $dias_v += $this->difDays($vacacion->fecha_inicio, $vacacion->fecha_fin);
                    }
                }
                $pausa = ExpedientePausas::with("asignacion")
                    ->whereHas("asignacion", function ($query) {
                        $query->where('asigexp_id', $this->expid);
                    })->orderBy('expedientes_pausa.created_at', 'desc')
                    ->first();

                if ($pausa) {
                    $dias = $this->difDays(date("2024-09-02"), now());
                    $text =  "<b>Días transcurridos desde fin de pausa:</b> ";
                } else {
                    $dias = $this->getDaysAfterAsig() - $dias_v;
                    if ($asignacion->periodo_id != $periodo->id) {
                        $dias = $this->difDays(date("2024-08-26"), now());
                        $text =  "<b>Días transcurridos desde inicio de corte:</b>";
                    } else {
                       // $dias = $this->difDays(date("2024-08-02"), now());
                        $dias = $this->getDaysAfterAsig() - $dias_v;
                        $text =  "<b>Días transcurridos desde la asignación:</b>";
                    }
                }
            }
        }
        if ($dias > 10) $color = 'orange';
        if ($dias > 20) $color = 'red';
        $text .=  " <span style='background-color:$color;color:#ffffff' class='pull-center badge'>$dias</span>";
        return $text;
    }

    public function isValidEvaPause()
    {
        $asignacion = $this->asignacion;
        if ($asignacion) {
            $pausas = $asignacion->pausas()->where('estado_id', 249)->orderBy('created_at', 'desc')->first();
            if (($pausas)) {
                if ($pausas->fecha_final < date('Y-m-d') and $this->expestado_id == 6) {
                    return true;
                }
            }
        }
        return false;
    }


    public function isValidOpenPeriodo()
    {


        $asig_periodo = $this->asignacion->periodo;
        $periodo = $this->getPeriodoActivo();
        if ($asig_periodo and $periodo and $asig_periodo->id == $periodo->id) {
            return true;
        }

        return false;
    }

    public function isValidOpenCorte()
    {
        $asignacion = $this->asignacion;
        $segmento = $this->getSegmentoActivo();
        $asigCorte = $this->getSegmentoAsignacion($asignacion);
        if ($segmento and $asigCorte and $segmento->id == $asigCorte->id) {
            return true;
        }
        return false;
    }

    public function isValidNotaMax()
    {
        $asignacion = $this->asignacion;
        $estado = $this->estados()->where('ref_estado_id', 4)->first();
        if ($estado != null) {
            return true;
        }
        return false;
        /* $segmento = $this->getSegmentoActivo();
        $asigCorte = $this->getSegmentoAsignacion($asignacion);
        //$asigEva = $this->getSegmentoEvaluacion($asignacion); 
        dd($estado);      
        if($segmento and $asigCorte and $segmento->id == $asigCorte ->id){
           return true;
        }
        return false; */
    }

    public function getCitas()
    {
        $asignacion = $this->getAsignacion();
        try {

            $can_edit = false;
            if ($asignacion->asig_docente !== null and $asignacion->asig_docente->docidnumber == auth()->user()->idnumber) {
                $can_edit = true;
            }
            $asignacion->citaciones->each(function ($citacion) use ($can_edit) {
                $citacion->can_edit = $can_edit;
            });
            return $asignacion->citaciones;
        } catch (\Throwable $th) {
            return [];
        }
    }
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\ColorTurnos;
use Illuminate\Support\Facades\Event;
use DB;
use App\User;
use App\Traits\AsigNotas;
use App\Traits\UploadFile;
use App\Segmento;
use App\HistorialDatosCaso;
use App\Services\ExpedientesService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use Illuminate\Support\Facades\App;

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

    /*  public function asigDocente($asignacion_caso)
    {
        $segmento = $this->segmentoService->getSegmentoActivo();

            $docente_unavi = $this->getDocentesByRama("UNAVI") ;
            $docente_unavi = $docente_unavi[0];

            $asig_doc = DB::select(
            DB::raw("SELECT `docidnumber`, `name`,COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
            JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
            JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
            JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
            JOIN periodo ON asignacion_caso.periodo_id = periodo.id
            JOIN segmentos ON periodo.id = segmentos.perid
            JOIN sede_usuarios ON sede_usuarios.user_id = users.id
            WHERE expedientes.exptipoproce_id = '1' AND users.active=1
            AND users.idnumber != $docente_unavi->idnumber 
            AND users.active_asignacion=1 AND segmentos.id = $segmento->segmento_id
            AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
            GROUP BY `docidnumber` ORDER BY num_casos ASC
             ")
            );

             $docentes = DB::table('users')
                ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
                ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
                ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
                ->where('role_id', '4')
                ->where('users.active', true)
                ->where('users.idnumber', '<>',$docente_unavi->idnumber)
                ->where('users.active_asignacion', true)
                ->where('sedes.id_sede', session('sede')->id_sede)
                ->select(
                    'users.active',
                    'users.id',
                    'ref_nombre',
                    'users.idnumber',
                    DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                    'role_user.role_id',
                    'roles.display_name'
                )->orderBy('users.created_at', 'desc')->get(); 
              //  dd($docentes,$asig_doc); 
        if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
                $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $asig_doc[0]->docidnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save();
            } else {
                foreach ($docentes as $key => $docente) {
                    $found_key = array_search($docente->idnumber, array_column($asig_doc, 'docidnumber'));
                    if ($found_key === false) {
                        $asignacion = new AsigDocenteCaso();
                        $asignacion->docidnumber = $docente->idnumber;
                        $asignacion->asig_caso_id = $asignacion_caso->id;
                        $asignacion->user_created_id = \Auth::user()->idnumber;
                        $asignacion->user_updated_id = \Auth::user()->idnumber;
                        $asignacion->save();
                        break;
                    }
                }
            }
        } elseif (count($docentes) > 0) {
            foreach ($docentes as $key => $docente) {
                $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $docente->idnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save();
                break;
            }
        }

        //dd($docentes,$asig_doc); 
    }
 */
    /* public function asigDocenteSeguimiento($asignacion_caso, $tipoproce)
    {
        $asig_doc = $this->getDocentesAsigByRama($tipoproce);

        $subRama = $asignacion_caso->expediente->rama_derecho->subrama;

        $doceWithRama = $this->getDocentesByRama($subRama);
        //dd($doceWithRama,$asig_doc);

        $arraydocentescompleto = [];
        $casoasignado = 0;
        foreach ($doceWithRama as $key1 => $docenterama) {
            $docexiste = 0;
            foreach ($asig_doc as $key2 => $docentecasos) {
                // echo $docenterama->idnumber."=".$docentecasos->docidnumber."<br>";
                if ($docenterama->idnumber == $docentecasos->docidnumber) {
                    $docexiste = 1;
                    $arraydocentescompleto[$docenterama->idnumber] = $docentecasos->num_casos;
                }
            }

            if ($docexiste == 0) {
                $casoasignado = 1;
               // dd($docenterama->idnumber,$subRama,"Aqui 1");
                $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $docenterama->idnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save();
                $asignado = true;
                break;
            }
        }
        if ($casoasignado == 0) {
            asort($arraydocentescompleto);
            //dd($docenterama->idnumber,$subRama,"Aqui 2");
            foreach ($arraydocentescompleto as $key => $numecasos) {
                $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $key;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save();
                $asignado = true;
                break;
            }
        }
    } */

    /*   private function getDocentesByRama($rama)
    {
        return $doceWithRama = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('user_has_ramasderecho', 'user_has_ramasderecho.user_id', '=', 'users.id')
            ->leftjoin('rama_derecho', 'rama_derecho.id', '=', 'ramaderecho_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->where('role_id', '4')
            ->where('rama_derecho.subrama', $rama)
            ->where('users.active', true)
            ->where('users.active_asignacion', true)
            ->where('sede_usuarios.sede_id', session('sede')->id_sede)
            ->select('users.id', 'users.idnumber')
            ->orderBy('users.created_at', 'desc')
            ->get()
            ->toArray(); 
    } */

    /*  private function getDocentesAsigByRama($tipoproce)
    {
        $segmento = $this->segmentoService->getSegmentoActivo();
        return $asig_doc = DB::select(
            DB::raw(
                "SELECT `docidnumber`, COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
        JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
        JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
        JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
        JOIN periodo ON asignacion_caso.periodo_id = periodo.id
        JOIN segmentos ON periodo.id = segmentos.perid
        JOIN sede_usuarios ON sede_usuarios.user_id = users.id
        WHERE expedientes.exptipoproce_id = '$tipoproce'
        AND sede_usuarios.sede_id = " .
                    session('sede')->id_sede .
                    "
        AND users.active=1 AND users.active_asignacion=1
        AND segmentos.id = $segmento->segmento_id
        GROUP BY `docidnumber` ORDER BY num_casos ASC
         ",
            ),
        );
    } */

    public function getActuaciones($only)
    {
        $service = App::make(ExpedientesService::class);
        return $service->getActuacions($this,$only);
    }

    function getDocenteAsig() 
    {
        $asig = $this->getAsignacion();
        //**   dd($asig);
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

            $now = $now == null ? Carbon::now() : Carbon::parse($now);
            $vacaciones_text = DB::table("vacaciones_periodo")
                ->whereDate('fecha_fin', '>=', $now)
                ->where("periodo_id", $periodo->id)->first();
            $fecha_asig = Carbon::parse($asig->fecha_asig);
            $fecha_max = Carbon::parse($asig->fecha_asig)->addDays(31);

            $_vacaciones = DB::table("vacaciones_periodo")
                ->whereDate('fecha_inicio', '>=', $fecha_asig)
                ->whereDate('fecha_fin', '<=', $fecha_max)
                ->where("periodo_id", $periodo->id)->first();
            // return  $_vacaciones;

            if ($_vacaciones) {
                $fecha_vaca_in = Carbon::parse($_vacaciones->fecha_inicio);
                $fecha_vaca_fin = Carbon::parse($_vacaciones->fecha_fin);
                $days_vac = $fecha_vaca_in->diffInDays($fecha_vaca_fin, false);
                $fecha_max->addDays($days_vac);
                $days = $now->diffInDays($fecha_max, false);
            } else {
                $_vacaciones = DB::table("vacaciones_periodo")
                    ->whereDate('fecha_inicio', '<=', $fecha_max)
                    ->whereDate('fecha_fin', '>=', $fecha_max)
                    ->where("periodo_id", $periodo->id)->first();
                if ($_vacaciones) {
                    $fecha_vaca_in = Carbon::parse($_vacaciones->fecha_inicio);
                    $fecha_vaca_fin = Carbon::parse($_vacaciones->fecha_fin);
                    if ($fecha_max >  $fecha_vaca_in and $fecha_max <  $fecha_vaca_fin) {
                        $days_pas = $fecha_vaca_in->diffInDays($fecha_max, false);
                        $days_vac = $fecha_vaca_in->diffInDays($fecha_vaca_fin, false);
                        $days = $days_pas + $days_vac;
                        //dd($days,$fecha_asig,$days_pas,$days_vac );
                        if ($fecha_vaca_fin < $now) {
                            $days = $fecha_vaca_fin->diffInDays($now, false);
                            $days =   $days_pas - $days;
                        }
                    }
                } else {
                    $days = $now->diffInDays($fecha_max, false);
                }
            }
            $dias = $days;
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
                    return $vacaciones_text ? "Vacaciones" : $mgs;
                    break;
                case 'dias':
                    return $dias;
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
            if (\Auth::user()->hasRole('estudiante')) {
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

    public function setNotActLimit($date = null)
    {
        $fecha_limit = Carbon::now();
        $padresAct = DB::table('actuacions')
            ->join('revisiones_actuacion', 'actuacions.id', '=', 'revisiones_actuacion.parent_rev_actid')
            ->where([['actestado_id', '<>', '136'], ['actestado_id', '<>', '138'], ['actestado_id', '<>', '139'], ['actestado_id', '<>', '174'], ['actestado_id', '<>', '175'], ['actestado_id', '<>', '176'], ['actestado_id', '<>', '177'], ['actestado_id', '<>', '178'], ['actidnumberest', $this->expidnumberest], ['actexpid', $this->expid]])
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
                AND actestado_id <> 136 AND actestado_id <> 138
                ORDER BY rev_actid DESC LIMIT 1"),
                );
                if ($hijosAct[0]->fecha_limit !== null) {
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


                    if (count($hijosAct) > 0 and $hijosAct[0]->actestado_id != 104 and $hijosAct[0]->actestado_id != 101 and $hijosAct[0]->actestado_id != 139 and $hijosAct[0]->fecha_limit !== null and $fecha_limit < $date) {
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
                            'docidnumber' => \Auth::user()->idnumber,
                            'tbl_org_id' => $actuacion->id,
                        ];
                        //
                        $actuacion->actestado_id = 139;
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

            //  dd($request->all());
            return $query->Orwhere(['expidnumberest' => $request->expidnumberest])
                ->where('exptipoproce_id', $request->exptipoproce_id)
                ->where('expestado_id', $request->expestado_id);

            if ($request->estado_id) {
                //return $query->where('expestado_id', $data);
            }
            //dd("dd00");
        }

        if (trim($request->data) != '') {

            $data = $request->data;
            switch ($request->tipo_busqueda) {

                case 'codido_exp':
                    return $query->where('expid', 'like', '%' . $data);
                    break;
                case 'estudiante':
                case 'estudiante_num':
                    return $query->where(['expidnumberest' => $data]);
                    break;
                case 'idnumber_doc':
                    return $query->where('asignacion_docente_caso.docidnumber', $data);
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

                    if ($data == 'green') {
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
        $asig = $this->getAsignacion();
        if ($asig) {
            $historial = HistorialDatosCaso::where('hisdc_expidnumber', $this->expid)
                ->where('hisdc_tipo_datos_caso', $tipo)
                ->where('hisdc_idnumberest_id', $this->expidnumberest)
                ->where('created_at', '>=', $asig->fecha_asig)
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
        $asig = $this->getAsignacion();
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

    public function getDaysForNexAct()
    {
        $act = $this->actuacion()
            ->where(['actusercreated' => $this->expidnumberest])
            ->orderBy('actuacions.actfecha', 'desc')->first();
        $color = 'green';
        $dias = 0;
        if ($act) {
            $dias = $this->difDays($act->actfecha, date('Y-m-d'));
            $text =  "<b>Días transcurridos desde última actuación:</b>";
        } else {
            $dias = $this->getDaysAfterAsig();
            $text =  "<b>Días transcurridos desde la asignación:</b>";
        }
        if ($dias > 10) $color = 'orange';
        if ($dias > 20) $color = 'red';


        $text .=  " <span style='background-color:$color;color:#ffffff' class='pull-center badge'>$dias</span>";



        return $text;
    }

    public function isValidOpen()
    {
        $expediente_estado = $this->estados()
            ->where('ref_estado_id', 4)
            ->orderBy('created_at', 'desc')->get();
        $dias = $this->getDaysAfterAsig();
        if ($dias <= 55) {
            return true;
        }
        return false;
    }

    public function getCitas()
        {
            $asignacion = $this->getAsignacion();
            $can_edit = false;
            if ($asignacion->asig_docente !== null and $asignacion->asig_docente->docidnumber == auth()->user()->idnumber) {
                $can_edit = true;
            }
            $asignacion->citaciones->each(function ($citacion) use ($can_edit) {
                $citacion->can_edit = $can_edit;
            });
            return $asignacion->citaciones;
        }
}

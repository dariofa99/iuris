<?php

namespace App\Repositories;

use App\Actuacion;
use App\AsignacionCaso;
use App\Expediente;
use App\ExpedientePausas;
use App\Sede;
use App\Services\AsignacionDocenteCasosService;
use App\Services\ExpedientesService;
use App\Services\PausasService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use App\Services\UsersService;
use App\Services\VacacionesService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ExpedientesRepository extends BaseRepository implements ExpedientesService
{

    private $periodoService;
    private $segmentoService;
    private $usersService;
    private $asignacionDocenteCasoService;
    private $request;
    private $pausaService;
    private $vacacionesService;
    public function __construct(
        Expediente $model,
        AsignacionDocenteCasosService $asignacionDocenteCasoService
    ) {
        parent::__construct($model);
        $this->periodoService = App::make(PeriodosService::class);
        $this->segmentoService = App::make(SegmentosService::class);
        $this->usersService = App::make(UsersService::class);
        $this->pausaService = App::make(PausasService::class);
        $this->vacacionesService = App::make(VacacionesService::class);
        $this->request = App::make(Request::class);

        $this->asignacionDocenteCasoService = $asignacionDocenteCasoService;
    }
    public function index(Request $request)
    {
        //dd("");
        //$this->query = $this->model;
        $order = "CASE
        WHEN expestado_id = 1 THEN 1
        WHEN expestado_id = 4 THEN 2
        WHEN expestado_id = 3 THEN 3
        WHEN expestado_id = 2 THEN 4
        WHEN expestado_id = 5 THEN 5
        ELSE 6
        END";
        if ((currentUser()->hasRole('docente') || currentUser()->active_asignacion)) {
            $order = "CASE
              WHEN expestado_id = 4 THEN 1
              WHEN expestado_id = 1 THEN 2
              WHEN expestado_id = 3 THEN 3
              WHEN expestado_id = 2 THEN 4
              WHEN expestado_id = 5 THEN 5
              ELSE 6
          END";
        } else if (currentUser()->hasRole('estudiante')) {
            $order = "CASE
              WHEN expestado_id = 3 THEN 1
              WHEN expestado_id = 1 THEN 2
              WHEN expestado_id = 4 THEN 3
              WHEN expestado_id = 2 THEN 4
              WHEN expestado_id = 5 THEN 5
              ELSE 6
              END";
        }

        $this->applyValidateSede();
        return $this->query //->join('asignacion_caso', 'asignacion_caso.asigexp_id', '=', 'expedientes.expid')
            // ->where('expedientes.expidnumberest', '<>', 3030)
            ->where(function ($query) use ($request) {
                if (
                    !$request->has('data') and
                    !$request->has('search_onlyMy_exp')
                    || ($request->has('search_onlyMy_exp')
                        and $request->input('search_onlyMy_exp') != 'off')
                ) {
                    $query->where([
                        ["expestado_id", "<>", 2],
                        ["expestado_id", "<>", 7],
                        ["expestado_id", "<>", 8],
                    ]);
                }
            })
            ->where(function ($query) use ($request) {
                if ((currentUser()->hasRole('docente') || currentUser()->active_asignacion)
                    and (!$request->has('search_onlyMy_exp')
                        || ($request->has('search_onlyMy_exp') and $request->input('search_onlyMy_exp') != 'off'))
                ) {
                    $query->whereHas('asignaciones.asig_docente', function ($q) {
                        $q->where('asignacion_docente_caso.docidnumber', Auth::user()->idnumber)
                            ->where('asignacion_docente_caso.activo', 1);
                    });
                } else if (currentUser()->hasRole('estudiante')) {
                    $query->whereHas('asignaciones', function ($q) {
                        $q->where('expedientes.expidnumberest', '=', currentUser()->idnumber)
                            ->where('asignacion_caso.activo', '=', 1)
                            ->where('asignacion_caso.asigest_id', '=', currentUser()->idnumber);
                    });
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->get('search_onlyProJur')) {
                    $query->whereHas('asignaciones', function ($q) {
                        $q->where('asignacion_caso.procesojud_id', '<>', 1)
                            ->where('asignacion_caso.activo', '=', 1);
                    });
                }
            })
            ->Criterio($request)
            ->orderByRaw($order)
            ->orderBy("expedientes.created_at", "DESC")
            ->paginate(10);
    }

    public function getColorsAsesorias(Request $request)
    {
        $this->query = $this->model;
        $this->applyValidateSede();
        return $this->query->join('asignacion_caso', 'asignacion_caso.asigexp_id', '=', 'expedientes.expid')
            ->where(function ($query) use ($request) {
                if ((currentUser()->hasRole('docente') || currentUser()->active_asignacion)
                    and (!$request->has('search_onlyMy_exp') || ($request->has('search_onlyMy_exp') and $request->input('search_onlyMy_exp') != 'off'))
                ) {
                    $query->whereHas('asignaciones.asig_docente', function ($q) {
                        $q->where('asignacion_docente_caso.docidnumber', Auth::user()->idnumber)
                            ->where('asignacion_docente_caso.activo', 1);
                    });
                } else if (currentUser()->hasRole('estudiante')) {
                    dd("");
                    $query->where('expedientes.expidnumberest', '=', currentUser()->idnumber)
                        ->where('asignacion_caso.activo', '=', 1)
                        ->where('asignacion_caso.asigest_id', '=', currentUser()->idnumber);
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->get('search_onlyProJur')) {
                    $query->where('asignacion_caso.procesojud_id', '<>', 1);
                }
            })
            ->Criterio($request)
            ->where('expedientes.exptipoproce_id', 1)
            ->where('expedientes.expestado_id', "<>", 2)
            ->selectRaw('SUM(IF(DATEDIFF(NOW(), `fecha_asig`) <= 10, 1, 0)) AS verde')
            ->selectRaw('SUM(IF(DATEDIFF(NOW(), `fecha_asig`) <= 20 AND DATEDIFF(NOW(), `fecha_asig`) > 10, 1, 0)) AS amarillo')
            ->selectRaw('SUM(IF(DATEDIFF(NOW(), `fecha_asig`) > 20 AND DATEDIFF(NOW(), `fecha_asig`) < 30, 1, 0)) AS rojo')
            ->selectRaw('SUM(IF(DATEDIFF(NOW(), `fecha_asig`) >= 30, 1, 0)) AS gris')
            ->get();
    }

    public function store(Request $request): Expediente
    {
        //

        $this->model->expid = $request->input('expid');
        $this->model->expfecha = ($request->has('expfecha') and $request->input('expfecha') != null) ? $request->input('expfecha') : date('Y-m-d');
        $this->model->expramaderecho_id = $request->has('expramaderecho_id') ? $request->input('expramaderecho_id') : 1;
        $this->model->expestado_id = $request->has('expestado_id') ? $request->input('expestado_id') : 1;
        $this->model->expidnumber = $request->input('expidnumber');
        $this->model->exptipoproce_id = $request->has('exptipoproce_id') ? $request->input('exptipoproce_id') : 1;
        $this->model->exptipocaso_id = ($request->has('exptipocaso_id') and $request->input('exptipocaso_id') != null) ? $request->input('exptipocaso_id') : 22;
        $this->model->expdesccorta = $request->has('expdesccorta') ? $request->input('expdesccorta') : '';
        $this->model->expidnumberest = $request->input('expidnumberest');
        $this->model->expdepto_id = $request->has('expdepto_id') ? $request->input('expdepto_id') : 96;
        $this->model->expmunicipio_id = $request->has('expmunicipio_id') ? $request->input('expmunicipio_id') : 24;
        $this->model->exptipovivien_id = $request->has('exptipovivien_id') ? $request->input('exptipovivien_id') : 90;
        $this->model->expperacargo = $request->has('expperacargo') ? $request->input('expperacargo') : '.';
        $this->model->expingremensual = $request->has('expingremensual') ? $request->input('expingremensual') : 0;
        $this->model->expegremensual = $request->has('expegremensual') ? $request->input('expegremensual') : 0;
        $this->model->exphechos = $request->has('exphechos') ? $request->input('exphechos') : '';
        $this->model->exprtaest = $request->has('exprtaest') ? $request->input('exprtaest') : '';
        $this->model->expjuzoent = $request->has('expjuzoent') ? $request->input('expjuzoent') : '';
        $this->model->expnumproc = $request->has('expnumproc') ? $request->input('expnumproc') : '';
        $this->model->exppersondemandante = $request->has('exppersondemandante') ? $request->input('exppersondemandante') : '';
        $this->model->exppersondemandada = $request->has('exppersondemandada') ? $request->input('exppersondemandada') : '';
        $this->model->expfechalimite = $request->has('expfechalimite') ? $request->input('expfechalimite') : null;
        $this->model->expfecha_res = $request->has('expfecha_res') ? $request->input('expfecha_res') : null;
        $this->model->expfecha_res = $request->has('es_projuridico') ? $request->input('es_projuridico') : 0;
        $this->model->expusercreated = currentUser()->idnumber;
        $this->model->expuserupdated = currentUser()->idnumber;
        $this->model->save();
        if ($request->has('sede_id')) {
            $sede = Sede::find($request->get('sede_id'));
            session(["sede" => $sede]);
            $this->model->sedes()->attach(session('sede')->id_sede);
        } else {
            if (session()->has('sede')) {
                $this->model->sedes()->attach(session('sede')->id_sede);
            }
        }
        return $this->model;
    }

    public function update(Expediente $expediente, Request $request): Expediente
    {
        $request['expuserupdated'] = currentUser()->idnumber;
        $expediente->fill($request->all());
        $expediente->save();
        // Event::dispatch('expediente.updated', $expediente);
        return $expediente;
    }


    //Asigna docentes para asesorias
    public function asignarDocente(AsignacionCaso $asignacion_caso)
    {
        $asig_doc = DB::select(
            DB::raw("SELECT `docidnumber`, `name`,COUNT(`docidnumber`) AS num_casos,
                    CASE 
                        WHEN `docidnumber` = '98378318' THEN 4
                       
                        ELSE 16 -- Valor por defecto si el docidnumber no coincide con ninguno de los anteriores
                    END AS num_hours FROM `asignacion_docente_caso`
                JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
                JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
                JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
                JOIN sede_usuarios ON sede_usuarios.user_id = users.id
                JOIN role_user ON role_user.user_id = users.id
                WHERE expedientes.exptipoproce_id = '1'
                AND users.active = 1
               
                AND  expedientes.expestado_id = '1'
                AND (users.active_asignacion = 1)
                AND sede_usuarios.sede_id =  '" . session('sede')->id_sede . "'
                GROUP BY `docidnumber` ORDER BY num_casos ASC")
        );

        $docentes = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where(function ($query) {
                $query->orwhere('users.active_asignacion', true);
            })
            ->where('users.active', true)
            // ->where('users.idnumber', "<>", 98378318)
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->select(
                'users.active',
                'users.id',
                'ref_nombre',
                'users.idnumber',
                DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                'role_user.role_id',
                'roles.display_name'
            )
            ->orderBy('users.created_at', 'desc')
            ->groupBy('users.idnumber')->get();

        /*    foreach ($asig_doc as $person) {
                $person->num_casos = round($person->num_casos / $person->num_hours);
            }
            $min_casos = min(array_map(function ($person) {
                return $person->num_casos;
            }, $asig_doc));
            // Filtra los elementos que tienen el valor mínimo de num_casos
            $person_with_min_casos = array_filter($asig_doc, function ($person) use ($min_casos) {
                return $person->num_casos == $min_casos;
            });
    
            // Si necesitas solo uno de los resultados (en caso de empate)
            $person_with_min_casos = reset($person_with_min_casos); */
        // dd($docentes,$asig_doc);
        $this->request['asig_caso_id']  = $asignacion_caso->id;
        if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
                $this->request['docidnumber']  = $asig_doc[0]->docidnumber;
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                return;
            } else {
                foreach ($docentes as $key => $docente) {
                    $found_key = array_search($docente->idnumber, array_column($asig_doc, 'docidnumber'));
                    if ($found_key === false) {

                        $this->request['docidnumber']  = $docente->idnumber;
                        $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                        break;
                    }
                }
            }
        } elseif (count($docentes) > 0) {
            foreach ($docentes as $key => $docente) {
                $this->request['docidnumber']  = $docente->idnumber;
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                break;
            }
        }
    }

    private function getDocentesAsigByTypeProcessAndRama($tipoproce, $subRama)
    {
        $segmento = $this->segmentoService->getSegmentoActivo();

        return $asig_doc = DB::select(
            DB::raw(
                "SELECT `docidnumber`, `name`, COUNT(`docidnumber`) AS num_casos, 
                    CASE 
                        WHEN `docidnumber` = '98378318' THEN 4
                       
                        ELSE 16
                    END AS num_hours
                FROM `asignacion_docente_caso`
                JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
                JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
                JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
                JOIN user_has_ramasderecho ON user_has_ramasderecho.user_id = users.id
                JOIN rama_derecho ON rama_derecho.id = user_has_ramasderecho.ramaderecho_id
                JOIN periodo ON asignacion_caso.periodo_id = periodo.id        
                JOIN sede_usuarios ON sede_usuarios.user_id = users.id
                JOIN role_user ON role_user.user_id = users.id
                WHERE expedientes.exptipoproce_id = '$tipoproce'        
                AND users.active = 1 
                AND expedientes.expestado_id = 1
                AND (users.active_asignacion = 1)
                AND asignacion_docente_caso.activo = 1
                AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
                AND rama_derecho.subrama = '" . $subRama . "'
                GROUP BY `docidnumber` 
                ORDER BY num_casos ASC"
            )
        );


        return $asig_doc = DB::select(
            DB::raw(
                "SELECT `docidnumber`, `name`, COUNT(`docidnumber`) AS num_casos 
        FROM `asignacion_docente_caso`
        JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
        JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
        JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
        JOIN user_has_ramasderecho ON user_has_ramasderecho.user_id = users.id
        join rama_derecho ON rama_derecho.id = user_has_ramasderecho.ramaderecho_id
        JOIN periodo ON asignacion_caso.periodo_id = periodo.id        
        JOIN sede_usuarios ON sede_usuarios.user_id = users.id
        JOIN role_user ON role_user.user_id = users.id
        WHERE expedientes.exptipoproce_id = '$tipoproce'        
        AND users.active=1 
        AND expedientes.expestado_id = 1
        AND (users.active_asignacion=1)
        AND asignacion_docente_caso.activo = 1
        
        AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
        AND rama_derecho.subrama = '" . $subRama . " '
        GROUP BY `docidnumber` ORDER BY num_casos ASC"
            )
        );

        //AND segmentos.id = $segmento->segmento_id
    }

    public function asignargDocenteSeguimiento($asignacion_caso, $tipoproce)
    {
        $subRama =  $asignacion_caso->expediente->rama_derecho->subrama;
        //$antes = $this->getDocentesAsigByTypeProcessAndRama($tipoproce, $subRama);
        $asig_doc = $this->getDocentesAsigByTypeProcessAndRama($tipoproce, $subRama);
        $docentes = $this->usersService->getDocentesByRama($subRama);
        $this->request['asig_caso_id']  = $asignacion_caso->id;
        if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
                foreach ($asig_doc as $person) {
                    $person->num_casos = round($person->num_casos / $person->num_hours);
                }
                $min_casos = min(array_map(function ($person) {
                    return $person->num_casos;
                }, $asig_doc));
                // Filtra los elementos que tienen el valor mínimo de num_casos
                $person_with_min_casos = array_filter($asig_doc, function ($person) use ($min_casos) {
                    return $person->num_casos == $min_casos;
                });

                // Si necesitas solo uno de los resultados (en caso de empate)
                $person_with_min_casos = reset($person_with_min_casos);

                $this->request['docidnumber']  = $person_with_min_casos->docidnumber;
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                return;
            } else {
                foreach ($docentes as $key => $docente) {
                    $found_key = array_search($docente['idnumber'], array_column($asig_doc, 'docidnumber'));
                    if ($found_key === false) {
                        $this->request['docidnumber']  = $docente['idnumber'];
                        $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                        break;
                    }
                }
            }
        } elseif (count($docentes) > 0) {
            foreach ($docentes as $key => $docente) {
                $this->request['docidnumber']  = $docente['idnumber'];
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                break;
            }
        }
    }

    public function getDaysForEval($asignacion, $fecha_1, $fecha_2, $numeval)
    {

        $fecha_1 = Carbon::parse($fecha_1)->startOfDay();
        $fecha_2 = Carbon::parse($fecha_2)->endOfDay();
        $dias_t = getDiffDays($fecha_1, $fecha_2);
        $total = 0;
        //\dd($dias_t);          
        /* if ($dias_t > $numeval) { */
        //Evaluar si se realizo en vacaciones
        $_vacaciones = $this->vacacionesService->getByDates([
            ['operador' => "<=", "value" => $fecha_2],
            ['operador' => ">=", "value" => $fecha_2]
        ]);

        //Evaluar si se realizo en pausa
        $pausas = $this->pausaService->getByAsignacion($asignacion, [
            ['operador' => "<=", "value" => $fecha_2],
            ['operador' => ">=", "value" => $fecha_2]
        ]);

        //SI HAY VACACIONES y PAUSAS
        if (count($_vacaciones) > 0 and count($pausas) > 0) {

            //Evaluar si o buscar la fecha inicial menor entre vacaciones y pausas,
            //es decir, busca en que fecha inicio primero
            $fecha_vaca_in = Carbon::parse($_vacaciones[0]->fecha_inicio);
            $fecha_pausa_in = Carbon::parse($pausas[0]->fecha_inicial);
            if ($fecha_vaca_in < $fecha_pausa_in) {
                $fecha_fin_1 = $fecha_vaca_in;
            } else {
                $fecha_fin_1 = $fecha_pausa_in;
            }
            //Evalu ar si o buscar la fecha final mayor entre vacaciones y pausas,
            //es decir, busca que fecha final es mayor
            $fecha_vaca_fin = Carbon::parse($_vacaciones[0]->fecha_fin);
            $fecha_pausa_fin = Carbon::parse($pausas[0]->fecha_final);
            if ($fecha_vaca_fin > $fecha_pausa_fin) {
                $fecha_in_2 = $fecha_vaca_fin;
            } else {
                $fecha_in_2 = $fecha_pausa_fin;
            }

            //Contar desde fecha de asignacion hasta la fecha 1
            $dias_pausado_1 = getDiffDays($fecha_1, $fecha_fin_1);
            // $dias_pausado_2 = getDiffDays($fecha_in_2, Carbon::now()->endOfDay());

            return [
                'dias_pausado' => $dias_pausado_1,
                'fecha_inicial' => $fecha_in_2
            ];

            $total = $dias_pausado_1 + $dias_pausado_2;
            // Log::info($fecha_in_2);
        } elseif (count($_vacaciones) > 0) {
            //Si solo vencio en vacaciones            
            $fecha_fin_1 = Carbon::parse($_vacaciones[0]->fecha_inicio);

            $dias_pausado_1 = getDiffDays($fecha_1, $fecha_fin_1);
            $fecha_in_2 = Carbon::parse($_vacaciones[0]->fecha_fin);
            //$dias_pausado_2 = getDiffDays($fecha_in_2, $fecha_2);
            return [
                'dias_pausado' => $dias_pausado_1,
                'fecha_inicial' => $fecha_in_2
            ];
        } else if (count($pausas) > 0) {
            //Si solo vencio en pausa
            $fecha_fin_1 = Carbon::parse($pausas[0]->fecha_inicial);
            $fecha_in_2 = Carbon::parse($pausas[0]->fecha_final);
            $dias_pausado_1 = getDiffDays($fecha_1, $fecha_fin_1);
            $dias_pausado_2 = getDiffDays($fecha_in_2, $fecha_2);
            return [
                'dias_pausado' => $dias_pausado_1,
                'fecha_inicial' => $fecha_in_2
            ];
            $total = $dias_pausado_1 + $dias_pausado_2;
            // Log::info($fecha_in_2);
        } else {

            //Si no se creo ni en vacaciones ni pausas                
            $dias_pausado_1 = $this->getDaysForEval2($fecha_1, $fecha_2, $asignacion);

            $dias_pausado = getDiffDays($fecha_1, $fecha_2) - 1;
            $total = $dias_pausado - $dias_pausado_1;

            // dd($dias_pausado_1, $dias_pausado,$total,$fecha_1, $fecha_2);

            return [
                'dias_pausado' => $total,
                'fecha_inicial' => $fecha_1,
                'fecha_iniciales' => $fecha_2
            ];
            $total = $dias_pausado - $dias_pausado_1;
            // Log::info(" * $fecha_1");
        }
    }


    public function calcularFechaPorDiasEfectivos(
        Carbon $fechaInicio,
        int $diasObjetivo,
        $pausas,
        $vacaciones
    ) {
        $fecha = $fechaInicio->copy();
        $diasContados = 0;
        $diaspasados = 0;
        $dias_pausado = 0;
        while ($diasContados < $diasObjetivo) {

            if (!$this->estaBloqueado($fecha, $pausas, $vacaciones)) {
                $diasContados++;
                //Log::info("Contando dia: " . $fecha->toDateString() . " - Dias contados: $diasContados");
            } else {
                $dias_pausado++;
                // Log::info("Dia bloqueado: " . $fecha->toDateString() . " - Dias pausados: $dias_pausado");
            }

            if ($diasContados < $diasObjetivo) {
                $fecha->addDay();
                // Log::info("Siguiente dia a evaluar: " . $fecha->toDateString());
            }
            $diaspasados++;
            // Log::info("Dias pasados: $diaspasados");
        }

        return $fecha;
    }



    private function estaBloqueado(Carbon $fecha, $pausas, $vacaciones): bool
    {
        foreach ($pausas as $p) {
            if ($fecha->between(
                Carbon::parse($p->fecha_inicial),
                Carbon::parse($p->fecha_final)
            )) {
                return true;
            }
        }

        foreach ($vacaciones as $v) {
            if ($fecha->between(
                Carbon::parse($v->fecha_inicio),
                Carbon::parse($v->fecha_fin)
            )) {
                return true;
            }
        }

        return false;
    }

    public function calcularDias($fecha_1, $fecha_2, $asignacion)
    {
        $pausas = $asignacion->pausas()
            ->whereDate('fecha_inicial', ">=", $fecha_1)
            ->whereDate('fecha_final', "<=", $fecha_2)
            ->orderBy('fecha_inicial', 'asc')
            ->get();

        $_vacaciones = $this->vacacionesService->getByDates([
            ['operador' => ">=", "value" => $fecha_1],
            ['operador' => "<=", "value" => $fecha_2]
        ]);

        $fecha = $this->calcularFechaPorDiasEfectivos(
            Carbon::parse($fecha_1)->startOfDay(),
            30,
            $pausas,
            $_vacaciones
        );

        return $fecha;
    }


    public function getDaysForEval2($fecha_1, $fecha_2, $asignacion)
    {
        $dias_sin_hechos =  6;/* Carbon::parse($fecha_1)
            ->diffInDays($fecha_2) */;
        // dd($dias_sin_hechos)  ; 
        $days_pausado = 0;
        if ($dias_sin_hechos > 1) {
            //evaluar si hubieron pausas

            $pausas = $asignacion->pausas()
                ->whereDate('fecha_inicial', ">=", $fecha_1)
                ->whereDate('fecha_final', "<=", $fecha_2)
                ->orderBy('fecha_inicial', 'asc')
                ->get();

            $_vacaciones = $this->vacacionesService->getByDates([
                ['operador' => ">=", "value" => $fecha_1],
                ['operador' => "<=", "value" => $fecha_2]
            ]);

            //Evaluar los dias de vacaciones teniendo en cuenta las pausas, solo se 
            //toman en cuenta las vacaciones que no se dieron mientras estaba pausado

            foreach ($_vacaciones as $key => $_vacacion) {
                $pausas_ = $this->pausaService->getByAsignacion($asignacion, [
                    ['operador' => "<=", "value" => $_vacacion->fecha_inicio],
                    ['operador' => ">=", "value" => $_vacacion->fecha_inicio]
                ]);
                if (count($pausas_) > 0) {
                    if ($_vacacion->fecha_fin > $pausas_[0]->fecha_final) {
                        $days_pausado += getDiffDays($pausas_[0]->fecha_final, $_vacacion->fecha_fin);

                        //  Log::info(getDiffDays($pausas_[0]->fecha_final, $_vacacion->fecha_fin) . 'fecha final de la pausa: ' . $pausas_[0]->fecha_final . " - $_vacacion->fecha_fin");
                    }
                } else {
                    $days_pausado += getDiffDays($_vacacion->fecha_inicio, $_vacacion->fecha_fin);
                    // Log::info(getDiffDays($_vacacion->fecha_inicio, $_vacacion->fecha_fin) . ' fecha final de la pausa: ' . $_vacacion->fecha_inicio . " - $_vacacion->fecha_fin");
                }
            }

            $days_pausado_ = $this->pausaService->getDays($pausas);
            // dd($days_pausado_, $_vacaciones,$days_pausado); 
            return $days_pausado + $days_pausado_;

            //
        }
    }

    public function getActuacions($expediente, $onlyEst)
    {
        $docente = $expediente->getDocenteAsig();
        return Actuacion::whereHas('revisionesExp', function ($query) use ($expediente) {
            $query->where('rev_actexpid', '=', $expediente->expid);
        })
            ->where(function ($query) use ($expediente, $onlyEst, $docente) {
                if ($onlyEst) {
                    $query->where('actidnumberest', '=', $expediente->expidnumberest)
                        ->orwhere(function ($query) use ($expediente, $docente) {
                            $query->where('actidnumberest', '=', $expediente->expidnumberest)
                                ->where('actusercreated', '=', $docente->idnumber);
                        });
                } else {
                    $query->where('actidnumberest', '<>', $expediente->expidnumberest);
                }
            })
            ->where([
                'actexpid' => $expediente->expid
            ])
            ->orderBy('created_at', 'desc')->get();
    }

    public function getExpeUser(User $user)
    {
        $this->query = $this->model;
        $this->applyValidateSede();
        return $this->query->whereHas('solicitante', function ($query) use ($user) {
            $query->where('expidnumber', $user->idnumber);
        })
            ->join('ref_estados', 'expedientes.expestado_id', '=', 'ref_estados.id')
            ->groupBy('ref_estados.nombre_estado')
            ->selectRaw('ref_estados.nombre_estado, count(*) as count')
            ->get();
    }

    public function pausarExpediente($expediente, Request $request)
    {
        $asignacion = $expediente->asignacion;
        ExpedientePausas::create([
            'fecha_inicial' => $request->input('fecha_inicial'),
            'fecha_final' => $request->input('fecha_final'),
            'userestud_id' => $asignacion->estudiante->id,
            'asig_caso_id' => $asignacion->id,
            'user_id' => currentUser()->id,
            'estado_id' => 249
        ]);
        return $asignacion;
    }
    public function deletePausa($id)
    {
        $pausa =  ExpedientePausas::find($id);
        $pausa->delete();
        return $pausa;;
    }
    public function updatePausa($id, Request $request)
    {
        $pausa = ExpedientePausas::find($id);
        $pausa->fill($request->all());
        $pausa->save();
        return $pausa;
    }

    public function getCasosAbandono(Request $request)
    {
        return $this->getCasosAbandonoQuery($request)->paginate(100);
    }

    public function getCasosAbandonoQuery(Request $request)
    {


        //$peri = $this->periodoService->getPeriodoActivo();

        if (!$request->has('dias_sin_actuaciones') || $request->dias_sin_actuaciones == '') {
            $request['dias_sin_actuaciones'] = 40;
        }
        $dias_limite = max(1, (int) $request->input('dias_sin_actuaciones', 40));
        $casos = DB::table('expedientes')
            ->join('asignacion_caso', 'asignacion_caso.asigexp_id', '=', 'expedientes.expid')
            ->join('asignacion_docente_caso', 'asignacion_docente_caso.asig_caso_id', '=', 'asignacion_caso.id')
            ->join('users as estudiante', 'estudiante.idnumber', '=', 'asignacion_caso.asigest_id')
            ->join('users as solicitante', 'solicitante.idnumber', '=', 'expedientes.expidnumber')
            ->join('users as docente', 'docente.idnumber', '=', 'asignacion_docente_caso.docidnumber')
            ->join('ref_estados', 'ref_estados.id', '=', 'expedientes.expestado_id')
            ->join('ref_tipproceso', 'ref_tipproceso.id', '=', 'expedientes.exptipoproce_id')
            ->select([
                'expedientes.expid',
                'asignacion_caso.fecha_asig',
                DB::raw("CONCAT(estudiante.name,' ',estudiante.lastname) as estudiante"),
                DB::raw("CONCAT(solicitante.name,' ',solicitante.lastname) as usuario_sol"),
                'ref_estados.nombre_estado as estado',
                'ref_tipproceso.ref_tipproceso as proceso',
                DB::raw("CONCAT(docente.name,' ',docente.lastname) as docente_as"),
                'expedientes.exphechos',
                'expedientes.exprtaest'
            ])
            // Última actuación realizada por el estudiante
            ->selectSub(function ($query) {

                $query->from('actuacions')
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn(
                        'actuacions.actexpid',
                        'expedientes.expid'
                    )
                    ->whereColumn(
                        'actuacions.actusercreated',
                        'expedientes.expidnumberest'
                    );
            }, 'fecha_ultima_actuacion')

            // Última modificación/redacción del caso
            ->selectSub(function ($query) {

                $query->from('historial_datos_casos')
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn(
                        'historial_datos_casos.hisdc_expidnumber',
                        'expedientes.expid'
                    );
            }, 'fecha_redaccion')

            // Estados
            ->whereIn('ref_estados.id', [1, 3])

            // Excluir estudiante
            ->where(
                'expedientes.expidnumberest',
                '<>',
                3030
            )

            // Excluir tipo de proceso
            ->where(
                'expedientes.exptipoproce_id',
                '<>',
                1
            )

            // Solo asignaciones activas
            ->where(
                'asignacion_caso.activo',
                1
            )

            // Filtros actuales
            ->where(function ($query) use ($request) {

                if (auth()->user()->hasRole('docente')) {

                    $query->where(
                        'asignacion_docente_caso.docidnumber',
                        auth()->user()->idnumber
                    );
                }
            })
            ->where(function ($query) use ($request) {

                if ($request->has('documento') && $request->documento != '') {

                    $query->where(
                        'expedientes.expidnumberest',
                        'like',
                        '%' . $request->documento . '%'
                    );
                }

                if ($request->has('periodo') && $request->periodo != '') {

                    $query->where(
                        'asignacion_caso.periodo_id',
                        $request->periodo
                    );
                }
            })
            ->where('asignacion_docente_caso.activo', 1)

            // ==========================================================
            // CASOS OLVIDADOS
            // ==========================================================

            // ==========================================================
            // CASOS OLVIDADOS
            // MÁS DE 40 DÍAS
            // ==========================================================

            ->where(function ($query) use ($dias_limite) {

                // ------------------------------------------------------
                // CASO 1:
                // Tiene actuaciones, pero la última fue hace
                // MÁS DE 40 DÍAS
                // ------------------------------------------------------

                $query->whereRaw("

            (
                SELECT MAX(a.created_at)

                FROM actuacions a

                WHERE a.actexpid = expedientes.expid

                AND a.actusercreated =
                    expedientes.expidnumberest

            ) < DATE_SUB(
                NOW(),
                INTERVAL $dias_limite DAY
            )

        ")


                    // ------------------------------------------------------
                    // CASO 2:
                    // Nunca ha tenido actuaciones y lleva
                    // MÁS DE 40 DÍAS ASIGNADO
                    // ------------------------------------------------------

                    ->orWhere(function ($query) use ($dias_limite) {

                        $query->whereNotExists(function ($subquery) {

                            $subquery->select(DB::raw(1))

                                ->from('actuacions')

                                ->whereColumn(
                                    'actuacions.actexpid',
                                    'expedientes.expid'
                                )

                                ->whereColumn(
                                    'actuacions.actusercreated',
                                    'expedientes.expidnumberest'
                                );
                        })

                            ->whereRaw("

                asignacion_caso.fecha_asig <
                DATE_SUB(
                    NOW(),
                    INTERVAL $dias_limite DAY
                )

            ");
                    });
            })
            ->distinct()

            ->groupBy('expedientes.expid')

            ->orderBy('fecha_ultima_actuacion', 'asc')

            ;

        return $casos;
    }
}

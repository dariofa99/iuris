<?php

namespace App\Repositories;

use App\Actuacion;
use App\AsignacionCaso;
use App\Expediente;
use App\ExpedientePausas;
use App\Sede;
use App\Services\AsignacionDocenteCasosService;
use App\Services\ExpedientesService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use App\Services\UsersService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class ExpedientesRepository extends BaseRepository implements ExpedientesService
{

    private $periodoService;
    private $segmentoService;
    private $usersService;
    private $asignacionDocenteCasoService;
    private $request;
    public function __construct(
        Expediente $model,
        AsignacionDocenteCasosService $asignacionDocenteCasoService
    ) {
        parent::__construct($model);
        $this->periodoService = App::make(PeriodosService::class);
        $this->segmentoService = App::make(SegmentosService::class);
        $this->usersService = App::make(UsersService::class);
        $this->request = App::make(Request::class);
        $this->asignacionDocenteCasoService = $asignacionDocenteCasoService;
    }
    public function index(Request $request)
    {
        $this->query = $this->model;
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
                    $query->where('expedientes.expidnumberest', '=', currentUser()->idnumber)
                        ->where('asignacion_caso.asigest_id', '=', currentUser()->idnumber);
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->get('search_onlyProJur')) {
                    $query->where('asignacion_caso.procesojud_id', '<>', 1);
                }
            })
            ->Criterio($request)
            ->orderByRaw($order)
            ->orderBy(DB::raw("asignacion_caso.created_at"), 'desc')
            ->groupBy('asignacion_caso.asigexp_id')
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
            DB::raw("SELECT `docidnumber`, `name`,COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
                JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
                JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
                JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
                JOIN sede_usuarios ON sede_usuarios.user_id = users.id
                JOIN role_user ON role_user.user_id = users.id
                WHERE expedientes.exptipoproce_id = '1'
                AND users.active = 1
                AND users.idnumber <> 98378318
                AND  expedientes.expestado_id = '1'
                AND (users.active_asignacion = 1 or role_user.role_id = 4)
                AND sede_usuarios.sede_id =  '" . session('sede')->id_sede . "'
                GROUP BY `docidnumber` ORDER BY num_casos ASC")
        );
        //dd($asig_doc);
        $docentes = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where(function ($query) {
                $query->orwhere('role_id', '4')
                    ->orwhere('users.active_asignacion', true);
            })
            ->where('users.active', true)
            ->where('users.idnumber',"<>", 98378318)
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
        AND (users.active_asignacion=1 or role_user.role_id=4)
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
        $asig_doc = $this->getDocentesAsigByTypeProcessAndRama($tipoproce, $subRama);
        $docentes = $this->usersService->getDocentesByRama($subRama);
        $this->request['asig_caso_id']  = $asignacion_caso->id;
        if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
                $this->request['docidnumber']  = $asig_doc[0]->docidnumber;
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
                $this->request['docidnumber']  = $docente->idnumber;
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                break;
            }
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
}

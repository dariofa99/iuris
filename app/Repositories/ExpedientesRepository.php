<?php

namespace App\Repositories;

use App\Actuacion;
use App\AsignacionCaso;
use App\Expediente;
use App\Sede;
use App\Services\AsignacionDocenteCasosService;
use App\Services\ExpedientesService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

        //Event::dispatch('expediente.created', $this->model);

        return $this->model;
    }

    public function update(Expediente $expediente, Request $request): Expediente
    {
        $expediente->fill($request->all());
        $expediente->save();
        // Event::dispatch('expediente.updated', $expediente);
        return $expediente;
    }


    public function asignarDocente(AsignacionCaso $asignacion_caso)
    {
        $segmento = $this->segmentoService->getSegmentoActivo();
        $docente_unavi = $this->usersService->getDocentesByRama("UNAVI");




        $asig_doc = DB::select(
            DB::raw("SELECT `docidnumber`, `name`,COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
            JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
            JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
            JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
            JOIN periodo ON asignacion_caso.periodo_id = periodo.id
            JOIN segmentos ON periodo.id = segmentos.perid
            JOIN sede_usuarios ON sede_usuarios.user_id = users.id
            WHERE expedientes.exptipoproce_id = '1' AND users.active=1
            AND users.idnumber != '" . $docente_unavi[0]['idnumber'] . "'
            AND users.active_asignacion=1 AND segmentos.id = $segmento->segmento_id
            AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
            GROUP BY `docidnumber` ORDER BY num_casos ASC")
        );

        $docentes = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where('role_id', '4')
            ->where('users.active', true)
            ->where('users.idnumber', '<>', $docente_unavi[0]['idnumber'])
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
            )
            ->orderBy('users.created_at', 'desc')->get();

        $this->request['asig_caso_id']  = $asignacion_caso->id;
        if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
                $this->request['docidnumber']  = $docente_unavi[0]['idnumber'];
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
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

    private function getDocentesAsigByTypeProcess($tipoproce)
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
        AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
        AND users.active=1 AND users.active_asignacion=1
        AND segmentos.id = $segmento->segmento_id
        GROUP BY `docidnumber` ORDER BY num_casos ASC
         ",
            ),
        );
    }

    public function asignargDocenteSeguimiento($asignacion_caso, $tipoproce)
    {
        $asig_doc = $this->getDocentesAsigByTypeProcess($tipoproce);
        $subRama = $asignacion_caso->expediente->rama_derecho->subrama;
        $doceWithRama = $this->usersService->getDocentesByRama($subRama);
        $arraydocentescompleto = [];
        $casoasignado = 0;
        $this->request['asig_caso_id']  = $asignacion_caso->id;

        foreach ($doceWithRama as $key1 => $docenterama) {
            $docexiste = 0;
            foreach ($asig_doc as $key2 => $docentecasos) {
                if ($docenterama['idnumber'] == $docentecasos->docidnumber) {
                    $docexiste = 1;
                    $arraydocentescompleto[$docenterama['idnumber']] = $docentecasos->num_casos;
                }
            }

            if ($docexiste == 0) {
                $casoasignado = 1;
                $this->request['docidnumber']  = $docenterama['idnumber'];
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                $asignado = true;
                break;
            }
        }
        if ($casoasignado == 0) {
            asort($arraydocentescompleto);
            foreach ($arraydocentescompleto as $key => $numecasos) {
                $this->request['docidnumber']  = $key;
                $asignacion = $this->asignacionDocenteCasoService->store($this->request);
                $asignado = true;
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
            ->where(function ($query) use ($expediente, $onlyEst,$docente) {
                if ($onlyEst) {
                    $query->where('actidnumberest', '=', $expediente->expidnumberest)
                    ->orwhere(function($query) use($expediente,$docente){
                        $query->where('actidnumberest', '=', $expediente->expidnumberest)
                        ->where('actusercreated', '=', $docente->idnumber);
                    });
                    
                }else{
                    $query->where('actidnumberest', '<>', $expediente->expidnumberest);
                }
            })
            ->where([
                'actexpid' => $expediente->expid
            ])
            ->orderBy('created_at', 'desc')->get();
    }
}

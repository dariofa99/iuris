<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Expediente;
use App\User;
use App\Actuacion;
use App\AsignacionCaso;
use App\AsigDocenteCaso;
use App\Conciliacion;
use App\Events\LoginEvent;
use App\ExpedientePausas;
use App\Periodo;
use App\Segmento;
use App\Solicitud;
use App\HistorialDatosCaso;
use App\Jobs\ProcessEmailSendNotificarDirector;
use App\Jobs\ProcessEmailSendPJ;
use App\Nota;
use App\Notifications\NotificarDirector;
use App\Notifications\SolicitudDocenteCaso;
use App\Notifications\SolicitudEstudiantesProcesosJuricosExp;
use Facades\App\Facades\NewPush;
use App\Notifications\UserNotification;
use App\Services\AsignacionCasosService;
use App\Services\AsignacionDocenteCasosService;
use App\Services\EstadosCasoService;
use App\Services\ExpedientesService;
use App\Services\PausasService;
use App\Services\PeriodosService;
use App\Services\ProcesoJudicialExpService;
use App\Services\SegmentosService;
use App\Services\SolicitudesService;
use App\Services\UsersService;
use App\Services\VacacionesService;
use PDF;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ExpedienteController extends Controller
{
  private $estadoCasoService;
  private $userService;
  private $expedienteService;
  private $asignacionCasoService;
  private $solicitudService;
  private $asignacionDocenteCasoService;
  private $segmentosService;
  private $procjucicialService;
  private $periodosService;
  private $vacacionesService;
  private $pausaService;
  public function __construct(
    VacacionesService $vacacionesService,
    PeriodosService $periodosService,
    AsignacionCasosService $asignacionCasoService,
    SolicitudesService $solicitudService,
    UsersService $userService,
    ExpedientesService $expedienteService,
    AsignacionDocenteCasosService $asignacionDocenteCasoService,
    EstadosCasoService $estadoCasoService,
    SegmentosService $segmentosService,
    ProcesoJudicialExpService $procjucicialService,
    PausasService $pausaService
  ) {
    $this->pausaService = $pausaService;
    $this->vacacionesService = $vacacionesService;
    $this->periodosService = $periodosService;
    $this->asignacionCasoService = $asignacionCasoService;
    $this->solicitudService = $solicitudService;
    $this->userService = $userService;
    $this->estadoCasoService = $estadoCasoService;
    $this->expedienteService = $expedienteService;
    $this->asignacionDocenteCasoService = $asignacionDocenteCasoService;
    $this->segmentosService = $segmentosService;
    $this->procjucicialService = $procjucicialService;
    $this->middleware('permission:ver_expedientes',   ['only' => ['create']]);
    $this->middleware('permission:sustituir_casos',   ['only' => ['replacecaso']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (currentUser()->hasRole("solicitante")) return redirect("/oficina/solicitante");
    $expedientes = $this->expedienteService->index($request);
    // $expedientes = [];
    $count_colors = []; // $this->expedienteService->getColorsAsesorias($request);
    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
      //$expedientes = $this->expedienteService->index($request);   
      $res = [];
      $view = view('myforms.frm_expediente_list_ajax', compact('expedientes', 'count_colors'))->render();
      $res["view"] = $view;
      if (!$request->has('tipo_busqueda') || ($request->has('tipo_busqueda') and $request->input('tipo_busqueda') != 'color')) {
        //$view_count = view('myforms.components_exp.count_asesorias_inlist', compact('expedientes', 'count_colors'))->render();
        //$res["view_count"] = $view_count;
      }
      return response()->json($res);
    }
    $request = $request->all();
    return view('myforms.frm_expediente_list', compact('expedientes', 'count_colors'));
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $active_expe = 'active';

    // $users = $this->userService->verifyStatus(true)->getUsersByRoleName('estudiante');


    $id = $this->getId();

    // dd($users);
    return view('myforms.frm_expediente_create', compact('active_expe', 'id'));
  }





  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {


    //  return response()->json($request->all());
    $res_day = Carbon::now();
    $res_day = $res_day->addDays(7)->format('Y-m-d');
    $date = Carbon::now();
    $request['expfecha_res'] = $res_day;
    $expediente = $this->expedienteService->store($request);
    $request['asigest_id'] = $request['expidnumberest'];
    $request['asigexp_id'] = $expediente->expid;
    $asignacion_caso = $this->asignacionCasoService->store($request);
    // dd($expediente);
    if ($request['exptipoproce_id'] == 1) {
      //solo para consultas de asesoria   
      $this->expedienteService->asignarDocente($asignacion_caso);
    } else {

      $this->expedienteService->asignargDocenteSeguimiento($asignacion_caso, $expediente->exptipoproce_id); // si tiene en cuenta la rama del derecho
    }
    if ($request->has('solicitud_id')) {
      //si viene desde solicitudes
      $request['type_status_id'] = 162;
      $request['type_category_id'] = 172;
      $solicitud = $this->solicitudService->store($request);
      $expediente->solicitudes()->attach($request->solicitud_id);
      /*  NewPush::channel('solicitudes_send')->message([
        'solicitud_id' => $solicitud->id,
        //'render'=>$render,             
      ])->publish(); */
    } else {
      $user = $this->userService->findWithFilter(['idnumber' => $expediente->expidnumber]);
      $request['turno'] = 0;
      $request['idnumber'] = $user->idnumber;
      $request['number'] = time();
      $request['name'] = $user->name;
      $request['tel1'] = $user->tel1;
      $request['lastname'] = $user->lastname;
      $request['estrato_id'] = $user->estrato_id;
      $request['tipodoc_id'] = $user->tipodoc_id;
      $request['type_category_id'] = 166;
      $request['type_status_id'] = 162;
      $solicitud = $this->solicitudService->store($request);
      $expediente->solicitudes()->attach($solicitud->id);
    }
    $expedientes = $this->getExpEstu($request['expidnumberest']);
    $numEx = count($expedientes);
    $render = view('myforms.frm_expediente_list_ajax', compact('expedientes', 'numEx'))->render();
    $user = $expediente->estudiante;
    $user->notification = 'Nueva notificación de caso';
    $user->link_to = '/expedientes/' . $expediente->expid . '/edit';
    $user->mensaje = 'Se ha asignado un nuevo caso. Exp: ' . $expediente->expid;
    $user->notify(new UserNotification($user));
    $request['comentario'] = 'Abierto primera vez';
    $request['expidnumber'] = $expediente->expid;
    $request['ref_estado_id'] = $expediente->expestado_id;
    $request['ref_motivo_estado_id'] = 13;
    $estado_caso = $this->estadoCasoService->store($request);
    //Notificar dir
    if (in_array($expediente->expramaderecho_id, ramasDerechoNotificar())) {
      // Notification::send(User::where("email",env("NOTIFICATION_DIR_EMAIL"))->first(),new NotificarDirector($expediente,"Hola soledad"));
      ProcessEmailSendNotificarDirector::dispatch($expediente)
        ->onConnection('database')->onQueue('emails');
    }
    Session::flash('message-success', 'Creado con éxito...!');
    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
      return response()->json($expediente);
    }
    return Redirect::to('expedientes');
  }

  public function prueba(Request $request)
  {

    $conciliacion = Conciliacion::where('id', 1)->first();
    $pdf = PDF::loadView('pdf.conciliacion_form',[
      'conciliacion'=>$conciliacion
    ]);
    return $pdf->stream('invoice.pdf');
    

    return view('pdf.conciliacion_form', compact('fecha_1'));
    dd($fecha_1);  


    // dd($dias_sin_hechos,$fecha_1,$fecha_2);

    $asignacion_caso = AsignacionCaso::where('asigexp_id', '2024B-1675')->first();
    // $this->expedienteService->asignarDocente($asignacion_caso);
    if ($request['exptipoproce_id'] == 1) {
      //solo para consultas de asesoria   
      $this->expedienteService->asignarDocente($asignacion_caso);
    } else {

      $this->expedienteService->asignargDocenteSeguimiento($asignacion_caso, 2); // si tiene en cuenta la rama del derecho
    }
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    if (currentUser()->hasRole("solicitante")) return redirect("/oficina/solicitante");
    $url = '/expedientes/';
    $expediente = $this->expedienteService->findWithFilter([
      'expid' => $id
    ]);

    if (!$expediente) return view('errors.error', compact('url'));


    $estudiante = $expediente->estudiante;

    if (currentUser()->hasRole("estudiante")) {

      if (Auth::user()->id != $estudiante->id) {
        return view('errors.error', compact('url'));
      }
    } elseif (currentUser()->hasRole("solicitante")) {
      if (Auth::user()->idnumber != $expediente->expidnumber) {
        $url = '/expedientes/';
        return view('errors.error', compact('url'));
      }
    }
    /* if ($expediente->expestado_id == '2' and currentUser()->hasRole("docente")) {
      return redirect('/expedientes/' . $expediente->expid);
    } */
    //dd($expediente);
    $estudiantes = $this->userService->getUsersByRoleName('estudiante');
    return view(
      'myforms.frm_expediente_show',
      [
        'expediente' => $expediente,
        'estudiantes' => $estudiantes,
        'readonly' => true
      ]
    );
  }

  private function getId()
  {
    //Nuevo codigo para crear el id autoincrementable
    $year_act = Date('Y');
    $sem_act = Date('m');
    if ($sem_act <= '06') {
      $sem_act = "A";
    } else {
      $sem_act = "B";
    }
    $indice = 0;
    $expediente =  Expediente::where('exptipoproce_id', '<>', 3)
      ->orderBy('id', 'desc')->first();

    if ($expediente) {
      $indices = explode("-", $expediente->expid);
      $indices[0] = substr($indices[0], 0, -1);
      $year_exp = $indices[0];
      $indice = $indices[1];
      if ($year_act != $year_exp) {
        $indice = 0;
      }
    }
    $id = $year_act . $sem_act . '-' . ($indice + 1);
    return $id;
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
    if (currentUser()->hasRole("solicitante")) return redirect("/oficina/solicitante");
    $url = '/expedientes/';
    $expediente = $this->expedienteService->findWithFilter([
      'expid' => $id
    ]);
    //dd($expediente->asignacion);
    if (!$expediente) return view('errors.error', compact('url'));
    //if ($expediente->exptipoproce_id == '3')  return redirect()->route('oficio.edit', $id);
    $estudiante = $expediente->estudiante;
    $asignacion = $expediente->asignacion;
    if ($expediente->exptipoproce_id ==  1) {
      $days = $expediente->getDaysOrColorForClose('dias');
      if ($days <= 0 || $days === true) { 
        if ($expediente->expestado_id != 5 and $expediente->expestado_id != 2) {
          $notas =  $expediente->get_has_nota_final();
          if (count($notas) <= 0) {
            $segmento = $this->segmentosService->getSegmentoActivo();
            if ($segmento) {
              $data = [
                'ntaaplicacion' => 0,
                'ntaconocimiento' => 0,
                'ntaetica' => 0,
                'ntaconcepto' => 'Evaluado por el sistema - Tiempo 30 días agotado',
                'orgntsid' => '4',
                'segid' => $segmento->id,
                'perid' => $segmento->perid,
                'tpntid' => '1',
                'expidnumber' => $expediente->expid,
                'estidnumber' => $expediente->expidnumberest,
                'docidnumber' => Auth::user()->idnumber,
                'tbl_org_id' => $expediente->id,
              ];
              $expediente->asignarNotas($data);
              $expediente->expestado_id = 5;
              $expediente->save();
              $request['comentario'] = 'Evaluado por el sistema - Tiempo 30 días agotado';
              $request['expidnumber'] = $expediente->expid;
              $request['ref_estado_id'] = $expediente->expestado_id;
              $request['ref_motivo_estado_id'] = 12;
              $estado_caso = $this->estadoCasoService->store($request);
              Session::flash('message-danger', 'Atención! El sistema ha cerrado el caso');
            } else {
              Session::flash('message-danger', 'Atención! No hay un segmento activo');
            }
          }
        }
      }
    }

    $estudiantes = $this->userService->getUsersByRoleName('estudiante');
    $expediente->setNotActLimit();
    if ($expediente->isValidEvaPause()) {
      $request['expestado_id'] = 1;
      $expediente = $this->expedienteService->update($expediente, $request);
      $request['comentario'] = 'Fecha de pausa caducada';
      $request['expidnumber'] = $expediente->expid;
      $request['ref_estado_id'] = $expediente->expestado_id;
      $request['ref_motivo_estado_id'] = 11;
      $estado_caso = $this->estadoCasoService->store($request);
      $exp = ExpedientePausas::where("asig_caso_id", $asignacion->id)
        ->update([
          "estado_id" => 250
        ]);
    } elseif ($expediente->expestado_id == '6' and !$expediente->isValidEvaPause()) {
      //$asignaciones = $expediente->asignaciones;
      $pausa = ExpedientePausas::with("asignacion")
        ->whereHas("asignacion", function ($query) use ($expediente) {
          $query->where('asigexp_id', $expediente->expid);
        })->orderBy('expedientes_pausa.created_at', 'desc')
        ->first();
      //dd($pausa->asig_caso_id,$asignacion->id);
      if ($pausa and $pausa->asig_caso_id != $asignacion->id) {
        $fechaCierre = Carbon::parse("2024-08-29");
        if ($pausa->fecha_final < $fechaCierre) {

          $request['expestado_id'] = 1;
          $expediente = $this->expedienteService->update($expediente, $request);
          $request['comentario'] = 'Fecha de pausa caducada';
          $request['expidnumber'] = $expediente->expid;
          $request['ref_estado_id'] = $expediente->expestado_id;
          $request['ref_motivo_estado_id'] = 11;
          $estado_caso = $this->estadoCasoService->store($request);
          $estudiante->notification = 'Nueva notificación de caso';
          $estudiante->link_to = '/expedientes/' . $expediente->expid . "/edit";
          $estudiante->mensaje = 'Se ha abierto un caso que estaba pausado.';
          $estudiante->notify(new UserNotification($estudiante));
        } elseif ($pausa->fecha_final > $fechaCierre) {
          //dd($pausa);
          $periodo_act = $this->periodosService->getPeriodoActivo();
          $request['fecha_inicial'] = $periodo_act->prdfecha_inicio;
          $request['fecha_final'] = $pausa->fecha_final;
          $request['userestud_id'] = $estudiante->id;
          $request['asig_caso_id'] = $asignacion->id;
          $asignacion = $this->expedienteService->pausarExpediente($expediente, $request);
          $request['comentario'] = 'Renovación pausa inicio periodo';
          $request['expidnumber'] = $expediente->expid;
          $request['ref_estado_id'] = $expediente->expestado_id;
          $request['ref_motivo_estado_id'] = 11;
          $estado_caso = $this->estadoCasoService->store($request);
          $estudiante->notification = 'Nueva notificación de caso';
          $estudiante->link_to = '/expedientes/' . $expediente->expid . "/edit";
          $estudiante->mensaje = 'Se ha renovado la pausa de un caso de sustitución.';
          $estudiante->notify(new UserNotification($estudiante));
        }
      }
    }
    if ($expediente->exptipoproce_id == '3')  return redirect()->route('oficio.edit', $id);
    if (currentUser()->hasRole("estudiante")) {
      if (Auth::user()->id != $estudiante->id) {
        return view('errors.error', compact('url'));
      }
      if (
        $expediente->expestado_id != '1'
        and $expediente->expestado_id != '3'
        and $expediente->expestado_id != '6'
      ) {
        //	Session::flash('message-success', 'Actualizado con éxito...!');
        return redirect('/expedientes/' . $expediente->expid);
      }
    } elseif (currentUser()->hasRole("solicitante")) {
      if (Auth::user()->idnumber != $expediente->expidnumber) {
        $url = '/expedientes/';
        return view('errors.error', compact('url'));
      }
    }
    /*  if (!currentUser()->can("admin_expedientes")) {
      return redirect('/expedientes/' . $expediente->expid);
    } */
    $readonly = false;
    return view(
      'myforms.frm_expediente_edit',
      compact('estudiantes', 'expediente', 'asignacion', 'readonly')
    );
  }



  public function getUsers()
  {
    $users = DB::table('users')
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->where('role_id', '6')
      ->select(
        'users.id',
        'users.idnumber',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
        'role_user.role_id',
        'roles.display_name'
      )
      ->orderBy('users.created_at', 'desc')
      ->pluck('full_name', 'users.idnumber');

    return $users;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

    $expediente = $this->expedienteService->find($id);
    if ($request->has('exphechos') and $expediente->exphechos != $request['exphechos']) {
      $historial = HistorialDatosCaso::insert([
        "hisdc_datos_caso" => $request['exphechos'],
        "hisdc_tipo_datos_caso" => '141',
        "hisdc_expidnumber" => $request['expid'],
        "hisdc_ndias" => '1',
        "hisdc_estado" => '2',
        "hisdc_idnumberest_id" => $expediente->expidnumberest,
        "hisdc_authuser_id" => Auth::user()->idnumber,
        "created_at" => Carbon::now(),
        "updated_at" => Carbon::now()
      ]);
    }
    if ($request->has('exprtaest') and $expediente->exprtaest != $request['exprtaest']) {
      $historial = HistorialDatosCaso::insert([
        "hisdc_datos_caso" => $request['exprtaest'],
        "hisdc_tipo_datos_caso" => '142',
        "hisdc_expidnumber" => $request['expid'],
        "hisdc_ndias" => '1',
        "hisdc_estado" => '2',
        "hisdc_idnumberest_id" => $expediente->expidnumberest,
        "hisdc_authuser_id" => Auth::user()->idnumber,
        "created_at" => Carbon::now(),
        "updated_at" => Carbon::now()
      ]);
    }

    $asignacion_caso = $this->asignacionCasoService->findWithFilter([
      'asigest_id' => $expediente->estudiante->idnumber,
      'activo' => 1,
      'asigexp_id' => $expediente->expid,
    ]);


    if ($request->has("exptipoproce_id") and $request->exptipoproce_id != $expediente->exptipoproce_id) {
      if ($request->exptipoproce_id == 1) {
        if ($expediente->getDocenteAsig()->idnumber == 'Sin asignar') {
          if ($asignacion_caso != null) {
            $date = Carbon::now();
            $this->expedienteService->asignarDocente($asignacion_caso);
          }
        }
      } else if ($request->exptipoproce_id != 1) {
        if ($expediente->getDocenteAsig()->idnumber != 'Sin asignar') {
          $asignacion_caso->asig_docente()->delete();
          $this->expedienteService->asignargDocenteSeguimiento($asignacion_caso, $expediente->exptipoproce_id);     // si tiene en cuenta la rama del derecho     
        }
      }
      //return response()->json([$request->all(),$asignacion_caso]);
    }


    if (
      Auth::user()->hasRole('diradmin')
      || Auth::user()->hasRole('coordprac')
      || Auth::user()->hasRole('amatai')
    ) {
      if (
        $asignacion_caso != null
        and $request->has('expidnumberest')
      ) {
        if (
          $request->has('expidnumberest')
          and $request->expidnumberest != $request['oldexpidnumberest']
        ) {
          /*  DB::table('asignacion_caso')
            ->where([
              'activo' => 1,
              'asigexp_id' => $expediente->expid,
              'asigest_id' => $request['expidnumberest']
            ])
            ->update(['activo' => 0]); */
          $date = Carbon::now();
          $asignacion_caso->asigest_id = $request['expidnumberest'];
          $asignacion_caso->fecha_asig = $date->format('Y-m-d H:i:s');
          $asignacion_caso->save();
        }
      }
    }
    $expediente = $this->expedienteService->update($expediente, $request);

    if (!$request->ajax()) {
      Session::flash('message-success', 'Actualizado con éxito...!');
      if ($expediente->exptipoproce_id == 3) {
        return Redirect::to('/defensas/oficio/' . $expediente->expid . '/edit');
      }
      return redirect()->back();
      //return Redirect::to('expedientes/'.$expediente->expid.'/edit');
    }
    return response()->json($expediente);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }



  public function listarActuaciones(Request $request)
  {

    if (empty($request->get('tipo_busqueda'))) {

      $criterio = '';
      $fechaini = fechasSem('fechaIni');
      $fechafin = fechasSem('fechaFin');
      $numpaginate = '20';
    } else {

      $fechaini = fechasSem('fechaIni');
      $fechafin = fechasSem('fechaFin');
      $criterio = $request->data;
      //$fechaini=$request->get('fechaini');
      //$fechafin=$request->get('fechafin');
      $numpaginate = '20';
    }


    if (currentUser()->hasRole("docente")) {

      if (!empty($request->get('tipo_busqueda'))) {

        $expedientes = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->Criterio($request)
          ->orderBy(DB::raw("FIELD(expestado_id,'4','1','2','3')"))
          ->orderBy(DB::raw("expedientes.created_at"), 'desc')->paginate($numpaginate);

        $numEx = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->Criterio($request)->count();
      } else {




        $expedientes = DB::table('expedientes as e')
          ->join('users as s', 'e.expidnumberest', '=', 's.idnumber')
          ->join('users as c', 'e.expidnumber', '=', 'c.idnumber')
          ->join('actuacions as a', 'e.expid', '=', 'a.actexpid')
          ->leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->select(
            'expid',
            'e.expidnumberest',
            's.name as nombrest',
            's.lastname as apeest',
            'e.expidnumber',
            'c.name as nombresolicita',
            'c.lastname as apesolicita',
            'e.expestado_id',
            'e.exptipoproce_id',
            'a.actestado',
            'e.created_at as created_at',
            'e.expfecha',
            'e.updated_at',
            'c.tel1 as tel1solicita',
            'e.id'
          )
          ->where('actestado', '=', '1')

          ->distinct()
          ->orderBy('e.id')
          ->paginate($numpaginate);

        //dd($expedientes);

        $numEx = $expedientes->count();
      }
    } else {
      if (!empty($request->get('tipo_busqueda'))) {
        $expedientes = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->Criterio($request)
          ->orderBy(DB::raw("expedientes.created_at"), 'desc')->paginate($numpaginate);
        $numEx = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->Criterio($request)->count();
      } else {
        $expedientes = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->orderBy(DB::raw("expedientes.created_at"), 'desc')->paginate($numpaginate);
        $numEx = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
          ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
          ->where('sedes.id_sede', session('sede')->id_sede)
          ->orderBy('expedientes.created_at', 'desc')->count();
      }
    }
    $request = $request->all();

    //$numEx = count($expedientes);
    $active_expe = 'active';
    return view('myforms.frm_expediente_actuacion_list', compact('expedientes', 'active_expe', 'numEx', 'request'));
  }




  public function reasigcaso(Request $request)
  {
    $expediente = $this->expedienteService->findWithFilter([
      'expid' => $request->expid,
    ]);
    $periodo_act = $this->periodosService->getPeriodoActivo();

    if (!$periodo_act) return response()->json(['errors' => ["No hay un periodo activo"]]);
    $asig = $expediente->asignaciones()
      ->where('asigest_id', $expediente->estudiante->idnumber)
      ->where('activo', 1)->first();
    $asignar = false;
    try {
      $asig->asig_docente->activo = 0;
      $asig->asig_docente->save();
      $cambio_docidnumber = $asig->asig_docente->cambio_docidnumber;
      $asignar = true;
    } catch (\Throwable $th) {
      $asignar = false;
    }
    $expediente->expidnumberest = $request->new_user_id;
    $expediente->save();
    if ($request['anotacion'] == null or $request['anotacion'] == '') {
      $anotacion = 'reasignado';
    } else {
      $anotacion = $request['anotacion'];
    }

    DB::table('asignacion_caso')
      ->where([
        'activo' => 1,
        'asigexp_id' => $request->expid,
        //'asigest_id'=>$request->new_user_id
      ])
      ->update(['activo' => 0]);
    $request['anotacion'] = $anotacion;
    $request['asigest_id'] = $request['new_user_id'];
    $request['asigexp_id'] = $expediente->expid;
    $request['ref_mot_asig_id'] = $request['motivo_asig_id'];
    $request['ref_asig_id'] = 2;
    $request['periodo_id'] = $periodo_act->id;
    $asignacion_caso = $this->asignacionCasoService->store($request);
    if ($asignar) {
      $request['docidnumber'] = $asig->asig_docente->docidnumber;
      $request['asig_caso_id'] = $asignacion_caso->id;
      $request['cambio_docidnumber'] = $cambio_docidnumber;
      $asignacion = $this->asignacionDocenteCasoService->store($request);
    }
    return response()->json($asignacion_caso->asig_docente);
  }

  public function replacecaso(Request $request)
  {

    $user = $this->getUsers();


    return view('myforms.frm_expediente_replace', compact('user'));
  }

  public function cambiarProcesoJuridico(Request $request)
  {

    $expediente = $this->expedienteService->find($request->expid);
    $asignacion_caso = $this->asignacionCasoService->findWithFilter([
      'asigest_id' => $expediente->expidnumberest,
      'activo' => 1,
      'asigexp_id' => $expediente->expid,
    ]);
    $request['procesojud_id'] = 245;
    $request['asig_caso_id'] = $asignacion_caso->id;
    $asignacion_caso = $this->asignacionCasoService->update($asignacion_caso, $request);
    $request['estado_id'] = 245;
    $request['comentario'] = "Solicitado por docente";
    $procjudi = $this->procjucicialService->store($request);



    return response()->json([
      $expediente,
      $asignacion_caso,
      $procjudi
    ]);

    return view('myforms.frm_expediente_replace', compact('user'));
  }

  public function anteriorEstudiante(Request $request)
  {

    $data = [];
    $estudiantes = [];
    if (($request->ajax())) {
      $asignaciones_caso = DB::table('asignacion_caso')
        ->whereDate('created_at', '>=', $request->fech_desde)
        ->whereDate('created_at', '<=', $request->fech_hasta)
        ->select('asigexp_id')
        //->orderBy('created_at','asc')
        ->groupBy('asigexp_id')->get();
      foreach ($asignaciones_caso as $key => $asignacion) {

        $asignacion_caso = DB::table('asignacion_caso')
          ->join('ref_asignacion', 'ref_asignacion.id', '=', 'asignacion_caso.ref_asig_id')
          ->join('users', 'users.idnumber', '=', 'asignacion_caso.asigest_id')
          ->select('ref_asignacion.nombre_asig as tipo_asig', 'ref_asignacion.descripcion', 'users.name', 'users.lastname', 'users.idnumber', 'asignacion_caso.created_at as fecha_asig')
          ->where('asigexp_id', $asignacion->asigexp_id)
          ->orderBy('asignacion_caso.created_at', 'desc')
          ->get();
        $data[$asignacion->asigexp_id] = $asignacion_caso;
      }

      foreach ($data as $exp => $asignaciones) {

        foreach ($asignaciones as $key => $asignacion) {
          if ($key == 1) {
            $estudiantes[] = [
              'full_name' => $asignacion->name . ' ' . $asignacion->lastname,
              'idnumber' => $asignacion->idnumber,
              'fecha_asig' => $asignacion->fecha_asig,
              //'asignaciones_caso'=>$asignaciones_caso,
            ];
          }
        }
      }
    }
    return response()->json($estudiantes);
  }

  public function searchExpAsig(Request $request)
  {
    $asignaciones_caso = DB::table('asignacion_caso')
      ->join('expedientes', 'expedientes.expid', '=', 'asignacion_caso.asigexp_id')
      ->join('users', 'users.idnumber', '=', 'expedientes.expidnumberest')
      ->select('expedientes.id', 'asigexp_id', 'users.name', 'users.lastname', 'users.idnumber', 'asignacion_caso.created_at as fecha_asig')
      ->where('asignacion_caso.asigest_id', $request->idnumber)
      ->whereDate('asignacion_caso.created_at', '>=', $request->fech_desde)
      ->whereDate('asignacion_caso.created_at', '<=', $request->fech_hasta)
      ->orderBy('asignacion_caso.created_at', 'desc')
      ->get();
    foreach ($asignaciones_caso as $key => $asig) {
      $asignaciones_caso_num = DB::table('asignacion_caso')
        ->where('asigexp_id', $asig->asigexp_id)->count();
      $asig->numero =  $asignaciones_caso_num;
    }



    return response()->json($asignaciones_caso);
  }

  /*private function unique_multidim_array($array, $key) { 
      $temp_array = array(); 
      $i = 0; 
      $key_array = array();     
      foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
      } 
      return $temp_array; 
    }*/

  public function sustcasos(Request $request)
  {


    $periodo = Periodo::where('estado', true)
      ->join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
      ->where('sp.sede_id', session('sede')->id_sede)
      ->first();
    foreach ($request->numberestact_id as $key_1 => $id_act) {
      $expedientes = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
        ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
        ->where('sedes.id_sede', session('sede')->id_sede)
        ->where('expidnumberest', $id_act)->get();

      // dd($expedientes);
      if ($expedientes != null and $expedientes != '' and count($expedientes) > 0) {



        foreach ($expedientes as $key_2 => $expediente) {
          if ($expediente->expestado_id == 1 || $expediente->expestado_id == 3) {
            $asignacion_caso = new AsignacionCaso();
            $asignacion_caso->anotacion = 'Sustitución';
            $asignacion_caso->asigest_id = $request->numberestnew_id[$key_1];
            $asignacion_caso->asiguser_id = currentUser()->idnumber;
            $asignacion_caso->asigexp_id = $expediente->expid;
            $asignacion_caso->periodo_id = $periodo->id;
            $asignacion_caso->ref_asig_id = 3;
            $asignacion_caso->ref_mot_asig_id = 1;
            $asignacion_caso->save();
            $expe = DB::table('expedientes')
              ->leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
              ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
              ->where('sedes.id_sede', session('sede')->id_sede)
              ->where('expid', $expediente->expid)
              ->update(['expidnumberest' => $request->numberestnew_id[$key_1]]);
          }

          if (isset($asignacion_caso) and $expediente->getAsignacion() and $expediente->getAsignacion()->asig_docente !== null) {
            $old_asig = $expediente->getAsignacion()->asig_docente;
            $old_asig->activo = 0;
            $old_asig->save();

            $new_asig_doc =  new AsigDocenteCaso();
            $new_asig_doc->activo = 1;
            $new_asig_doc->docidnumber = $old_asig->docidnumber;
            $new_asig_doc->asig_caso_id =  $asignacion_caso->id;
            $new_asig_doc->user_created_id = Auth::user()->idnumber;
            $new_asig_doc->user_updated_id = Auth::user()->idnumber;
            $new_asig_doc->save();
          }
        }
        $user_asig = DB::table('asignacion_caso')->where([
          'asigest_id' => $id_act,
          'activo' => 1,
        ])->update([
          'activo' => 0,
        ]);
        if ($expedientes != null and $expedientes != '' and count($expedientes)) {
          $user = User::where('idnumber', $request->numberestnew_id[$key_1])->first();
          $user->notification = 'Nueva notificación de caso';
          $user->link_to = '/expedientes';
          $user->mensaje = 'Se han asignado nuevos casos por sustitución.';
          // $user->notify(new UserNotification($user)); 
        }
      }


      foreach ($request->numberestnew_id as $key_2 => $id_new) {
        if ($key_1 == $key_2) {

          $expedientes = Expediente::leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->where('expidnumberest', $id_act)->get();

          if ($expedientes != null and $expedientes != '' and count($expedientes)) {
            $user = User::where('idnumber', $id_new)->first();
            $user->notification = 'Nueva notificación de caso';
            $user->link_to = '/expedientes';
            $user->mensaje = 'Se ha asignado un nuevo caso por sustitución.';
            $user->notify(new UserNotification($user));
          }
        }
      }
    }
    Session::flash('message-success', 'Actualizado con éxito...!');
    return redirect('/expediente/replacecaso/');

    /* if ($request->ajax()) {
       $expedientes = DB::table('expedientes')
      ->where('expidnumberest',$request->numberestact_id)
      ->update(['expidnumberest' => $request->numberestnew_id]);
      return response()->json($request->all());
      }
    */
  }

  public function getEstudiantes()
  {

    $estudiantes = $this->userService->getEstudiantes();
    return response()->json($estudiantes);
  }

  public function casosreasig()
  {

    $expreasignados = AsignacionCaso::where('ref_asig_id', 2)->paginate(100);
    //dd($expreasignados);
    return view('myforms.frm_expediente_reasignados_list', compact('expreasignados'));
  }
  public function selectest($texcon)
  {
    $periodo = DB::table('periodo')
      ->join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
      ->where('sp.sede_id', session('sede')->id_sede)
      ->where('estado', '=', '1')
      ->first();
    if ($periodo == null) return response()->json(['error' => 'No existe un periodo activo!']);
    $estudiantes = array();
    $fecha_in = $periodo->prdfecha_inicio;
    $fecha_in = $fecha_in . ' 01:00:00';

    $consultex = "";
    $consultex2 = "";
    if ($texcon == 1) {
      $consultex = "simples";
      $consultex2 = "complejas";
    } elseif ($texcon == 2 or $texcon == 3) {
      $consultex = "complejas";
      $consultex2 = "simples";
    }

    $date = Carbon::now();
    $horahoy = $date->format('H');
    $fechahoy = $date->format('Y-m-d');
    $horaconsul = "";
    $fechaconsul = "";

    if ($horahoy == "08" or $horahoy == "09") {
      $horaconsul = "08:00:00";
    } elseif ($horahoy == "10" or $horahoy == "11") {
      $horaconsul = "10:00:00";
    } elseif ($horahoy == "14" or $horahoy == "15") {
      $horaconsul = "14:00:00";
    } elseif ($horahoy == "16" or $horahoy == "17") {
      $horaconsul = "16:00:00";
    }

    $fechaconsul = $fechahoy . " " . $horaconsul;

    if ($horaconsul == "" or $texcon == 3) {

      /*   $estudiantes= DB::table('users')
              //->join('expedientes', 'expedientes.expidnumberest', '=', 'asistencia.astid_estudent')
              ->leftJoin('expedientes', 'users.idnumber', '=', 'expedientes.expidnumberest'  )
              ->join('role_user', 'users.id', '=', 'role_user.user_id'  )
              ->select('users.idnumber AS astid_estudent','users.name', 'users.lastname' , DB::raw('SUM(IF(expedientes.exptipoproce_id = 1, 1, 0)) AS simples'),DB::raw('SUM(IF(expedientes.exptipoproce_id = 2, 1, 0)) as complejas'),DB::raw('SUM(IF(expedientes.exptipoproce_id = 1 AND expedientes.expestado_id = 3, 1, 0)) AS simples_cerradas'),DB::raw('SUM(IF(expedientes.exptipoproce_id = 2 AND expedientes.expestado_id = 3, 1, 0)) as complejas_cerradas'))
              ->where('users.active', '=', '1')
              ->where('role_user.role_id', '=', '6')
              ->where('expfecha', '>=', $fecha_in)
              ->groupBy('astid_estudent')
              ->orderBy($consultex)
              ->orderBy($consultex2)
              ->get(); */
    } else {


      $estudiantes_fil = DB::table('asistencia')
        ->leftJoin(
          'expedientes',
          'asistencia.astid_estudent',
          '=',
          'expedientes.expidnumberest'
        )
        //->join('users', 'users.idnumber', '=', 'asistencia.astid_estudent'  )
        ->select(
          'asistencia.astid_estudent',
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 1, 1, 0)) AS simples'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 2, 1, 0)) as complejas'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 1 AND expedientes.expestado_id = 2, 1, 0)) AS simples_cerradas'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 2 AND expedientes.expestado_id = 2, 1, 0)) as complejas_cerradas')
        )
        ->where('expfecha', '>=', $fecha_in)
        ->where('astfecha', '=', $fechaconsul)
        ->where('astid_lugar', '=', '130')
        ->Where(function ($query) {
          $query->orwhere('astid_tip_asist', '=', '121')
            ->orwhere('astid_tip_asist', '=', '122')
            ->orwhere('astid_tip_asist', '=', '125')
            ->orwhere('astid_tip_asist', '=', '127')
            ->orwhere('astid_tip_asist', '=', '128');
        })
        ->groupBy('astid_estudent')
        ->orderBy($consultex)
        ->orderBy($consultex2)
        ->get();


      $estudiantes_asis = DB::table('asistencia')
        ->join('users', 'users.idnumber', '=', 'asistencia.astid_estudent')
        ->select('asistencia.astid_estudent', 'users.name', 'users.lastname')
        ->where('astfecha', '=', $fechaconsul)
        ->where('astid_lugar', '=', '130')
        ->Where(function ($query) {
          $query->orwhere('astid_tip_asist', '=', '121')
            ->orwhere('astid_tip_asist', '=', '122')
            ->orwhere('astid_tip_asist', '=', '125')
            ->orwhere('astid_tip_asist', '=', '127')
            ->orwhere('astid_tip_asist', '=', '128');
        })
        ->groupBy('astid_estudent')
        ->orderBy('astid_estudent')
        ->get();


      $estudiantes_com = array();
      $estudiantes_exp = array();

      foreach ($estudiantes_asis as $key => $est_inv) {

        $estudiantes_com[$key] = [
          "astid_estudent" => $est_inv->astid_estudent,
          "name" => $est_inv->name,
          "lastname" => $est_inv->lastname,
          "complejas" => "0",
          "complejas_cerradas" => "0",
          "simples" => "0",
          "simples_cerradas" => "0",

        ];
      }

      foreach ($estudiantes_fil as $key_fil => $est_inv_fil) {
        foreach ($estudiantes_asis as $key => $est_inv) {
          if ($est_inv->astid_estudent == $est_inv_fil->astid_estudent) {


            unset($estudiantes_com[$key]);

            $estudiantes_exp[$key_fil] = [
              "astid_estudent" => $est_inv->astid_estudent,
              "name" => $est_inv->name,
              "lastname" => $est_inv->lastname,
              "complejas" => $est_inv_fil->complejas,
              "complejas_cerradas" => $est_inv_fil->complejas_cerradas,
              "simples" => $est_inv_fil->simples,
              "simples_cerradas" => $est_inv_fil->simples_cerradas,

            ];
            break;
          }
        }
      }
      $estudiantes = array_merge($estudiantes_com, $estudiantes_exp);
    }


    if (sizeof($estudiantes) <= 0) {

      $estudiantes = DB::table('users')
        //->join('expedientes', 'expedientes.expidnumberest', '=', 'asistencia.astid_estudent')
        ->leftJoin('expedientes', 'users.idnumber', '=', 'expedientes.expidnumberest')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('turnos', 'users.idnumber', '=', 'turnos.trnid_estudent')
        ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
        ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
        ->where('sedes.id_sede', session('sede')->id_sede)
        ->select(
          'users.idnumber AS astid_estudent',
          'users.name',
          'users.lastname',
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 1, 1, 0)) AS simples'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 2, 1, 0)) as complejas'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 1 AND expedientes.expestado_id = 3, 1, 0)) AS simples_cerradas'),
          DB::raw('SUM(IF(expedientes.exptipoproce_id = 2 AND expedientes.expestado_id = 3, 1, 0)) as complejas_cerradas')
        )
        ->where('users.active', '=', '1')
        ->where('role_user.role_id', '=', '6')
        //->where('expfecha', '>=', $fecha_in)
        ->groupBy('astid_estudent')
        ->orderBy($consultex)
        ->orderBy($consultex2)
        ->get();
    }

    return response()->json($estudiantes);
  }
  public function historialDatosCaso($exp, $tipo)
  {

    $historial = HistorialDatosCaso::where('hisdc_expidnumber', $exp)
      ->join('users', 'users.idnumber', '=', 'historial_datos_casos.hisdc_idnumberest_id')
      ->join('asignacion_caso', 'asignacion_caso.asigexp_id', '=', 'historial_datos_casos.hisdc_expidnumber')
      ->select('hisdc_idnumberest_id', 'name', 'lastname', 'hisdc_datos_caso', 'historial_datos_casos.created_at')
      ->where('hisdc_tipo_datos_caso', $tipo)
      ->orderBy('historial_datos_casos.id', 'DESC')
      ->get();

    $expediente = Expediente::where('expid', $exp)->first();
    return response()->json(
      [
        "historial" => $historial,
        "num_dias" => $expediente->getDaysForEvaHechos(),   
      ]
    );
  }

  private function getExpEstu($idnumber)
  {
    return $expedientes = Expediente::join('asignacion_caso', 'expedientes.expid', '=', 'asignacion_caso.asigexp_id')
      ->leftjoin('sede_expedientes', 'sede_expedientes.expediente_id', '=', 'expedientes.id')
      ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_expedientes.sede_id')
      ->where('sedes.id_sede', session('sede')->id_sede)
      ->where('asignacion_caso.asigest_id', '=', $idnumber)
      ->where('expidnumberest', '=', $idnumber)
      ->orderBy(DB::raw("FIELD(expestado_id,'3','1','4','2','5')"))
      ->orderBy(DB::raw("asignacion_caso.created_at"), 'desc')
      ->groupBy('asignacion_caso.asigexp_id')
      ->paginate(10);
  }
  public function shareStream($id)
  {
    $fecha_unix = strtotime("+1 hours");
    $user = User::where('idnumber', $id)->first();
    if ($user) {
      $image = url('/thumbnails/' . $user->image);
      $name = $user->name;
      $email = $user->email;
      $idjitsi = $user->idnumber;
    } else {
      $solicitud = Solicitud::where('idnumber', $id)->first();
      $image = url('/thumbnails/default.jpg');
      $name = $solicitud->name;
      $email = $solicitud->idnumber . "@default.com";
      $idjitsi = $solicitud->idnumber;
    }

    $tokenjitsi = array(
      'context' => array(
        'user'  => array(
          'avatar'  => $image,
          'name'  => $name,
          'email' => $email,
          'id'  => $idjitsi,
        ),
        //'group' => 'a123-123-456-789',

      ),
      'aud' => 'my_server1',
      'iss' => 'my_web_client',
      'sub' => 'meet.jitsi',
      'room'  => $id,
      'exp' => $fecha_unix
    );
    $jwt = JWT::encode($tokenjitsi, 'c6x@JKCixAr*4sPO@XjXlb1b^', 'HS256');
    /* NewPush::channel('stream' . $id)
      ->message(['sol_id' => $id . "?jwt=" . $jwt])->publish();
 */
  }

  public function createStream($id)
  {
    $fecha_unix = strtotime("+1 hours");

    $tokenjitsi = array(
      'context' => array(
        'user'  => array(
          'avatar'  => url('/thumbnails/' . Auth::user()->image),
          'name'  => Auth::user()->name,
          'email' => Auth::user()->email,
          'id'  => Auth::user()->idnumber,
        ),
        //'group' => 'a123-123-456-789',

      ),
      'aud' => 'my_server1',
      'iss' => 'my_web_client',
      'sub' => 'meet.jitsi',
      'room'  => $id,
      'exp' => $fecha_unix
    );
    $jwt = JWT::encode($tokenjitsi, 'c6x@JKCixAr*4sPO@XjXlb1b^', 'HS256');
    return response()->json(
      ['room' => $id, "jwt" => $jwt]
    );
  }

  public function asigConciliacion(Request $request)
  {

    $expediente = Expediente::find($request->expediente_id);

    if ($expediente) {
      $periodo = Periodo::where('estado', '1')
        ->first();

      $conciliacion = Conciliacion::create([
        //'fecha_radicado'=>date('Y-m-d'),
        'num_conciliacion' => "CCEAH-000-00-00",
        'categoria_id' => 173,
        'estado_id' => 174,
        'periodo_id' => $periodo->id,
        'user_id' => auth()->user()->id
      ]);
      $actuacion = new Actuacion();
      $actuacion->actexpid = $expediente->expid;
      $actuacion->actnombre = "Creada por conciliación";
      $actuacion->actdescrip = "Creada por conciliación el " . getSmallDate(date("Y-m-d"));
      $actuacion->actestado_id = 174;
      $actuacion->actcategoria_id = 223;
      $actuacion->actfecha = date("Y-m-d");
      $actuacion->actidnumberest = $expediente->expidnumberest;
      $actuacion->actusercreated = currentUser()->idnumber;
      $actuacion->actuserupdated = currentUser()->idnumber;
      $actuacion->save();

      $actuacion->revisionesExp()->attach($actuacion->id, [
        'rev_actexpid' => $expediente->expid,
        'parent_rev_actid' => $actuacion->id,
        //'rev_actid'=>$actuacion->id,
      ]);


      $conciliacion->usuarios()->attach(auth()->user()->id, [
        'tipo_usuario_id' => 199,
        'estado_id' => 1
      ]);
      /*  $conciliacion->usuarios()->attach(auth()->user()->id,[
        'tipo_usuario_id'=>196
      ]); */

      $conciliacion->expedientes()->attach($expediente->id, [
        'type_status_id' => 1,
        'user_id' => auth()->user()->id,
        'actuacion_id' => $actuacion->id
      ]);
    }
    $response = [
      "expediente" => $expediente,
      "conciliacion" => $conciliacion
    ];


    return response()->json($response);
  }

  public function pruebaasig(Request $request, $id)
  {

    $expediente = Expediente::where("expid", "2024A-399")->first();
    //$user = User::where("email",env("NOTIFICATION_DIR_EMAIL"))->first();
    //Notification::send($user, new NotificarDirector($expediente,"Prueba"));
    /*  $this->expedienteService->findWithFilter([
      'expid'=>'2024A-176'
    ]);
    
     */
    /*   ProcessEmailSendNotificarDirector::dispatch($expediente)
      ->onConnection('database')->onQueue('emails'); */
    $asignacion_caso =  $expediente->asignacion;
    $segmento = $this->segmentosService->getSegmentoAsignacion($asignacion_caso);
    dd($segmento);
    /* if (in_array($expediente->expramaderecho_id, ramasDerechoNotificar())) {
      // Notification::send($user_,new NotificarDirector($expediente));
      ProcessEmailSendNotificarDirector::dispatch($expediente)
        ->onConnection('database')->onQueue('emails');
        dd(ramasDerechoNotificar(),User::where("email",env('NOTIFICATION_DIR_EMAIL'))->first());
    } */
    //$expediente = $this->expedienteService->asignargDocenteSeguimiento($asignacion_caso, 2); // si tiene en cuenta la rama del derecho


    //  $validate = $expediente->verifyActuacionAnexoForCreate();

    //dd($expediente);

    if ($expediente->exptipoproce_id == 1) {
      //solo para consultas de asesoria   
      $this->expedienteService->asignarDocente($asignacion_caso);
    } else {

      $this->expedienteService->asignargDocenteSeguimiento($asignacion_caso, $expediente->exptipoproce_id); // si tiene en cuenta la rama del derecho
    }

    dd("");
    //$relations = $asignacion_caso->getRelations();

    $fecha_unix = strtotime("+1 hours");
    $tokenjitsi = array(
      'context' => array(
        'user'  => array(
          'avatar'  => 'https://robohash.org/john-doe',
          'name'  => 'John Doe',
          'email' => 'jdoe@example.com',
          'id'  => 'abcd:a1b2c3-d4e5f6-0abc1-23de-abcdef01fedcba',
        ),
        'group' => 'a123-123-456-789',

      ),
      'aud' => 'my_server1',
      'iss' => 'my_web_client',
      'sub' => 'meet.jitsi',
      'room'  => '*',
      'exp' => $fecha_unix
    );
    $jwt = JWT::encode($tokenjitsi, 'c6x@JKCixAr*4sPO@XjXlb1b^', 'HS256');

    echo $jwt;
  }

  public function storeProcJudicial(Request $request)
  {
    // return response()->json($request->all());
    $expediente = $this->expedienteService->find($request->expid);
    $asignacion_caso = $expediente->asignacion;;
    $estado_caso_old = $asignacion_caso->procesojud_id;
    $request['procesojud_id'] = $request->estado_id;
    $asignacion_caso = $this->asignacionCasoService->update($asignacion_caso, $request);
    $request['estado_id'] = $asignacion_caso->procesojud_id;
    $request['asig_caso_id'] = $asignacion_caso->id;
    $procjudi = $this->procjucicialService->store($request);
    if ($request->has('fileprocjud') and $request->fileprocjud != '') {
      $procjudi = $this->procjucicialService->saveFile($procjudi, $request);
    }
    $expediente->setRelations([]);
    ProcessEmailSendPJ::dispatch(
      $expediente,
      $request->estado_id,
      Auth::user()->name . " " . Auth::user()->lastname
    )->onConnection('database')->onQueue('emails');
    return response()->json($expediente);
  }

  public function editExpProcJudicial(Request $request, $id)
  {
    $procjudi = $this->procjucicialService->find($id);
    $view = view("myforms.components_exp.frm_detalles_exprocjudicial", compact('procjudi'))->render();
    return response()->json([
      'view' => $view,
      'procjudi' => $procjudi
    ]);
  }

  public function cambiarDocente(Request $request)
  {
    $expediente = $this->expedienteService->find($request->expid);
    $asig = $expediente->asignacion;
    if ($asig == null) return response()->json(['errors' => [
      'No hay una asignacion activa'
    ]]);
    $asig_doc =  $this->asignacionDocenteCasoService->findWithFilter([
      'asig_caso_id' => $asig->id,
      'activo' => 1
    ]);
    if ($request->tipo_cambio == 0) {
      $request['cambio_docidnumber'] = $request->new_docente_id;
      $notify = $this->userService->findWithFilter([
        'idnumber' => $request->new_docente_id
      ]);
      $notify->expid = $expediente->expid;
      $notify->notify(new SolicitudDocenteCaso($notify));
      $asig_doc =  $this->asignacionDocenteCasoService->update($asig_doc, $request);
    } elseif ($request->tipo_cambio == 1) {
      $request['docidnumber'] = $request->new_docente_id;
      $request['cambio_docidnumber'] = null;
      $asig_doc =  $this->asignacionDocenteCasoService->update($asig_doc, $request);
    } elseif ($request->tipo_cambio == 2) {
      $request['cambio_docidnumber'] = null;
      $asig_doc =  $this->asignacionDocenteCasoService->update($asig_doc, $request);
    } elseif ($request->tipo_cambio == 3) {
      $asig_doc->activo = 0;
      $request['activo'] = 1;
      $request['docidnumber'] = auth()->user()->idnumber;
      $request['asig_caso_id'] = $asig_doc->asig_caso_id;
      $asignacion = $this->asignacionDocenteCasoService->store($request);
      $asig_doc->user_updated_id = Auth::user()->idnumber;
      $asig_doc->save();
    } elseif ($request->tipo_cambio == 4) {
      $request['docidnumber'] = $request->new_docente_id;
      $request['asig_caso_id'] = $asig->id;
      $asignacion = $this->asignacionDocenteCasoService->store($request);
      return response()->json(['agregado' => 1]);
    } elseif ($request->tipo_cambio == 5) {
      $del = $expediente->getAsignacion()->asig_docente->delete();
      return response()->json(['eliminado' => 1]);
    }

    return response()->json($asig_doc);
  }

  public function darBaja(Request $request)
  {

    $estudiantes = $this->userService->getUsersByRoleName("estudiante_pruebas");
    $docentes = $this->userService->getUsersByRoleName("docente_prueba");

    if (count($estudiantes) <= 0) {
      return response([
        "errors" => ["No hay un estudiante de pruebas activo"],
      ]);
    }
    if (count($docentes) <= 0) {
      return response([
        "errors" => ['No hay un docente de pruebas activo']
      ]);
    }

    if (count($docentes) > 0 and count($estudiantes) > 0) {

      $expediente = $this->expedienteService->findWithFilter([
        'expid' => $request->exp_id
      ]);
      $asig = $expediente->asignacion;
      $asig->activo = 0;
      $asig->save();

      $estudiante = $estudiantes[0];
      $request['expestado_id'] = 8;
      $request["expidnumberest"] = $estudiante["idnumber"];
      $expediente = $this->expedienteService->update($expediente, $request);

      $periodo_act = $this->periodosService->getPeriodoActivo();
      $request['anotacion'] = "Asignado al dar de baja";
      $request['asigest_id'] = $estudiante['idnumber'];
      $request['periodo_id'] = $periodo_act->id;
      $request['asigexp_id'] = $expediente->expid;
      $request["ref_asig_id"] = 2;
      $request["ref_mot_asig_id"] = 8;
      $asignacion = $this->asignacionCasoService->store($request);

      $docente = $docentes[0];

      $request['docidnumber'] = $docente['idnumber'];
      $request['asig_caso_id'] = $asignacion->id;

      $this->asignacionDocenteCasoService->store($request);

      $old_asig = AsigDocenteCaso::where([
        "asig_caso_id" => $asig->id,
        "activo" => 1
      ])->first();
      if ($old_asig) {
        $old_asig->activo = 0;
        $old_asig->save();
      }
      $request['expidnumber'] = $expediente->expid;
      $request['ref_estado_id'] = $expediente->expestado_id;
      $request['ref_motivo_estado_id'] = 11;
      $this->estadoCasoService->store($request);
      return response([
        "error" => false,
        "message" => "El caso se dió de baja con éxito y fue asignado al docente de prueba " . $docente['full_name']
      ]);
    }
    return response([
      "error" => true,
      "message" => "No hay un docente de pruebas activo"
    ]);
  }

  public function pausarExpediente(Request $request)
  {
    $expediente = $this->expedienteService->find($request->expediente_id);
    if (($expediente->exptipoproce_id == 3
        and $expediente->exphechos == "")
      || ($expediente->exptipoproce_id != 3 and
        $expediente->exprtaest == "" || $expediente->exphechos == "")
    ) {
      return response()->json(["errors" => ['Los hechos y respuesta del estudiante no pueden estar vacios']]);
    }
    $request['expestado_id'] = 6;
    $expediente = $this->expedienteService->update($expediente, $request);
    $asignacion = $this->expedienteService->pausarExpediente($expediente, $request);
    $request['comentario'] = 'Pausado primera vez';
    $request['expidnumber'] = $expediente->expid;
    $request['ref_estado_id'] = $expediente->expestado_id;
    $request['ref_motivo_estado_id'] = 11;
    $estado_caso = $this->estadoCasoService->store($request);
    return response()->json($asignacion);
  }

  public function getPausasExpediente(Request $request)
  {
    $expediente = $this->expedienteService->find($request->expediente_id);
    $pausas = $expediente->asignacion->pausas;
    $pausas->each(function ($pau) {
      $pau->fecha_initxt = getSmallDate($pau->fecha_inicial);
      $pau->fecha_fintxt = getSmallDate($pau->fecha_final);
    });
    return response()->json($pausas);
  }
  public function deletePausaExpediente(Request $request, $id)
  {
    $expediente = $this->expedienteService->find($request->expediente_id);
    $request['expestado_id'] = 1;
    $expediente = $this->expedienteService->update($expediente, $request);
    $request['comentario'] = 'Cancelación de pausa';
    $request['expidnumber'] = $expediente->expid;
    $request['ref_estado_id'] = $expediente->expestado_id;
    $request['ref_motivo_estado_id'] = 11;
    $estado_caso = $this->estadoCasoService->store($request);
    $pausa = $this->expedienteService->deletePausa($id);
    return response()->json($pausa);
  }
  public function updatePausaExpediente(Request $request, $id)
  {
    // return response()->json($request->all());
    $expediente = $this->expedienteService->find($request->expediente_id);
    if ($expediente and $request->has('finapausa') and $request->input('finapausa') == 1) {
      $request['expestado_id'] = 1;
      $expediente = $this->expedienteService->update($expediente, $request);
      $request['comentario'] = 'Terminación de pausa';
      $request['expidnumber'] = $expediente->expid;
      $request['ref_estado_id'] = $expediente->expestado_id;
      $request['ref_motivo_estado_id'] = 11;
      $estado_caso = $this->estadoCasoService->store($request);
      $request['fecha_final'] = Carbon::now();
    }

    $pausa = $this->expedienteService->updatePausa($id, $request);
    return response()->json($pausa);
  }

  public function addUser(Request $request)
  {
    $expediente = $this->expedienteService->find($request->exp_id);
    if ($request->has("id") and $request->input("id") == '') {
      $user = $this->userService->store($request);
    } else {
      $user = $this->userService->find($request->input("id"));
      $user = $this->userService->update($user, $request);
    }
    $expediente->usuarios()->syncWithoutDetaching([
      $user->id => [
        "tipo_usuario_id" => 1,
        "estado_id" => 2
      ]
    ]);
    return response()->json($request->all());
  }
}

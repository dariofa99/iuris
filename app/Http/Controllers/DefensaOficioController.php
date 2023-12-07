<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Expediente;
use App\User;
use App\AsignacionCaso;
use App\Jobs\ProcessEmailSendNotificarDirector;
use App\Notifications\UserNotification;
use App\Services\AsignacionCasosService;
use App\Services\EstadosCasoService;
use App\Services\ExpedientesService;
use App\Services\UsersService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DefensaOficioController extends Controller
{
  private $userService;
  private $expedientesService;
  private $asignacionCasosService;
  private $estadoCasoService;
  public function __construct(
    UsersService $userService,
    ExpedientesService $expedientesService,
    AsignacionCasosService $asignacionCasosService,
    EstadosCasoService $estadoCasoService
  ) {
    $this->estadoCasoService = $estadoCasoService;
    $this->userService = $userService;
    $this->expedientesService = $expedientesService;
    $this->asignacionCasosService = $asignacionCasosService;
    //$this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
    $this->middleware('permission:crear_defensas_oficio',   ['only' => ['create']]);
  }
  /**
   * Display a listing of the resource. 
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {


    array_map('unlink', glob(public_path('act_temp/' . currentUser()->id . '___*'))); //elimina los archivos que el usuario a visualizado anteriormente.(provisional)
    if (empty($request->get('tipo_busqueda'))) {
      $criterio = '';
      $fechaini = fechasSem('fechaIni');
      $fechafin = fechasSem('fechaFin');
      $numpaginate = '20';
    } else {
      $fechaini = fechasSem('fechaIni');
      $fechafin = fechasSem('fechaFin');
      $criterio = $request->data;
      $numpaginate = '100';
    }


    if (currentUser()->hasRole("estudiante")) {
      if (!empty($request->get('tipo_busqueda'))) {
        if ((is_null($request->dataIni))) {
          //Si no es rango de fechas
          $expedientes = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->Criterio($request->data, $request->tipo_busqueda)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->paginate(10);
          $numEx = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->Criterio($request->data, $request->tipo_busqueda)->count();
        } else {
          $expedientes = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->RangoFechas($request->dataIni, $request->dataFin)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->paginate(10);
          $numEx = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->RangoFechas($request->dataIni, $request->dataFin)->count();
        }
      } else {
        //Por defecto.. estudiante 
        $expedientes = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->orderBy(DB::raw("created_at"), 'desc')->paginate(10);
        $numEx = Expediente::where('expidnumberest', '=', currentUser()->idnumber)->count();
      }

      //$numEx = count($expedientes);
    } elseif (currentUser()->hasRole("docente")) {
      //$expedientes= Expediente::Criterio($criterio)->orderBy('created_at', 'desc')->paginate($numpaginate);

      if (!empty($request->get('tipo_busqueda'))) {
        /*$expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("FIELD(expestado,'4','1','2','3')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate); 
*/
        if (is_null($request->dataIni)) {
          //si la consulta no es rango
          $expedientes = Expediente::Criterio($request->data, $request->tipo_busqueda)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->paginate($numpaginate);

          $numEx = Expediente::Criterio($request->data, $request->tipo_busqueda)->count();
        } else {
          //rango
          $expedientes = Expediente::RangoFechas($request->dataIni, $request->dataFin)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->paginate($numpaginate);

          $numEx = Expediente::RangoFechas($request->dataIni, $request->dataFin)->count();
        }
      } else {
        //Por defecto docente

        $expedientes = Expediente::orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
        //es para colocar de primero los que se registran dia a dia
        //$date = Carbon::now();
        //$fechaactual=$date->toDateString();
        //$exped_abiertos_ac=Expediente::where('expfecha', '=', $fechaactual)->where('expestado', '=', '1');

        $numEx = Expediente::orderBy('created_at', 'desc')->count();
      }


      //$numEx = count($expedientes);     
      //$expediente= Expediente::orderBy(DB::raw("FIELD(expestado,'2','1','4','3')"))->orderBy(DB::raw("created_at"), 'desc')->first();



    } else {


      if (!empty($request->get('tipo_busqueda'))) {

        if (is_null($request->dataIni)) {
          $expedientes = Expediente::Criterio($request->data, $request->tipo_busqueda)->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
          $numEx = Expediente::Criterio($request->data, $request->tipo_busqueda)->count();
        } else {
          $expedientes = Expediente::RangoFechas($request->dataIni, $request->dataFin)->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
          $numEx = Expediente::RangoFechas($request->dataIni, $request->dataFin)->count();
        }
      } else {

        $expedientes = Expediente::orderBy(DB::raw("created_at"), 'desc')->where('exptipoproce', 3)->paginate($numpaginate);
        $numEx = Expediente::orderBy('created_at', 'desc')->count();
      }
    }
    $request = $request->all();
    // $expedientes = Expediente::orderBy('expestado','ASC')->get()->toArray();
    //  sort($expedientes);

    $userSel = $this->getEstudiantes();
    $solicitantesSel = $this->getSolicitantes();
    //$numEx = count($expedientes);
    $active_expe = 'active';
    return view('myforms.frm_defensa_oficio_list', compact('expedientes', 'active_expe', 'numEx', 'request', 'userSel', 'solicitantesSel'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

    $active_expe = 'active';
    $estudiantes = $this->userService->getUsersByRoleName('estudiante');

    // $user = User::with('role')->where('role.id',6)->get();

    //   dd($users);

    return view('myforms.frm_defensa_oficio_create', ['active_expe' => $active_expe, 'estudiantes' => $estudiantes]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $ced = time();
    $messages = [
      'expid.unique' => 'El número de Expediente ya existe.',
    ];

    $validator = Validator::make($request->all(), [
      'expid' => 'required|unique:expedientes',
    ], $messages);
    if ($validator->fails()) {

      if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
        return response()->json(['errors' => $validator->errors()->all()]);
      }

      return redirect()->back()
        ->withErrors($messages)
        ->withInput();
    }
    $request['idnumber'] = '00' . $ced;
    $request['name'] = 'UserName';
    $request['lastname'] = 'UserLastName';
    $request['email'] = $ced . 'user@correo.com';
    $user = $this->userService->store($request);
    $request['exptipoproce_id'] = 3;
    $request['expidnumber'] = $user->idnumber;
    $expediente = $this->expedientesService->store($request);
    $request['asigest_id'] =  $expediente->expidnumberest;
    $request['asigexp_id'] =  $expediente->expid;
    $request['periodo_id'] =  $request['periodo_id'];
    $asignacion_caso = $this->asignacionCasosService->store($request);
    $request['comentario'] = 'Abierto primera vez';
    $request['expidnumber'] = $expediente->expid;
    $request['ref_estado_id'] = $expediente->expestado_id;
    $request['ref_motivo_estado_id'] = 13;
    $estado_caso = $this->estadoCasoService->store($request);
    $user = $expediente->estudiante;
    $user->notification = 'Nueva notificación de caso';
    $user->link_to = '/defensas/oficio/' . $expediente->expid . '/edit';
    $user->mensaje = 'Se ha asignado una defensa de oficio. Número: ' . $expediente->expid;
    $user->notify(new UserNotification($user));
    if (
      $expediente->expramaderecho_id == 37
      or $expediente->expramaderecho_id == 39
      or $expediente->expramaderecho_id == 40
      or $expediente->expramaderecho_id == 41
    ) {
      ProcessEmailSendNotificarDirector::dispatch($expediente)
        ->onConnection('database')->onQueue('emails');
    }
    /* $notifications = view('layouts.notifications',compact('user'))->render();
        NewPush::channel('notifications_'.$request['expidnumberest']) 
        ->message(['render'=>$render,'notifications'=>$notifications])->publish();  */
    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
      return response()->json($expediente);
    }
    return redirect('/expedientes');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $readonly = true;
    $expediente = $this->expedientesService->findWithFilter([
      'expid' => $id
    ]);
    $estudiantes = $this->userService->getUsersByRoleName('estudiante');

    if (currentUser()->hasRole('estudiante')) {
      if ($expediente->expidnumberest != Auth::user()->idnumber) {
        $url = '/expedientes/';
        return view('errors.error', compact('url'));
      }
    }
    return view('myforms.frm_defensa_oficio_show', compact('expediente', 'readonly', 'estudiantes'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {

    $expediente = $this->expedientesService->findWithFilter([
      'expid' => $id
    ]);
    $estudiantes = $this->userService->getUsersByRoleName('estudiante');


    $readonly = false;
    if (currentUser()->hasRole("estudiante")) {
      if (Auth::user()->id != $expediente->estudiante->id) {
        $url = '/expedientes/';
        return view('errors.error', compact('url'));
      }
    }

    if (($expediente->expestado_id == '4'
        || $expediente->expestado_id == '2'
        || $expediente->expestado_id == '5')
      and (!currentUser()->can("admin_expedientes"))
    ) {

      return redirect('/defensas/oficio/' . $expediente->expid);
    }
    return view('myforms.frm_defensa_oficio_edit', compact('estudiantes', 'expediente', 'readonly'));
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
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
  }


  public function getEstudiantes()
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
      )->orderBy('users.created_at', 'desc')->pluck('full_name', 'users.idnumber');
    return $users;
  }

  public function getSolicitantes()
  {
    $users = DB::table('users')
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->where('role_id', '8')

      ->select(
        'users.id',
        'users.idnumber',

        DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
        'role_user.role_id',
        'roles.display_name'
      )->orderBy('users.created_at', 'desc')->pluck('full_name', 'users.idnumber');
    return $users;
  }

  private function getExpEstu($idnumber)
  {
    return $expedientes = Expediente::join('asignacion_caso', 'expedientes.expid', '=', 'asignacion_caso.asigexp_id')
      ->where('asignacion_caso.asigest_id', '=', $idnumber)
      ->where('expidnumberest', '=', $idnumber)
      ->orderBy(DB::raw("FIELD(expestado_id,'3','1','4','2','5')"))
      ->orderBy(DB::raw("asignacion_caso.created_at"), 'desc')
      ->groupBy('asignacion_caso.asigexp_id')
      ->paginate(10);
  }
}

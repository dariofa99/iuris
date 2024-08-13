<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auditoria;
use App\ConcEncSatifAditionalData;
use App\ConcEncuestaSatisf;
use App\Conciliacion;
use App\Expediente;
use App\Mail\RegConcEncuestaSatSuccess;
use App\ReferencesData;
use App\Services\ConcEncuSatisfaccionService;
use App\Services\ConciliacionesService;
use App\Services\LoginService;
use App\Services\ReferenciasService;
use App\Services\UsersService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ConcEncuSatisfaccionController extends Controller
{
  private $userService;
  private $encuestaService;
  private $referenciasService;
  private $loginService;
  private $conciliacionesService;

  public function __construct(
    ConciliacionesService $conciliacionesService,
    LoginService $loginService,
    ReferenciasService $referenciasService,
    UsersService $userService,
    ConcEncuSatisfaccionService $encuestaService
  ) {
    $this->encuestaService = $encuestaService;
    $this->referenciasService = $referenciasService;
    $this->userService = $userService;
    $this->loginService = $loginService;
    $this->conciliacionesService = $conciliacionesService;
    $this->middleware('auth', ['except' => ['index', 'findUser']]); //not work
  }

  public function renderForm(Request $request)
  {
    $encuesta = ConcEncuestaSatisf::where('token', $request->get('token'))->first();
    if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
      $paginate = true;
      $data = getReferencesDataBySection(
        'personalizado',
        'conc_encuesta_satisf'
      );
      $view = view(
        'myforms.categorias.refs_aditional_data',
        compact('data', 'encuesta')
      )->render();
      return response()->json($view);
    }
    return view('myforms.encuestas.conciliaciones.formulario', compact('encuesta'));
  }

  public function index()
  {
    $tipodoc = $this->referenciasService->getReferenciasByFilter(
      [
        'tabla_ref' => 'users',
        'categoria' => 'tipo_doc'
      ]
    );

    return view('myforms.encuestas.conciliaciones.index', compact('tipodoc'));
  }
  public function findUser(Request $request)
  {
    $response = [];
    $encontrado = false;
    $sin_sede = false;
    try {
      $user = $this->userService->findWithFilter([
        'tipodoc_id' => $request->tipodoc_id,
        'idnumber' => $request->idnumber
      ]);
    } catch (\Throwable $th) {
      $user = false;
    }

    if ($user) {
      $encontrado = true;
    } else {
      try {
        $sin_sede = true;
        $response['sin_sede'] = true;
        $user = $this->userService->setValidateSede(false)->findWithFilter([
          'tipodoc_id' => $request->tipodoc_id,
          'idnumber' => $request->idnumber
        ]);
      } catch (\Throwable $th) {
        $user = false;
      }
      if ($user) {
        $encontrado = true;
      }
    }

    Auth::login($user);
    $login = $this->loginService->login($request);
    $response['user'] = $user;
    return response()->json($response);
  }

  public function store(Request $request)
  {
    // return response()->json($request->all());
    $request['user_id'] = auth()->user()->id;
    $request['tipo_usuario_id'] = 1;
    $encuesta = $this->encuestaService->store($request);
    // 

    return response()->json($encuesta);
  }

  public function update(Request $request)
  {
    $encuesta = $this->encuestaService->find($request->encuesta_id);
    $encuesta = $this->encuestaService->update($request, $encuesta);
    Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
  
    return response()->json($encuesta);
  }

  public function buscarConciliaciones(Request $request)
  {
    $user = currentUser();
    $user->conciliaciones;
    return view('myforms.encuestas.conciliaciones.conciliaciones', compact('user'));
  }

  public function getDataForChart(Request $request)
  {
    $resultados = DB::table('references_data')
    ->join('references_data_options', 'references_data.id', '=', 'references_data_options.references_data_id')
    ->leftJoin('concencsat_aditional_data', function ($join) {
        $join->on('references_data.id', '=', 'concencsat_aditional_data.reference_data_id')
             ->on('references_data_options.id', '=', 'concencsat_aditional_data.reference_data_option_id');
    })
    ->select(
        'references_data.id as pregunta_id',
        'references_data.name as pregunta',
        'references_data_options.id as opcion_id',
        'references_data_options.value as opcion',        
        DB::raw('COUNT(concencsat_aditional_data.id) as conteo')
    )
    ->where('categories','conc_encuesta_satisf')
    ->groupBy('references_data.id', 'references_data_options.id')
    ->get();
    $resultadosAgrupados = $resultados->groupBy('pregunta_id')->map(function ($items, $key) {
      return [
          'id' => $items->first()->pregunta_id,
          'pregunta' => $items->first()->pregunta,
          'resultados' => $items->map(function ($item) {
              return [
                  'id' => $item->opcion_id,
                  'label' => $item->opcion,
                  'value' => $item->conteo,                  
              ];
          })->values()
      ];
  })->values();


  return response()->json($resultadosAgrupados);



  }
  public function showResultados(Request $request)
  { 
    $encuestas = ConcEncuestaSatisf::orderBy('created_at','asc')->paginate(1);
    if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
      $view = view('myforms.encuestas.conciliaciones.resultados_individual_ajax',compact('encuestas'))->render();
      $response=[
        "view"=>$view
      ];
      return response()->json($response);

    }
    return view('myforms.encuestas.conciliaciones.resultados',compact('encuestas'));
  }

}

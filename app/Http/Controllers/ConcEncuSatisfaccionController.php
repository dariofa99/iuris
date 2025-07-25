<?php

namespace App\Http\Controllers;

use App\AdminEncuestas;
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
use App\Services\PeriodosService;
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
  private $periodosService;

  public function __construct(
    ConciliacionesService $conciliacionesService,
    LoginService $loginService,
    ReferenciasService $referenciasService,
    UsersService $userService,
    ConcEncuSatisfaccionService $encuestaService,
    PeriodosService $periodosService
  ) {
    $this->encuestaService = $encuestaService;
    $this->referenciasService = $referenciasService;
    $this->userService = $userService;
    $this->loginService = $loginService;
    $this->periodosService = $periodosService;
    $this->middleware('auth', ['except' => ['index', 'findUser']]); //not work
  }

  public function renderForm(Request $request)
  {
    $encuesta = ConcEncuestaSatisf::where('token', $request->get('token'))->first();
    if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
      $paginate = true;
      $data = $encuesta->encuesta->preguntas;
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
    //    return response()->json($request->all());
    $periodo_activo = $this->periodosService->getPeriodoActivo();
    $request['periodo_id'] = $periodo_activo->id;
    $request['user_id'] = auth()->user()->id;
    $request['tipo_usuario_id'] = 1;
    $encuesta = $this->encuestaService->store($request);
    // 

    return response()->json($encuesta);
  }

  public function update(Request $request)
  {
    //return response()->json($request->all());
    $encuesta = $this->encuestaService->find($request->conencuesta_id);
    $encuesta = $this->encuestaService->update($request, $encuesta);
    Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
    Auth::logout();
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
    $encuestaAct = AdminEncuestas::where("categoria_id", 257)
      ->where("activo", 1)->first();
    if (!$encuestaAct) {
      return response()->json([
        "errors" => ["No hay una encuesta activa"]
      ]);
    }

    $resultados = DB::table('references_data')
      ->join('references_data_options', 'references_data.id', '=', 'references_data_options.references_data_id')
      ->leftJoin('concencsat_aditional_data', function ($join) {
        $join->on('references_data.id', '=', 'concencsat_aditional_data.reference_data_id')
          ->on('references_data_options.id', '=', 'concencsat_aditional_data.reference_data_option_id');
      })
      ->leftJoin('conc_encuesta_satisf', function ($join) use ($encuestaAct) {
        $join->on('conc_encuesta_satisf.id', '=', 'concencsat_aditional_data.enc_satisf_id')
          ->where('conc_encuesta_satisf.encuesta_id', $encuestaAct->id);
      })
      ->select(
        'references_data.id as pregunta_id',
        'references_data.name as pregunta',
        'references_data_options.id as opcion_id',
        'references_data_options.value as opcion',
        DB::raw('COUNT(concencsat_aditional_data.id) as conteo')
      )
      ->where('categories', 'conc_encuesta_satisf')
      ->where('conc_encuesta_satisf.periodo_id', $request->periodo)
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
    $periodos = $this->periodosService->index($request);
    $periodo_activo = $this->periodosService->getPeriodoActivo();
    $encuestaAct = AdminEncuestas::where("categoria_id", 257)
      ->where("activo", 1)->first();

    $encuestas = ConcEncuestaSatisf::where(function ($query) use ($request, $encuestaAct) {
        if ($encuestaAct) {
          $query->where("encuesta_id", $encuestaAct->id);
        } else {
          $query->where("encuesta_id", 0);
        }
      })
      ->where(function ($query) use ($request, $periodo_activo) {
        if ($request->has('periodo') && $request->periodo != '') {
          $query->where('periodo_id', $request->periodo);
        } else {
          $query->where('periodo_id', 8);
        }
      })
      ->orderBy('created_at', 'asc')
      ->paginate(1);


    $admin_encuestas = AdminEncuestas::where("categoria_id", 257)->get();


    if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
      $view = view('myforms.encuestas.conciliaciones.resultados_individual_ajax', compact('encuestas'))->render();
      $response = [
        "view" => $view
      ];
      return response()->json($response);
    }

    return view('myforms.encuestas.conciliaciones.resultados', compact('encuestas', 'admin_encuestas', 'periodos'));
  }
}

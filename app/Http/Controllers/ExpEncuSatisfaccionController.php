<?php

namespace App\Http\Controllers;

use App\AdminEncuestas;
use Illuminate\Http\Request;
use App\Auditoria;
use App\ConcEncSatifAditionalData;
use App\ConcEncuestaSatisf;
use App\Conciliacion;
use App\Expediente;
use App\ExpEncuestaSatisf;
use App\Mail\RegConcEncuestaSatSuccess;
use App\ReferencesData;
use App\Services\ConcEncuSatisfaccionService;
use App\Services\ConciliacionesService;
use App\Services\ExpEncuSatisfaccionService;
use App\Services\LoginService;
use App\Services\PeriodosService;
use App\Services\ReferenciasService;
use App\Services\UsersService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ExpEncuSatisfaccionController extends Controller
{
    private $userService;
    private $encuestaService;
    private $referenciasService;
    private $loginService;
    private $conciliacionesService;
    private $periodosService;
    public function __construct(
        ConciliacionesService $conciliacionesService,
        LoginService $loginService,
        ReferenciasService $referenciasService,
        UsersService $userService,
        ExpEncuSatisfaccionService $encuestaService,
        PeriodosService $periodosService
    ) {
        $this->encuestaService = $encuestaService;
        $this->referenciasService = $referenciasService;
        $this->userService = $userService;
        $this->loginService = $loginService;
        $this->conciliacionesService = $conciliacionesService;
        $this->periodosService = $periodosService;
        $this->middleware('auth', ['except' => ['index', 'findUser', 'buscarExpedientes']]); //not work
    }

    public function renderForm(Request $request)
    {
        $encuesta = ExpEncuestaSatisf::where('token', $request->get('token'))->first();
        if (
            $request->ajax()
            || $request->header('X-Requested-With') == 'XMLHttpRequest'
        ) {
            $paginate = true;
            $data = $encuesta->encuesta->preguntas;
            $view = view(
                'myforms.categorias.refs_aditional_data',
                compact('data', 'encuesta')
            )->render();
            return response()->json($view);
        }
        //dd($encuesta->encuesta);
        return view('myforms.encuestas.expedientes.formulario', compact('encuesta'));
    }

    public function index()
    {
        $tipodoc = $this->referenciasService->getReferenciasByFilter(
            [
                'tabla_ref' => 'users',
                'categoria' => 'tipo_doc'
            ]
        );

        return view('myforms.encuestas.expedientes.index', compact('tipodoc'));
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
        }
        // return response()->json($user);
        if ($user) {
            $encontrado = true;
            Auth::login($user);
            $login = $this->loginService->login($request);
            $response['user'] = $user;
            return response()->json($response);
        }
        $response["errors"] = ["No se encontró la persona usuaria"];
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $encuesta = ExpEncuestaSatisf::where('exp_id', $request->get('exp_id'))
            ->first();

        if ($encuesta) {
            $expe = Expediente::find($request->input("exp_id"));
            if ($expe) {
                $user = User::find($expe->solicitante->id);
                Auth::login($user);
            }
            return response()->json($encuesta);
        } else {
            $request['user_id'] = auth()->user()->id;
            if ($request->has("exp_id")) {
                $expe = Expediente::find($request->input("exp_id"));
                if ($expe) {
                    $user = User::find($expe->solicitante->id);
                    Auth::login($user);
                    $request['user_id'] = $expe->solicitante->id;
                }
            }

            //$request['tipo_usuario_id'] = 1;
            $encuesta = $this->encuestaService->store($request);
            if (!$encuesta) {
                return response()->json([
                    "errors" => ["No hay una encuesta activa"]
                ]);
            }
            return response()->json($encuesta);
        }
        // return response()->json($request->all());
        // 


    }

    public function update(Request $request)
    {
        //return response()->json($request->all());
        $encuesta = $this->encuestaService->find($request->expencuesta_id);
        $encuesta = $this->encuestaService->update($request, $encuesta);
        return response()->json($encuesta);
    }

    public function buscarConciliaciones(Request $request)
    {
        $user = currentUser();
        $user->conciliaciones;
        return view('myforms.encuestas.conciliaciones.conciliaciones', compact('user'));
    }

    public function buscarExpedientes(Request $request)
    {
        $user = currentUser();
        //dd($user->casosRevision);

        if (count($user->casosRevision) > 0) {
            return view('myforms.encuestas.expedientes.expedientes', compact('user'));
        } else {
            Session::flash('message-danger', 'No se encontraron asignaciones para el número de documento.');

            return redirect("/expedientes/encuestas/start");
        }
    }

    public function getDataForChart(Request $request)
    {
        $encuestaAct = AdminEncuestas::where("activo", 1)->first();
        if (!$encuestaAct) {
            return response()->json([
                "errors" => ["No hay una encuesta activa"]
            ]);
        }
        $resultados = DB::table('references_data')
            ->join('references_data_options', 'references_data.id', '=', 'references_data_options.references_data_id')
            ->leftJoin('expencsat_aditional_data', 'references_data_options.id', '=', 'expencsat_aditional_data.reference_data_option_id')
            ->leftJoin('exp_encuesta_satisf', function ($join) use ($encuestaAct) {
                $join->on('exp_encuesta_satisf.id', '=', 'expencsat_aditional_data.exp_satisf_id')
                    ->where('exp_encuesta_satisf.encuesta_id', $encuestaAct->id);
            })
            ->select(
                'references_data.id as pregunta_id',
                'references_data.name as pregunta',
                'references_data_options.id as opcion_id',
                'references_data_options.value as opcion',
                DB::raw('COALESCE(COUNT(expencsat_aditional_data.id), 0) as conteo')
            )
            ->where('references_data.categories', 'exp_encuesta_satisf')
            ->where('exp_encuesta_satisf.periodo_id', $request->periodo)
            ->groupBy(
                'references_data.id',
                'references_data.name',
                'references_data_options.id',
                'references_data_options.value'
            )
            ->get();
        // Agrupamos los resultados por pregunta
        $resultadosAgrupados = $resultados->groupBy('pregunta_id')->map(function ($items) {
            return [
                'id' => $items->first()->pregunta_id,
                'pregunta' => $items->first()->pregunta,
                'resultados' => $items->map(function ($item) {
                    return [
                        'id' => $item->opcion_id,
                        'label' => $item->opcion,
                        'value' => $item->conteo ?? 0, // Mostrar conteo 0 si no hay respuestas
                    ];
                })->values(),
            ];
        })->values();

        return response()->json($resultadosAgrupados);
    }
    public function showResultados(Request $request)
    {
        $periodos = $this->periodosService->index($request);
        $periodo_activo = $this->periodosService->getPeriodoActivo();
        $encuestaAct = AdminEncuestas::where("activo", 1)->first();
        $encuestas = ExpEncuestaSatisf::orderBy('created_at', 'asc')
            ->where(function ($query) use ($request, $encuestaAct) {
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
            ->paginate(1); 

        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $view_re = view('myforms.encuestas.expedientes.resultados_individual_ajax', compact('encuestas'))->render();
            $response = [
                "view" => $view_re
            ];
            return response()->json($response);
        }
        $admin_encuestas = AdminEncuestas::all();

        return view('myforms.encuestas.expedientes.resultados', compact('encuestas', 'admin_encuestas', 'periodos'));
    }
    function getQuestionsById(Request $request, $id)
    {
        $encuesta = AdminEncuestas::find($id);
        $view = view("myforms.encuestas.expedientes.preguntas_form", compact("encuesta"))->render();
        return response()->json([

            "view" => $view
        ]);
    }
}

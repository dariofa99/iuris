<?php

namespace App\Http\Controllers;

use App\AdminPersonas;
use App\ConcPersonasExternas;
use App\ReferencesData;
use App\Services\ConcPersonaExternaService;
use Illuminate\Http\Request;

class ConciliacionPersonasController extends Controller
{
    protected $concPersonaExternaService;

    public function __construct(ConcPersonaExternaService $concPersonaExternaService)
    {
        $this->middleware('auth');
        $this->concPersonaExternaService = $concPersonaExternaService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //$periodo_activo = $this->periodosService->getPeriodoActivo();
        /* $encuestaAct = AdminPersonas::where("categoria_id", 256)
            ->where("activo", 1)->first();
 */
        $encuestas = ConcPersonasExternas::orderBy('created_at', 'asc')
            /* ->where(function ($query) use ($request, $encuestaAct) {
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
                    $query->where('periodo_id', $periodo_activo->id);
                }
            }) */
            ->paginate(10);

        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $view_re = view('myforms.personas.expedientes.resultados_individual_ajax', compact('encuestas'))->render();
            $response = [
                "view" => $view_re,
                "data" => $encuestas
            ];
            return response()->json($response);
        }




        $admin_encuestas = AdminPersonas::where("categoria_id", 257)->get();
      //  dd($admin_encuestas);
        return view('myforms.personas.expedientes.resultados', compact('encuestas', 'admin_encuestas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $encuesta = $this->concPersonaExternaService->store($request);
        if (!$encuesta) {
            return response()->json([
                "errors" => ["No hay una encuesta activa"]
            ]);
        }
       // $conc = $encuesta->conciliacion;
        return response()->json($encuesta, 200);

       

        $encuesta = ExpEncuestaSatisf::where('exp_id', $request->get('exp_id'))
            ->first();
        $periodo_activo = $this->periodosService->getPeriodoActivo();
        $request['periodo_id'] = $periodo_activo->id;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function getByRefDataFilter(Request $request)
    {
        //return response()->json("esste");
        $preguntasSinEncuesta = ReferencesData::with("options")->whereNotIn('id', function ($query) use ($request) {
            $query->select('pregunta_id')
                ->from('personas_externas_preguntas')
                ->where('persona_externa_id', $request->encuesta_id);
        })
            /*  ->where([
            'categories' => $request->categories,
            'table' => $request->table,
        ]) */
            ->get();


        $response = [];
        $response['view'] = view('myforms.personas.preguntas.preguntas', [
            'data' => $preguntasSinEncuesta,
            'col' => 12,
            'design' => 'select_question',
        ])->render();
        return response()->json($response);
    }

    public function addPreguntasForm(Request $request)
    {
        $encuesta = AdminPersonas::find($request->encuesta_id);
        if ($request->has("pregunta_id") and is_array($request->input("pregunta_id"))) {
            $orden = 1;
            if (count($encuesta->preguntas) > 0) {
                $orden = $encuesta->preguntas()->orderBy("orden", "desc")->first()->pivot->orden + 1;
            }
            foreach ($request->input("pregunta_id") as $key => $pregunta) {

                $encuesta->preguntas()->syncWithoutDetaching([
                    $pregunta => ['orden' => $orden]
                ]);
                $orden++;
            }
        }
        $encuesta = AdminPersonas::find($request->encuesta_id);
        $view = view("myforms.encuestas.preguntas.preguntas_form", compact("encuesta"))->render();
        return response()->json([
            "view" => $view,
            "encuesta" => $encuesta
        ]);
    }
}

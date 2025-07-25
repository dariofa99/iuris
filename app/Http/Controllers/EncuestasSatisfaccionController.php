<?php

namespace App\Http\Controllers;

use App\AdminEncuestas;
use App\Services\ReferencesDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncuestasSatisfaccionController extends Controller
{

    protected $referencesDataService;
    public function __construct(
        ReferencesDataService $referencesDataService
    ) {
        $this->referencesDataService = $referencesDataService;
    }

    public function index(Request $request)
    {


        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $paginate = true;
            $data = getReferencesDataBySection(
                'personalizado',
                'conc_encuesta_satisf'
            );
            $view = view(
                'myforms.categorias.refs_aditional_data',
                compact('data', 'paginate')
            )->render();
            return response()->json(["view" => $view]);
        }

        return view('myforms.encuestas.conciliaciones.formulario');
    }

    public function store(Request $request)
    {


        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $encuesta = AdminEncuestas::create($request->all());
            $admin_encuestas = AdminEncuestas::where("categoria_id", $request->categoria_id)->get();

            $view = view('myforms.encuestas.expedientes.encuestas_list_ajax', compact('admin_encuestas'))->render();

            return response()->json([
                "encuesta" => $encuesta,
                "view" => $view
            ]);
        }
        //return view('myforms.encuestas.conciliaciones.formulario');
    }

    public function storeCategoria(Request $request)
    {


        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $encuesta = AdminEncuestas::find($request->encuesta_id);
            $pregunta = $this->referencesDataService->store($request);
            if ($encuesta) {
                $orden = 1;
                if (count($encuesta->preguntas) > 0) {
                    $orden = $encuesta->preguntas()->orderBy("orden", "desc")->first()->pivot->orden + 1;
                }
                $encuesta->preguntas()->attach($pregunta, [
                    "orden" => $orden,
                ]);
            }

            $encuesta = AdminEncuestas::find($request->encuesta_id);
            $view = view("myforms.encuestas.preguntas.preguntas_form", compact("encuesta"))->render();
            return response()->json([
                "view" => $view
            ]);
        }
        //return view('myforms.encuestas.conciliaciones.formulario');
    }

    public function update(Request $request, $id)
    {


        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            DB::table("admin_encuestas_general")
                ->where("categoria_id", $request->categoria_id)
                ->update([
                    "activo" => false
                ]);
            $encuesta = AdminEncuestas::find($id);
            $encuesta->fill($request->all());
            $encuesta->save();
            return response()->json([
                "view" => $encuesta
            ]);
        }
        //return view('myforms.encuestas.conciliaciones.formulario');
    }

    public function addPreguntasEncuesta(Request $request)
    {
        $encuesta = AdminEncuestas::find($request->encuesta_id);
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
        $encuesta = AdminEncuestas::find($request->encuesta_id);
        $view = view("myforms.encuestas.preguntas.preguntas_form", compact("encuesta"))->render();
        return response()->json([
            "view" => $view,
            "encuesta" => $encuesta
        ]);
    }

    function getQuestionsById(Request $request, $id)
    {
        $encuesta = AdminEncuestas::find($id);
        $view = view("myforms.encuestas.preguntas.preguntas_form", compact("encuesta"))->render();
        return response()->json([

            "view" => $view
        ]);
    }

    function deletePreguntaEncuesta(Request $request, $id)
    {
        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $encuesta = AdminEncuestas::find($id);
            if ($encuesta) {
                $encuesta->preguntas()->detach($request->pregunta_id);
                $encuesta = AdminEncuestas::find($id);
                $view = view("myforms.encuestas.preguntas.preguntas_form", compact("encuesta"))->render();
                return response()->json([
                    "view" => $view,
                    "encuesta" => $encuesta
                ]);
            } else {
                return response()->json([
                    "errors" => ["No se encontró la pregunta"]
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\AdminPersonas;
use App\Services\ReferencesDataService;
use Illuminate\Http\Request;

class PersonasExternasController extends Controller
{
    private $referencesDataService;


    public function __construct(
        ReferencesDataService $referencesDataService
    ) {
        $this->referencesDataService = $referencesDataService;
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        
        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $encuesta = AdminPersonas::create($request->all());
            $admin_encuestas  = AdminPersonas::where("categoria_id", $request->categoria_id)->get();

            $view = view('myforms.personas.expedientes.encuestas_list_ajax', compact('admin_encuestas'))->render();

            return response()->json([
                "encuesta" => $encuesta,
                "view" => $view
            ]);
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

    public function getQuestionsById(Request $request, $id)
    {
        $encuesta = AdminPersonas::find($id);
        $view = view("myforms.personas.preguntas.preguntas_form", compact("encuesta"))->render();
        return response()->json([
            $encuesta,

            "view" => $view
        ]);
    }


     public function storeCategoria(Request $request)
    {


        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            $encuesta = AdminPersonas::find($request->encuesta_id);
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

            $encuesta = AdminPersonas::find($request->encuesta_id);
            $view = view("myforms.personas.preguntas.preguntas_form", compact("encuesta"))->render();
            return response()->json([
                "view" => $view
            ]);
        }
        //return view('myforms.encuestas.conciliaciones.formulario');
    }

}

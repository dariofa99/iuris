<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class EncuestasSatisfaccionController extends Controller
{


    public function index(Request $request)
    {


        if($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest'){
            $paginate = true;
            $data = getReferencesDataBySection(
                'personalizado',
                'conc_encuesta_satisf');
            $view = view('myforms.categorias.refs_aditional_data',
            compact('data','paginate')
            )->render();
            return response()->json(["view"=>$view]);
        }
        return view('myforms.encuestas.conciliaciones.formulario');
    }
    
}

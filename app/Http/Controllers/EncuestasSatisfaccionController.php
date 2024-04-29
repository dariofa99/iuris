<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auditoria;
use App\Expediente;

class EncuestasSatisfaccionController extends Controller
{


    public function index(Request $request)
    {



        return view('myforms.encuestas.conciliaciones.index');
    }
    public function buscarConciliaciones(Request $request)
    {



        return response()->json($request->all());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auditoria;
use App\Expediente;

class AuditoriaController extends Controller
{
    
    public function index(){

    	$auditorias = Auditoria::orderBy('created_at','desc')->paginate(200);

        //dd($auditorias);

    	return view('myforms.frm_auditoria',compact('auditorias'));
    }

    public function show($id){

    		$auditoria = Auditoria::find($id);
            $auditoria->usuario;
            $auditoria->expediente;
    		$exp_auditado = json_decode($auditoria->data);
            $expediente_actual = Expediente::find($auditoria->exp_id);
            $data = [
                'auditoria'=>$auditoria,
                'exp_auditado'=>$exp_auditado,
                'expediente_actual'=>$expediente_actual
            ];

    		return response()->json($data);

    }
}
 
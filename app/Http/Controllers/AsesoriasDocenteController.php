<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use DB;
use App\AsesoriaDocente;

class AsesoriasDocenteController extends Controller
{
    

	public function store(Request $request){

		$expediente = Expediente::where('expid',$request->expid)->first();
		$estudiante_id = $expediente->estudiante->idnumber;
		$docente_id = \Auth::user()->idnumber;
		AsesoriaDocente::create([                                    
                                    'comentario'=>$request->comentario, 
                                    'estado'=>1, 
                                    'apl_shared'=>$request->apl_shared,
                                    'estidnumber'=>$estudiante_id, 
                                    'docidnumber'=> $docente_id,
                                    'expidnumber'=> $request->expid,                                    
                                 ]);

		return response()->json($request->all());
	}


	public function edit($id){
		$asesoria = AsesoriaDocente::find($id);
		return response()->json($asesoria);
	}

	public function update(Request $request,$id){
		$asesoria = AsesoriaDocente::find($id);
		$asesoria->comentario = $request->comentario;
		$asesoria->save();
		return response()->json($asesoria);
	}

	public function destroy($id){
		$asesoria = AsesoriaDocente::find($id);
		$asesoria->estado = 0;
		$asesoria->save();
		return response()->json($asesoria);
	}

	public function changeShared(Request $request){

			$asesoria = AsesoriaDocente::find($request->id);
			if ($asesoria->apl_shared) {
				$asesoria->apl_shared = 0;
			}else if (!$asesoria->apl_shared){
				$asesoria->apl_shared = 1;
			}
			
			$asesoria->save();

			return response()->json($asesoria);
	}


}

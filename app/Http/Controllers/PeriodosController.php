<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use DB;
use App\Periodo;
use App\Segmento;

class PeriodosController extends Controller
{
 
	public function __construct()
  {
      
     // $this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
      //$this->middleware('permission:ver_periodos',   ['only' => ['index']]);
  }

	function getPeriodos($request){
		$periodos = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->orderBy('periodo.created_at','desc')->get();
		return $periodos;

	}

function getPeriodo($request){
		$periodos = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->orderBy('periodo.created_at','desc')
		->Criterio($request->data_search,$request->datatype)->get();

		return $periodos;

	}

    public function index(Request $request){

    		$active_config = 'active';
    		if ($request->ajax()) {
    			if ($request->data_search and $request->datatype) {
    				$periodos = $this->getPeriodo($request);
    			}
    			if ($request->data_search and $request->data_search=='all') {
    				$periodos = $this->getPeriodos($request);
    			}
    			return view('myforms.frm_periodos_list_ajax',compact('periodos'))->render();
    		}else{
    			if ($request->data_search and $request->datatype) {
    				$periodos = $this->getPeriodo($request);
    			}else{
    				$periodos = $this->getPeriodos($request);
    			}
    		}
			
    		return  view('myforms.frm_periodos_list',compact('periodos','active_config'));
	}

	public function store(Request $request){
//dd($request);
		$periodo = new Periodo($request->all());
		$periodo->save();
		if(session('sede')){
			$periodo->sedes()->attach(session('sede')->id_sede);
		}
		$periodos = $this->getPeriodos($request);

		return view('myforms.frm_periodos_list_ajax',compact('periodos'))->render();
			return response()->json($periodo);
	} 


	public function show($id){
		$periodo = Periodo::find($id);
		return response()->json($periodo);

	}

	public function update(Request $request,$id){
		$periodo = Periodo::find($id);
		$periodo->fill($request->all());
		$periodo->save();
		$periodos = $this->getPeriodos($request);
		return view('myforms.frm_periodos_list_ajax',compact('periodos'))->render();
	}

	public function destroy($id){
		$periodo = Periodo::find($id);		
		$periodo->delete();

		$periodos = $this->getPeriodos($request=0);
		return view('myforms.frm_periodos_list_ajax',compact('periodos'))->render();
	}

	public function changeState($id){

		$per= DB::table('periodo')
		->join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->update(['estado'=>false]);  
		$seg= DB::table('segmentos')
		->join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
		->where('sg.sede_id',session('sede')->id_sede)
		->update(['estado'=>false]);           
		$segmento = Periodo::find($id);
		$segmento->estado = true;
		$segmento->save();

		$periodos = $this->getPeriodos($request=0);

		return view('myforms.frm_periodos_list_ajax',compact('periodos'))->render();

		return redirect('/periodos/');

		
	}

	public function searchSegmentos(Request $request,$id){
		$segmentos = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
		->where('sg.sede_id',session('sede')->id_sede)
		->where('perid',$id)->get();
		return response()->json($segmentos);
	}

}

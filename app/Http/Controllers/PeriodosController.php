<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use DB;
use App\Periodo;
use App\Segmento;
use App\Services\PeriodosService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PeriodosController extends Controller
{

	private $periodosService;
	public function __construct(PeriodosService $periodosService)
	{
		$this->periodosService = $periodosService;
		// $this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
		//$this->middleware('permission:ver_periodos',   ['only' => ['index']]);
	}

	function getPeriodos($request)
	{
		$periodos = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->orderBy('periodo.created_at', 'desc')->get();
		return $periodos;
	}

	function getPeriodo($request)
	{
		$periodos = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->orderBy('periodo.created_at', 'desc')
			->Criterio($request->data_search, $request->datatype)->get();

		return $periodos;
	}

	public function index(Request $request)
	{

		$active_config = 'active';
		if ($request->ajax()) {
			if ($request->data_search and $request->datatype) {
				$periodos = $this->getPeriodo($request);
			}
			if ($request->data_search and $request->data_search == 'all') {
				$periodos = $this->getPeriodos($request);
			}
			return view('myforms.frm_periodos_list_ajax', compact('periodos'))->render();
		} else {
			if ($request->data_search and $request->datatype) {
				$periodos = $this->getPeriodo($request);
			} else {
				$periodos = $this->getPeriodos($request);
			}
		}

		return  view('myforms.frm_periodos_list', compact('periodos', 'active_config'));
	}

	public function store(Request $request)
	{
		$periodo = $this->periodosService->findWithFilter([
			'prdfecha_inicio'=>$request->prdfecha_inicio,
			'prdfecha_fin'=>$request->prdfecha_fin
		]);
		if ($periodo) {
			if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
				return response()->json(['errors' => ["El periodo registrado ya existe"]]);
			}
		}
		$periodo = $this->periodosService->store($request);
		$periodos = $this->periodosService->index($request);
		$view = view('myforms.frm_periodos_list_ajax', compact('periodos'))->render();
		return response()->json($view);
	}


	public function show($id)
	{
		$periodo = Periodo::find($id);
		return response()->json($periodo);
	}

	public function update(Request $request, $id)
	{
		$periodo = $this->periodosService->find($id);
		$messages = [
			'prdfecha_inicio.unique' => 'La fecha de inicio ya existe.',
			'prdfecha_fin.unique' => 'La fecha de fin ya existe.',
		];
		$validator = Validator::make($request->all(), [
			'prdfecha_inicio' => ['required', Rule::unique('periodo')->ignore($periodo,'prdfecha_inicio') ],
			'prdfecha_fin' => ['required', Rule::unique('periodo')->ignore($periodo,'prdfecha_fin')]
		], $messages);

		if ($validator->fails()) {
			if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
				return response()->json(['errors' => $validator->errors()->all()]);
			}
		}		
		$periodo = $this->periodosService->update($periodo, $request);	
		$periodos = $this->periodosService->index($request);
		$view = view('myforms.frm_periodos_list_ajax', compact('periodos'))->render();
		return response()->json($view);
	}

	public function destroy($id)
	{
		$periodo = Periodo::find($id);
		$periodo->delete();

		$periodos = $this->getPeriodos($request = 0);
		return view('myforms.frm_periodos_list_ajax', compact('periodos'))->render();
	}

	public function changeState(Request $request,$id)
	{

		$per = DB::table('periodo')
			->join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->update(['estado' => false]);
		$seg = DB::table('segmentos')
			->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->update(['estado' => false]);
		$segmento = $this->periodosService->find($id);
		$segmento->estado = true;
		$segmento->save();

		$periodos = $this->periodosService->index($request);
		$view = view('myforms.frm_periodos_list_ajax', compact('periodos'))->render();
		return response()->json($view);
	}

	public function searchSegmentos(Request $request, $id)
	{
		$segmentos = Segmento::join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->where('perid', $id)->get();
		return response()->json($segmentos);
	}
}

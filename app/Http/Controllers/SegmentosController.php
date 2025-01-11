<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;


use App\Segmento;
use App\Actuacion;
use App\Requerimiento;
use App\Periodo;
use App\AsigDocenteCaso;
use App\AsignacionCaso;
use Carbon\Carbon;
use App\Nota;
use App\HistorialDatosCaso;
use App\Services\EstadosCasoService;
use App\Services\PausasService;
use App\Services\PeriodosService;
use App\Services\VacacionesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use stdClass;

class SegmentosController extends Controller
{
	public $periodo;
	private $estadoCasoService;
	private $vacacionesService;
	private $periodoService;
	private $pausaService;

	public function __construct(

		EstadosCasoService $estadoCasoService,
		VacacionesService $vacacionesService,
		PeriodosService $periodoService,
		PausasService $pausaService
	) {

		$this->estadoCasoService = $estadoCasoService;
		$this->vacacionesService = $vacacionesService;
		$this->periodoService = $periodoService;
		$this->pausaService = $pausaService;
	}

	public function periodo()
	{
		return Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->where('estado', true)
			->first();
	}

	function getSegmentos()
	{
		$segmentos = Segmento::join('periodo', 'periodo.id', '=', 'segmentos.perid')
			->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->select('segmentos.id', 'segmentos.segnombre', 'segmentos.act_fc', 'fecha_inicio', 'fecha_fin', 'segmentos.estado', 'periodo.prddes_periodo', 'segmentos.est_evaluado')
			->where('periodo.estado', true)->get();

		return $segmentos;
	}
	function getSegmento($request)
	{
		$segmentos = Segmento::join('periodo', 'periodo.id', '=', 'segmentos.perid')
			->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->where('periodo.estado', true)
			->select('segmentos.id', 'segmentos.segnombre', 'segmentos.act_fc', 'fecha_inicio', 'fecha_fin', 'segmentos.estado', 'periodo.prddes_periodo')
			->Criterio($request->data_search, $request->datatype)->get();

		return $segmentos;
	}

	public function index(Request $request)
	{

		$segmentos = $this->getSegmentos();
		$periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->where('estado', true)->first();
		$active_config = 'active';

		if ($request->ajax()) {
			if ($request->data_search and $request->datatype) {
				$segmentos = $this->getSegmento($request);
			}
			if ($request->data_search and $request->data_search == 'all') {
				$segmentos = $this->getSegmentos();
			}
			return view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
		} else {
			if ($request->data_search and $request->datatype) {
				$segmentos = $this->getSegmento($request);
			} else {
				$segmentos = $this->getSegmentos();
			}
		}

		//dd($periodo);
		return  view('myforms.frm_segmentos_list', compact('segmentos', 'periodo', 'active_config'));
	}

	public function store(Request $request)
	{
		$segmento = new Segmento($request->all());
		//$regmento->segusercreated = Auth::user()->idnumber;
		//$regmento->seguserupdated = Auth::user()->idnumber;		
		$segmento->save();
		if (session('sede')) {
			$segmento->sedes()->attach(session('sede')->id_sede);
		}
		$segmentos = $this->getSegmentos();
		$view = view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
		return response()->json($view);
	}


	public function show($id)
	{
		$segmento =  Segmento::find($id);
		return response()->json($segmento);
	}

	public function update(Request $request, $id)
	{
		$segmento =  Segmento::find($id);
		$segmento->fill($request->all());
		$segmento->save();
		$segmentos = $this->getSegmentos();
		$view = view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
		return response()->json($view);
	}

	public function destroy($id)
	{
		$segmento =  Segmento::find($id);
		$segmento->delete();
		$segmentos = $this->getSegmentos();
		return view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
	}

	public function changeState($id)
	{

		$users = DB::table('segmentos')
			->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->update(['estado' => false, 'act_fc' => false]);
		$segmento = Segmento::find($id);
		$segmento->estado = true;
		$segmento->save();


		$segmentos = $this->getSegmentos();
		$view = view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
		return response()->json($view);
	}

	public function change_state_segfc(Request $request)
	{
		$segmentoact = Segmento::join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
			->where('sg.sede_id', session('sede')->id_sede)
			->where('estado', true)->first();
		if (empty($segmentoact)) {
			$response = [
				'success' => false,
				'msj' => 'No hay un segmento de corte activo'
			];
		} else {
			if ($segmentoact->act_fc) {
				$segmentoact->act_fc = 0;
				$segmentoact->fecha_corte = null;
			} else {
				$segmentoact->act_fc = 1;
				$segmentoact->fecha_corte = date('Y-m-d');
			}

			$segmentoact->save();

			$response = [
				'success' => true,     
				'msj' => 'Correcto',
				'statusfc' => $segmentoact->act_fc,
				'seg' => $segmentoact->id
			];
		}


		return response()->json($response);
	}

	private function evaluarExpedientes($expedientes, $segmento, $dateiniciocorte, $datefinalcortereal, $request)
	{

		$docente_id = Auth::user()->idnumber;
		$exps = [];

		foreach ($expedientes as $key => $expediente) {
			//$expediente = $expedientes[1];
			if (($expediente->exptipoproce_id == 3
					and $expediente->exphechos == 0)
				|| ($expediente->exptipoproce_id != 3 and
					($expediente->exphechos == 0 || $expediente->exprtaest == 0))
			) {

				//se registra un cero cuando no tiene informacion en datos del caso
				$asignacion = AsignacionCaso::where([
					'asigexp_id' => $expediente->expid,
					'asigest_id' => $expediente->expidnumberest,
					'activo' => 1
				])->first();

				if ($asignacion->evaluado_hechos == 0) {
					$data = [
						'ntaaplicacion' => 0,
						'ntaconocimiento' => 0,
						'ntaetica' => 0,
						'ntaconcepto' => 'Sin información de hechos o respuesta del caso. ',
						'orgntsid' => 4,
						'segid' => $segmento->id,
						'perid' => $segmento->perid,
						'tpntid' => 2,
						'expidnumber' => $expediente->expid,
						'estidnumber' => $expediente->expidnumberest,
						'docidnumber' => $docente_id,
						'tbl_org_id' => $expediente->id,
					];

					$this->Asignotasnewdatos($data);
					$asignacion->evaluado_hechos = 1;
					$asignacion->save();
					Log::info("Evaluado {$expediente->expidnumberest}");
					Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
				}
			} else {

				//cuanto tiempo paso al llenar la informacion desde asignado el caso
				//$historial =
				$historial =  HistorialDatosCaso::where('hisdc_expidnumber', $expediente->expid)
					//->join('users', 'users.idnumber','=','historial_datos_casos.hisdc_idnumberest_id')
					//->join('asignacion_caso', 'asignacion_caso.asigexp_id','=','historial_datos_casos.hisdc_expidnumber')
					->select('historial_datos_casos.created_at')
					//->whereDate('fecha_asig', '>=', $dateiniciocorte)
					->where('hisdc_idnumberest_id', $expediente->expidnumberest)
					->orderBy('historial_datos_casos.created_at', 'ASC')
					->first();

				if ($historial) {
					$fecha_1 = Carbon::parse($expediente->fecha_asig);
					$fecha_2 = Carbon::parse($historial->created_at);
					$dias_sin_hechos =  Carbon::parse($fecha_1)
						->diffInDays($fecha_2);
					if ($dias_sin_hechos > 5) {
						//Se evalua si venció en vacaciones
						$asignacion = AsignacionCaso::where([
							'asigexp_id' => $expediente->expid,
							'asigest_id' => $expediente->expidnumberest,
							'activo' => 1
						])->first();
						$fecha_2 = $this->getFechaDos($asignacion, $fecha_2);
						$dias_sin_hechos =  Carbon::parse($fecha_1)
							->diffInDays($fecha_2);
						if ($dias_sin_hechos > 5 and $asignacion->evaluado_hechos == 0) {
							//se evalua que no hayan habido vacaciones y pausas
							$dias_pausa = 0;
							$pausas = $this->pausaService->getByAsignacion($asignacion, [
								['operador' => ">=", "value" => $fecha_1],
								['operador' => "<=", "value" => $fecha_2]
							]);
							if (count($pausas) > 0) {
								//validar las pausas con vacaciones
								$dias_pausa = $this->getDiasPausado($pausas, $fecha_1, $fecha_2);
								$dias_sin_hechos = $dias_sin_hechos - $dias_pausa;
							} else {
								//Se evalua que no haya habido vacaciones
								$_vacaciones = $this->vacacionesService->getByDates([
									['operador' => ">=", "value" => $fecha_1],
									['operador' => "<=", "value" => $fecha_2]
								]);
								$dias_vacaciones = 0;
								if (count($_vacaciones) > 0) {
									$dias_vacaciones = $this->vacacionesService->getDays($_vacaciones);
									$dias_sin_hechos = $dias_sin_hechos - $dias_vacaciones;
								}
							}
							if ($dias_sin_hechos > 5) {

								$data = [
									'ntaaplicacion' => 0,
									'ntaconocimiento' => 0,
									'ntaetica' => 0,
									'ntaconcepto' => 'Demora en colocar información en datos del caso. ' . date('Y-m-d'),
									'orgntsid' => 4,
									'segid' => $segmento->id,
									'perid' => $segmento->perid,
									'tpntid' => 2,
									'expidnumber' => $expediente->expid,
									'estidnumber' => $expediente->expidnumberest,
									'docidnumber' => $docente_id,
									'tbl_org_id' => $expediente->id,
								];
								$this->Asignotasnewdatos($data);
								$asignacion->evaluado_hechos = 1;
								$asignacion->save();
								Log::info("Evaluado {$expediente->expidnumberest}");
								Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
							}
						}
					}
				} else {
					//Error en el historial
				}
			}
			$asigdocencaso = AsigDocenteCaso::where([
				'asig_caso_id' => $expediente->asig_caso_id,
				 'activo' => '1'])
				->first();
			if (!$asigdocencaso) {
				$data = [
					'ntaaplicacion' => 0,
					'ntaconocimiento' => 0,
					'ntaetica' => 0,
					'ntaconcepto' => 'No tiene docente asignado ' . date('Y-m-d'),
					'orgntsid' => 4,
					'segid' => $segmento->id,
					'perid' => $segmento->perid,
					'tpntid' => 2,
					'expidnumber' => $expediente->expid,
					'estidnumber' => $expediente->expidnumberest,
					'docidnumber' => $docente_id,
					'tbl_org_id' => $expediente->id,
				];
				$this->Asignotasnewdatos($data);
				Log::info("Evaluado {$expediente->expidnumberest}");
				Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
			}
			//Evalua actuaciones

			if ($expediente->exptipoproce_id != '1') {
				$actuaciones = Actuacion::where('actexpid', $expediente->expid)
					->where('actidnumberest', $expediente->expidnumberest)
					->whereDate('actfecha', '>=', $dateiniciocorte)
					->limit(1)->get();
				if (count($actuaciones) <= 0) {
					$asignacion = AsignacionCaso::where([
						'asigexp_id' => $expediente->expid,
						'asigest_id' => $expediente->expidnumberest,
						'activo' => 1
					])->first();
					$fecha_uno = Carbon::parse($dateiniciocorte);
					$fecha_dos = Carbon::parse($segmento->fecha_fin);
					$dias_sin_act =  Carbon::parse($fecha_uno)
						->diffInDays($fecha_dos);
					if ($dias_sin_act > 31) {
						//Se verifica que no haya vencido en vacaciones
						$fecha1 = $this->getFechaUno($asignacion, $fecha_uno);
						$fecha2 = $this->getFechaDos($asignacion, $fecha_dos);
						$dias_sin_act =  Carbon::parse($fecha1)->diffInDays($fecha2);
						if ($dias_sin_act > 31) {
							$dias_pausa = 0;
							$pausas = $this->pausaService->getByAsignacion($asignacion, [
								['operador' => ">=", "value" => $fecha1],
								['operador' => "<=", "value" => $fecha2]
							]);
							if (count($pausas) > 0) {
								//validar las pausas con vacaciones
								$dias_pausa = $this->getDiasPausado($pausas, $fecha1, $fecha2);
								$dias_sin_act = $dias_sin_act - $dias_pausa;
								if ($dias_sin_act > 31) {
									$fecha_ini = getSmallDate($fecha1);
									$fecha_fini = getSmallDate($fecha2);
									$mensaje = "Sin actuaciones. Periodo evaluado desde {$fecha_ini} hasta $fecha_fini. Se contaron pausas. Días: {$dias_sin_act}";
									$data = [
										'ntaaplicacion' => 0,
										'ntaconocimiento' => 0,
										'ntaetica' => 0,
										'ntaconcepto' => 'No tiene actuaciones o anexos requeridos en más 30 días, requeridos a lo largo del corte. ', //cambiado el texto
										'orgntsid' => 4,
										'segid' => $segmento->id,
										'perid' => $segmento->perid,
										'tpntid' => 2,
										'expidnumber' => $expediente->expid,
										'estidnumber' => $expediente->expidnumberest,
										'docidnumber' => $docente_id,
										'tbl_org_id' => $expediente->id,
									];
									$this->Asignotasnewdatos($data);
									Log::info("Evaluado {$expediente->expidnumberest}");
									Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
								}
							} else {
								//no tuvo pausas
								//Se verifica si tuvo vacaciones en el lapso
								//Si hubieron vacaciones en el lapso
								$_vacaciones = $this->vacacionesService->getByDates([
									['operador' => ">=", "value" => $fecha1],
									['operador' => "<=", "value" => $fecha2]
								]);
								$dias_vacaciones = 0;
								if (count($_vacaciones) > 0) {
									$dias_vacaciones = $this->vacacionesService->getDays($_vacaciones);
									$dias_sin_act = $dias_sin_act - $dias_vacaciones;
								}
								if ($dias_sin_act > 31) {
									$fecha_ini = getSmallDate($fecha1);
									$fecha_fini = getSmallDate($fecha2);
									$mensaje = "Periodo evaluado desde {$fecha_ini} hasta $fecha_fini. Días: {$dias_sin_act}";
									$data = [
										'ntaaplicacion' => 0,
										'ntaconocimiento' => 0,
										'ntaetica' => 0,
										'ntaconcepto' => 'No tiene actuaciones o anexos requeridos en más 30 días, requeridos a lo largo del corte. ', //cambiado el texto
										'orgntsid' => 4,
										'segid' => $segmento->id,
										'perid' => $segmento->perid,
										'tpntid' => 2,
										'expidnumber' => $expediente->expid,
										'estidnumber' => $expediente->expidnumberest,
										'docidnumber' => $docente_id,
										'tbl_org_id' => $expediente->id,
									];
									$this->Asignotasnewdatos($data);
									Log::info("Evaluado {$expediente->expidnumberest}");
									Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
								}
							}
						}
					}
				} else {

					//Se verifica si se realizaron actuaciones cada mes en los casos viejos
					// durante el corte evaluado

					$actuacionsmes = DB::select(DB::Raw("SELECT date_format(actfecha, '%Y-%m-%d') as fechas 
						FROM actuacions
						 WHERE actexpid = '" . $expediente->expid . "' 
							 AND (actestado_id  = '101' 
							 		|| actestado_id  = '102' 
									|| actestado_id  = '103'
									|| actestado_id  = '104'
									|| actestado_id  = '138'
									|| actestado_id  = '139'
								)
							 AND actidnumberest = '" . $expediente->expidnumberest . "' 
							 AND actusercreated = '" . $expediente->expidnumberest . "' 
							 AND (actfecha >= '" . $dateiniciocorte . "' 
						 	 AND  actfecha <= '" . $datefinalcortereal . "') 
						 	 GROUP BY 1 ORDER BY 1 ASC"));
					//$iseva = $this->isActuacionEval($actuacionsmes,$dateiniciocorte,$segmento->fecha_fin);

					//eturn (['sjsj',$actuacionsmes]);
					$res_act = $this->isActuacionEval($actuacionsmes, $dateiniciocorte, $segmento->fecha_fin, $expediente);

					//return ([$res_act]); 

					if ($res_act[0]) {
						$data = [
							'ntaaplicacion' => 0,
							'ntaconocimiento' => 0,
							'ntaetica' => 0,
							'ntaconcepto' => 'No tiene actuaciones o anexos requeridos en más 30 días, requeridos a lo largo del corte. ' . $res_act[1], //cambiado el texto
							'orgntsid' => 4,
							'segid' => $segmento->id,
							'perid' => $segmento->perid,
							'tpntid' => 2,
							'expidnumber' => $expediente->expid,
							'estidnumber' => $expediente->expidnumberest,
							'docidnumber' => $docente_id,
							'tbl_org_id' => $expediente->id,
						];
						$this->Asignotasnewdatos($data);
						Log::info("Evaluado {$expediente->expidnumberest}");
						Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
					}
				}
			}

			if ($expediente->exptipoproce_id ==  1) {

				$expedientemodel = Expediente::where('expid', $expediente->expid)->first();
				$notas =  $expedientemodel->get_has_nota_final();
				$days = $expedientemodel->getDaysOrColorForClose('dias', $datefinalcortereal);
				if ($days <= 0 || $days === true) {
					if ($expedientemodel->expestado_id != 5 and $expedientemodel->expestado_id != 2) {
						$notas =  $expedientemodel->get_has_nota_final();
						if (count($notas) <= 0) {
							$data = [
								'ntaaplicacion' => 0,
								'ntaconocimiento' => 0,
								'ntaetica' => 0,
								'ntaconcepto' => 'Evaluado por el sistema - Tiempo 30 días agotado',
								'orgntsid' => '4',
								'segid' => $segmento->id,
								'perid' => $segmento->perid,
								'tpntid' => '1',
								'expidnumber' => $expedientemodel->expid,
								'estidnumber' => $expedientemodel->expidnumberest,
								'docidnumber' => Auth::user()->idnumber,
								'tbl_org_id' => $expedientemodel->id,
							];

							$expedientemodel->asignarNotas($data);
							$expedientemodel->expestado_id = 5;
							$expedientemodel->save();
							$request['comentario'] = 'Cerrado por sistema, fin de corte. Tiempo 30 días agotado';
							$request['expidnumber'] = $expediente->expid;
							$request['ref_estado_id'] = $expediente->expestado_id;
							$request['ref_motivo_estado_id'] = 12;
							$estado_caso = $this->estadoCasoService->store($request);
							Log::info("Evaluado {$expediente->expidnumberest}");
							Log::info("Evaluado {$expediente->expid} {$data['ntaconcepto']}");
						}
					}
				}
			}
		}
	}

	public function closeSegmento(Request $request, $id)
	{


		//-revisar los casos desde agosto hasta el 6 de febrero -> inicio corte hasta 15 dias antes del final del corte
		//-un cero a los casos que no tengan docente asignado
		//-un cero a los casos que no tengan informacion  en hechos o respuesta del estudiante
		//-un cero a los casos de seguimiento que no tengan ninguna actuacion o anexo mayor al inicio del corte
		//colocar cerro si de demoro mas de ocho dias en subir una actuacion o un anexo
		// para los casos de seguimiento deben tener una actuacion o anexo 1 vez por mes
		//revisar cuantos dias pasaron para llenar hechos y respuesta colocar cero si se demoro más de 5 dias
		//revisar los casos de asesoria que estan abiertos pero requieren ser cerrados por el sistema

		$segmento = Segmento::find($id);
		if ($segmento->fecha_corte == null) {
			Session::flash('message-danger', 'Atención..! No hay una fecha de corte activa');
			//return response()->json(['errors' => ["No hay una fecha de corte activa"]]);
		}
		$now = Carbon::now();
		//$dateiniciocorte = Carbon::parse($segmento->fecha_inicio)->startOfDay(); // Asegura que la fecha de inicio sea a las 00:00:00
		$dateiniciocorte = Carbon::parse("26-08-2024")->startOfDay();
		$fechaFinalCorte = Carbon::parse($segmento->fecha_fin)->endOfDay(); // Asegura que la fecha de corte sea a las 23:59:59
		//consulta sobre todos los casos asignados antes del corte
		//Estos casos son viejos
		$expedientes = DB::select(DB::Raw("Select asignacion_caso.fecha_asig,
		 expedientes.id , expid , expedientes.expidnumberest, if(expedientes.exphechos!='',1,0) as exphechos, if(expedientes.exprtaest!='',1,0) as exprtaest, asignacion_caso.id as asig_caso_id, exptipoproce_id, expestado_id 
		 from expedientes
		 join asignacion_caso on asignacion_caso.asigexp_id = expedientes.expid 
		 join `users` on expedientes.expidnumberest = users.idnumber
		 join turnos on turnos.trnid_estudent = users.idnumber 
		 join sede_expedientes on expedientes.id = sede_expedientes.expediente_id
		 where expedientes.expidnumberest = asignacion_caso.asigest_id 
		 and (expestado_id != 5 and expestado_id != 2 
		 and expestado_id != 8) 		
		 and asignacion_caso.activo = 1 
		 and users.idnumber <> 3030	  
		 and fecha_asig < '" . $dateiniciocorte . "' 
		 and sede_expedientes.sede_id=" . session('sede')->id_sede) . "
		 order by asignacion_caso.fecha_asig desc");
		///////////////fin foreach todos los casos
		$data = $this->evaluarExpedientes($expedientes, $segmento, $dateiniciocorte, $fechaFinalCorte, $request);
		return response()->json([
			'saved' => true,
			'view' => $expedientes
		]);
		//consulta sobre los casos asignados solo durante el corte para notas sobre tiempos limites de inicio
		///return response()->json([$data]);

		$expedientescorte = DB::select(DB::Raw(
			"Select asignacion_caso.fecha_asig,asignacion_caso.evaluado_hechos,
			expedientes.id , expid , expedientes.expidnumberest, if(expedientes.exphechos!='',1,0) as exphechos, if(expedientes.exprtaest!='',1,0) as exprtaest, asignacion_caso.id as asig_caso_id, exptipoproce_id, expestado_id 
			from expedientes
			join asignacion_caso on asignacion_caso.asigexp_id = expedientes.expid 
			join `users` on expedientes.expidnumberest = users.idnumber 
			join sede_expedientes on expedientes.id = sede_expedientes.expediente_id
			where expedientes.expidnumberest = asignacion_caso.asigest_id 
			and expedientes.expidnumberest  <> 3030
			and (expestado_id != 5 and expestado_id != 2 and expestado_id != 8)		
			AND (fecha_asig BETWEEN '" . $dateiniciocorte . "' AND '" . $fechaFinalCorte . "')   
			and asignacion_caso.periodo_id = " . $segmento->perid . " 
			and sede_expedientes.sede_id=" . session('sede')->id_sede . "
			order by asignacion_caso.fecha_asig desc"

		));


		$data = $this->evaluarExpedientes($expedientescorte, $segmento, $dateiniciocorte, $fechaFinalCorte, $request);

		//return response()->json([$data]);
		//and expedientes.expidnumberest = '1006106455'
		//and expedientes.expidnumberest <> 3030 and (fecha_asig <= '" . $segmento->fecha_corte . "') 

		//and fecha_asig <= '".$datemenosquincediasfinalcorte."') 



		$segmento->est_evaluado = 1;
		$segmento->save();
		$segmentos = $this->getSegmentos();
		$view = view('myforms.frm_segmentos_list_ajax', compact('segmentos'))->render();
		return response()->json(['saved' => true, 'view' => $view]);
		//return redirect()->back();

	}

	private function hasVacationsNext($fecha_inicial, $fecha_final)
	{

		$_vacaciones = DB::table("vacaciones_periodo")
			->whereDate('fecha_inicio', '>=', $fecha_inicial)
			->whereDate('fecha_fin', '<=', $fecha_final)
			->where("periodo_id", $this->periodo()->id)
			->get();

		if (count($_vacaciones) > 0) {
			$days_vac = 0;
			foreach ($_vacaciones as $key => $vacaciones) {
				$fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicio);
				$fecha_vaca_fin = Carbon::parse($vacaciones->fecha_fin);
				$days_vac = $days_vac + intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
			}
			return $days_vac;
		}
		return 0;
	}

	private function hasVacations($fecha_inicial, $fecha_final)
	{

		$_vacaciones = DB::table("vacaciones_periodo")
			->whereDate('fecha_inicio', '>=', $fecha_inicial)
			->whereDate('fecha_fin', '<=', $fecha_final)
			->where("periodo_id", $this->periodo()->id)
			->get();

		if (count($_vacaciones) > 0) {
			$days_vac = 0;
			foreach ($_vacaciones as $key => $vacaciones) {
				$fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicio);
				$fecha_vaca_fin = Carbon::parse($vacaciones->fecha_fin);
				$days_vac = $days_vac + intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
			}
			return ($days_vac);
		}

		return 0;
	}

	private function getVacations($fecha_inicial, $fecha_final)
	{
		$_vacaciones = DB::table("vacaciones_periodo")
			->whereDate('fecha_inicio', '<=', $fecha_inicial)
			->whereDate('fecha_fin', '<=', $fecha_final)
			->where("periodo_id", $this->periodo()->id)
			->orderBy("fecha_inicio", 'desc')
			->first();;
		if ($_vacaciones) {
			return $_vacaciones;
		}
		return $_vacaciones;
	}

	private function hasVacationsFin($fecha_inicial, $fecha_final)
	{

		$_vacaciones = DB::table("vacaciones_periodo")
			->whereDate('fecha_inicio', '>=', $fecha_inicial)
			->whereDate('fecha_fin', '<=', $fecha_final)
			->where("periodo_id", $this->periodo()->id)
			->first();;

		if ($_vacaciones) {
			$fecha_vaca_in = Carbon::parse($_vacaciones->fecha_inicio);
			$fecha_vaca_fin = Carbon::parse($_vacaciones->fecha_fin);
			$days_vac = $fecha_vaca_in->diffInDays($fecha_vaca_fin, false);
			return $days_vac;
		}
		return 0;
	}

	private function getDiasPausado($pausas, $fecha1, $fecha2)
	{
		$dias_pausa = 0;
		foreach ($pausas as $key_p => $pausa) {
			$fecha_pI = $pausa->fecha_inicial;
			$fecha_pF = $pausa->fecha_final;
			$dias_pausado = 0;
			$dias_vacaciones_p = 0;
			if ($key_p == 0) {

				//Validar vacaciones desde fecha anterior hasta inicio de pausas
				$_vacaciones = $this->vacacionesService->getByDates([
					['operador' => ">=", "value" => $fecha1],
					['operador' => "<=", "value" => $fecha_pI]
				]);
				$dias_vacaciones = 0;
				if (count($_vacaciones) > 0) {
					$dias_vacaciones = $this->vacacionesService->getDays($_vacaciones);
					$dias_vacaciones_p = $dias_vacaciones_p + $dias_vacaciones;
				}
			}

			//si esta en vacaciones el final de pausa
			$esta_vacaciones_pausa = $this->vacacionesService->getByDates([
				['operador' => "<=", "value" => $fecha_pF],
				['operador' => ">=", "value" => $fecha_pF]
			]);
			if (count($esta_vacaciones_pausa) > 0) {
				$dias_pausado = Carbon::parse($fecha_pI)
					->diffInDays($esta_vacaciones_pausa[0]->fecha_fin);
				if (isset($pausas[$key_p + 1])) {
					$fecha_next = $pausas[$key_p + 1]->fecha_inicial;
					//buscar vacaciones entre pausas
					$_vacaciones_ps = $this->vacacionesService->getByDates([
						['operador' => ">=", "value" => $esta_vacaciones_pausa[0]->fecha_fin],
						['operador' => "<=", "value" => $fecha_next]
					]);
					if (count($_vacaciones_ps) > 0) {
						$dias_vacaciones_p = $this->vacacionesService->getDays($_vacaciones_ps);
					}
				}
			} else {
				$dias_pausado = Carbon::parse($fecha_pI)
					->diffInDays($fecha_pF);
				//Log::info($pausas);
				if (isset($pausas[$key_p + 1])) {
					$fecha_next = $pausas[$key_p + 1]->fecha_inicial;
					//buscar vacaciones entre pausas
					$_vacaciones_ps = $this->vacacionesService->getByDates([
						['operador' => ">=", "value" => $fecha_pF],
						['operador' => "<=", "value" => $fecha_next]
					]);
					if (count($_vacaciones_ps) > 0) {
						$dias_vacaciones_p = $this->vacacionesService->getDays($_vacaciones_ps);
					}
				}
			}
			$dias_pausa = $dias_vacaciones_p + $dias_pausado;
		}
		//Validar vacaciones desde fecha ultima de pausas hasta la fecha 2
		$_vacaciones = $this->vacacionesService->getByDates([
			['operador' => ">=", "value" => $fecha_pF],
			['operador' => "<=", "value" => $fecha2]
		]);
		//$dias_vacaciones = 0;
		if (count($_vacaciones) > 0) {
			$dias_vacaciones = $this->vacacionesService->getDays($_vacaciones);
			//$dias_vacaciones_p = $dias_vacaciones_p - $dias_vacaciones;
		}
		$dias_pausa = $dias_pausa + $dias_vacaciones;
		Log::info("dias_pausa{$dias_pausa} Paso el {$dias_vacaciones}");

		return $dias_pausa;
	}
	private function isActuacionEval($array, $fecha_asig, $fecha_fin, $expediente)
	{
		$asignacion = AsignacionCaso::where([
			'asigexp_id' => $expediente->expid,
			'asigest_id' => $expediente->expidnumberest,
			'activo' => 1
		])->first();

		if ($asignacion->fecha_asig > $fecha_asig) {
			$fecha_asig = $asignacion->fecha_asig;
		}

		$act_i = new stdClass();
		$act_i->fechas = Carbon::parse($fecha_asig)->format("Y-m-d");
		array_unshift($array, $act_i);
		$act_i = new stdClass();
		$act_i->fechas = Carbon::parse($fecha_fin)->format("Y-m-d");
		array_push($array, $act_i);
		$dias_sin_act = 0;

		foreach ($array as $key => $fechacalc) {

			$fecha1 = Carbon::parse($fechacalc->fechas);
			$fecha2 = Carbon::parse($fecha_fin);
			if (array_key_exists($key + 1, $array)) {
				$fecha2 = Carbon::parse($array[$key + 1]->fechas);
			}

			$dias_sin_act =  Carbon::parse($fecha1)->diffInDays($fecha2);
			$mensaje = "No";

			if ($dias_sin_act > 31) {
				$fecha1 = $this->getFechaUno($asignacion, $fecha1);
				$fecha2 = $this->getFechaDos($asignacion, $fecha2);

				if ($fecha1 < $fecha2) {
					$dias_sin_act =  Carbon::parse($fecha1)->diffInDays($fecha2);

					if ($dias_sin_act > 31) {
						//si tuvo pausas en el lapso

						$dias_pausa = 0;
						$pausas = $this->pausaService->getByAsignacion($asignacion, [
							['operador' => ">=", "value" => $fecha1],
							['operador' => "<=", "value" => $fecha2]
						]);

						if (count($pausas) > 0) {
							//Se valida vacaciones desde fecha anterior hasta inicio de pausa
							//validar las pausas con vacaciones
							$dias_pausa = $this->getDiasPausado($pausas, $fecha1, $fecha2);
							//return $dias_pausa ;
							$dias_sin_act = $dias_sin_act - $dias_pausa;

							if ($dias_sin_act > 31) {
								$fecha_ini = getSmallDate($fecha1);
								$fecha_fini = getSmallDate($fecha2);
								$mensaje = "Periodo evaluado desde {$fecha_ini} hasta $fecha_fini. Se contaron pausas {$dias_pausa}. Días: {$dias_sin_act}";
								return [
									true,
									$mensaje
								];
							} else {
								if (array_key_exists($key + 1, $array)) {
									$array[$key + 1]->fechas = $fecha2;
								}
							}
						} else {
							//no tuvo pausas
							//Se verifica si tuvo vacaciones en el lapso
							//Si hubieron vacaciones en el lapso
							$_vacaciones = $this->vacacionesService->getByDates([
								['operador' => ">=", "value" => $fecha1],
								['operador' => "<=", "value" => $fecha2]
							]);
							$dias_vacaciones = 0;
							$mess = "";
							if (count($_vacaciones) > 0) {
								$dias_vacaciones = $this->vacacionesService->getDays($_vacaciones);
								$dias_sin_act = $dias_sin_act - $dias_vacaciones;
								$mess = "Se contaron vacaciones {$dias_vacaciones}";
							}

							if ($dias_sin_act > 31) {
								$fecha_ini = getSmallDate($fecha1);
								$fecha_fini = getSmallDate($fecha2);
								$mensaje = "Periodo evaluado desde {$fecha_ini} hasta $fecha_fini.{$mess} Días: {$dias_sin_act}";
								return [
									true,
									$mensaje
								];
							} else {
								if (array_key_exists($key + 1, $array)) {
									$array[$key + 1]->fechas = $fecha2;
								}
							}
						}
					} else {
						if (array_key_exists($key + 1, $array)) {
							$array[$key + 1]->fechas = $fecha2;
						}
					}
				} else {
					if (array_key_exists($key + 1, $array)) {
						$array[$key + 1]->fechas = $fecha2;
					}
				}
			} else {
				if (array_key_exists($key + 1, $array)) {
					$array[$key + 1]->fechas = $fecha2;
				}
			}
		}
		return [false];
	}

	private function getFechaUno($asignacion, $fecha1)
	{
		//si estaba pausado antes 
		$estaba_pausado = $this->pausaService->getByAsignacion($asignacion, [
			['operador' => "<=", "value" => $fecha1],
			['operador' => ">=", "value" => $fecha1]
		]);

		//si estaba en vacaciones antes 
		$estaba_en_vacaciones = $this->vacacionesService->getByDates([
			['operador' => "<=", "value" => $fecha1],
			['operador' => ">=", "value" => $fecha1]
		]);
		if (
			count($estaba_pausado) > 0
			and count($estaba_en_vacaciones) > 0
		) {
			$fecha1 = $estaba_en_vacaciones[0]->fecha_fin;
			if ($estaba_pausado[0]->fecha_final > $estaba_en_vacaciones[0]->fecha_fin) {
				$fecha1 = $estaba_pausado[0]->fecha_final;
			}
		} elseif (count($estaba_pausado) > 0) {
			$fecha1 = $estaba_pausado[0]->fecha_final;
		} elseif (count($estaba_en_vacaciones) > 0) {
			$fecha1 = $estaba_en_vacaciones[0]->fecha_fin;
		}
		return $fecha1;
	}
	private function getFechaDos($asignacion, $fecha2)
	{
		//La actuacion se venció mientras estaba pausada
		$esta_pausado = $this->pausaService->getByAsignacion($asignacion, [
			['operador' => "<=", "value" => $fecha2],
			['operador' => ">=", "value" => $fecha2]
		]);

		//Log::info($esta_pausado);
		//La actuacion se venció mientras estaba en vacaciones
		$esta_vacaciones = $this->vacacionesService->getByDates([
			['operador' => "<=", "value" => $fecha2],
			['operador' => ">=", "value" => $fecha2]
		]);

		if (count($esta_pausado) > 0 and count($esta_vacaciones) > 0) {
			$fecha2 = $esta_pausado[0]->fecha_inicial;
			if ($esta_vacaciones[0]->fecha_inicio < $esta_pausado[0]->fecha_inicial) {
				$fecha2 = $esta_vacaciones[0]->fecha_inicio;
			}
		} elseif (count($esta_pausado) > 0) {
			$fecha2 = $esta_pausado[0]->fecha_inicial;
		} elseif (count($esta_vacaciones) > 0) {
			$fecha2 = $esta_vacaciones[0]->fecha_inicio;
		}
		return $fecha2;
	}

	private function verMeses($a)
	{

		$f1 = new \DateTime($a[0]);
		$f2 = new \DateTime($a[1]);
		// obtener la diferencia de fechas
		$d = $f1->diff($f2);
		$difmes =  $d->format('%m');
		$fechas = [];
		$impf = $f1;
		for ($i = 1; $i <= $difmes; $i++) {
			// despliega los meses
			$impf->add(new \DateInterval('P1M'));
			$fechas[] =  $impf->format('d-m-Y');
		}
		return $fechas;
	}
	private function Asignotasnew($request)
	{

		Nota::create([

			'nota' => $request['ntaetica'], //cotrte1
			'cptnotaid' => 3, //competencia
			'orgntsid' => $request['orgntsid'], //expedientes
			'segid' => $request['segid'], //id tabla asignaciones
			'tpntid' => $request['tpntid'],
			'perid' => $request['perid'], //id tabla procedencia
			'estidnumber' => $request['estidnumber'],
			'expidnumber' => $request['expidnumber'],
			'docidnumber' => $request['docidnumber'],
			'tbl_org_id' => $request['tbl_org_id'],
		]);
		Nota::create([

			'nota' => $request['ntaconcepto'], //cotrte1
			'cptnotaid' => 4, //competencia
			'orgntsid' => $request['orgntsid'], //expedientes
			'segid' => $request['segid'], //id tabla asignaciones
			'tpntid' => $request['tpntid'],
			'perid' => $request['perid'], //id tabla procedencia
			'estidnumber' => $request['estidnumber'],
			'expidnumber' => $request['expidnumber'],
			'docidnumber' => $request['docidnumber'],
			'tbl_org_id' => $request['tbl_org_id'],
		]);
	}
	private function Asignotasnewdatos($request)
	{
		Log::info("Evaluado {$request['estidnumber']}");
		Log::info("Evaluado {$request['expidnumber']}");

		Nota::create([
    
			'nota' => $request['ntaconocimiento'], //cotrte1
			'cptnotaid' => 1, //competencia
			'orgntsid' => $request['orgntsid'], //expedientes
			'segid' => $request['segid'], //id tabla asignaciones
			'tpntid' => $request['tpntid'],
			'perid' => $request['perid'], //id tabla procedencia
			'estidnumber' => $request['estidnumber'],
			'expidnumber' => $request['expidnumber'],
			'docidnumber' => $request['docidnumber'],
			'tbl_org_id' => $request['tbl_org_id'],
		]);
		Nota::create([

			'nota' => $request['ntaaplicacion'], //cotrte1
			'cptnotaid' => 2, //competencia
			'orgntsid' => $request['orgntsid'], //expedientes
			'segid' => $request['segid'], //id tabla asignaciones
			'tpntid' => $request['tpntid'],
			'perid' => $request['perid'], //id tabla procedencia
			'estidnumber' => $request['estidnumber'],
			'expidnumber' => $request['expidnumber'],
			'docidnumber' => $request['docidnumber'],
			'tbl_org_id' => $request['tbl_org_id'],
		]);

		Nota::create([
			'nota' => $request['ntaetica'], //cotrte1
			'cptnotaid' => 3, //competencia
			'orgntsid' => $request['orgntsid'], //expedientes
			'segid' => $request['segid'], //id tabla asignaciones
			'tpntid' => $request['tpntid'],
			'perid' => $request['perid'], //id tabla procedencia
		]);
	}
}	
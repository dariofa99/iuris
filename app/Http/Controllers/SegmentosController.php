<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use DB;
use Session;
use App\Segmento;
use App\Actuacion;
use App\Requerimiento;
use App\Periodo;
use App\AsigDocenteCaso;
use Carbon\Carbon;
use App\Nota;
use App\HistorialDatosCaso;

class SegmentosController extends Controller
{
	public $periodo;

	public function periodo(){
		return Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
		->where('sp.sede_id', session('sede')->id_sede)
		->where('estado', true)
		->first();
	}

	function getSegmentos(){
			$segmentos = Segmento::join('periodo','periodo.id','=','segmentos.perid')
			->join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
			->where('sg.sede_id',session('sede')->id_sede)
			->select('segmentos.id','segmentos.segnombre','segmentos.act_fc','fecha_inicio','fecha_fin','segmentos.estado','periodo.prddes_periodo','segmentos.est_evaluado')
			->where('periodo.estado',true)->get();

			return $segmentos;


	}
		function getSegmento($request){
			$segmentos = Segmento::join('periodo','periodo.id','=','segmentos.perid')
			->join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
			->where('sg.sede_id',session('sede')->id_sede)
			->where('periodo.estado',true)			
			->select('segmentos.id','segmentos.segnombre','segmentos.act_fc','fecha_inicio','fecha_fin','segmentos.estado','periodo.prddes_periodo')
			->Criterio($request->data_search,$request->datatype)->get();

			return $segmentos;


	}

    public function index(Request $request){

    		$segmentos = $this->getSegmentos();
    		$periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
			->where('sp.sede_id',session('sede')->id_sede)
			->where('estado',true)->first();
    		$active_config = 'active';

    		if ($request->ajax()) {
    			if ($request->data_search and $request->datatype) {
    				$segmentos = $this->getSegmento($request);
    			}
    			if ($request->data_search and $request->data_search=='all') {
    				$segmentos = $this->getSegmentos();
    			}
    			return view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();
    		}else{
    			if ($request->data_search and $request->datatype) {
    				$segmentos = $this->getSegmento($request);
    			}else{
    				$segmentos = $this->getSegmentos();
    			}
    		}

			//dd($periodo);
    		return  view('myforms.frm_segmentos_list',compact('segmentos','periodo','active_config'));
	}

	public function store(Request $request){
		$segmento = new Segmento($request->all());
		//$regmento->segusercreated = \Auth::user()->idnumber;
		//$regmento->seguserupdated = \Auth::user()->idnumber;		
		$segmento->save();
		if(session('sede')){
			$segmento->sedes()->attach(session('sede')->id_sede);
		}
		$segmentos = $this->getSegmentos();
		return view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();
		//return response()->json($segmento);
	} 


	public function show($id){
		$segmento =  Segmento::find($id);
		return response()->json($segmento);

	}

	public function update(Request $request,$id){
		$segmento =  Segmento::find($id);
		$segmento->fill($request->all());
		$segmento->save();
		$segmentos = $this->getSegmentos();
		return view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();
		
	}

	public function destroy($id){
		$segmento =  Segmento::find($id);
		$segmento->delete();
		$segmentos = $this->getSegmentos();
		return view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();
	}

	public function changeState($id){

		$users= DB::table('segmentos')
		->join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
		->where('sg.sede_id',session('sede')->id_sede)
		->update(['estado'=>false,'act_fc'=>false]);           
		$segmento = Segmento::find($id);		
		$segmento->estado = true;
		$segmento->save();


		$segmentos = $this->getSegmentos();
		return view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();

		
	}

	public function change_state_segfc(Request $request){
		$segmentoact = Segmento:: join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
		->where('sg.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		if (empty($segmentoact)) {
			$response = [
				'success'=>false,
				'msj'=>'No hay un segmento de corte activo'
			];
		}else{
			if ($segmentoact->act_fc) {
				$segmentoact->act_fc = 0;
				$segmentoact->fecha_corte = null;
			}else{
				$segmentoact->act_fc = 1;
				$segmentoact->fecha_corte = date('Y-m-d');
			}
			
			$segmentoact->save();

			$response = [
				'success'=>true,
				'msj'=>'Correcto',
				'statusfc'=>$segmentoact->act_fc,
				'seg'=>$segmentoact->id
			];

		}


		return response()->json($response);

	}

	public function closeSegmento($id){

	
//-revisar los casos desde agosto hasta el 6 de febrero -> inicio corte hasta 15 dias antes del final del corte
//-un cero a los casos que no tengan docente asignado
//-un cero a los casos que no tengan informacion  en hechos o respuesta del estudiante
//-un cero a los casos de seguimiento que no tengan ninguna actuacion o anexo mayor al inicio del corte
//colocar cerro si de demoro mas de ocho dias en subir una actuacion o un anexo
// para los casos de seguimiento deben tener una actuacion o anexo 1 vez por mes
//revisar cuantos dias pasaron para llenar hechos y respuesta colocar cero si se demoro más de 5 dias
//revisar los casos de asesoria que estan abiertos pero requieren ser cerrados por el sistema
		
		$segmento = Segmento::find($id);
		//dd($segmento); 
		if($segmento->fecha_corte == null) {
			Session::flash('message-danger', 'Atención..! No hay una fecha de corte activa');
			return response()->json(['saved'=>false]);
		}
		

		$now = Carbon::now();


	
		$dateini = Carbon::parse($segmento->fecha_inicio);
		$dateiniciocorte = $dateini->format('Y-m-d 00:00:01');

		$date_segmento = Carbon::parse($segmento->fecha_fin);
		$datefinalcortereal = $date_segmento->format('Y-m-d 23:59:59');
		
		$date = $date_segmento->subDays(15);//se quitan 15 dias para evitar evaluacion a los casos asignados en los ultimos 15dias
		$datemenosquincediasfinalcorte = $date->format('Y-m-d 23:59:59');
		





		$meses = [$segmento->fecha_inicio,$datemenosquincediasfinalcorte];
		$mess = $this->verMeses($meses);
		
		
			/*
		$expedientes = DB::select(DB::Raw("Select asignacion_caso.fecha_asig,
		 expedientes.id , expid , expedientes.expidnumberest, if(expedientes.exphechos!='',1,0) as exphechos, if(expedientes.exprtaest!='',1,0) as exprtaest, asignacion_caso.id as asig_caso_id, exptipoproce_id from expedientes
		 join asignacion_caso on asignacion_caso.asigexp_id = expedientes.expid
		 where expedientes.expidnumberest = asignacion_caso.asigest_id and (expestado_id != 5 and expestado_id != 2) and fecha_asig <= '".$datesql."'"));
        */

		//consulta sobre todos los casos asignados antes del corte
		//Estos casos son viejos
		 $expedientes = DB::select(DB::Raw("Select asignacion_caso.fecha_asig,
		 expedientes.id , expid , expedientes.expidnumberest, if(expedientes.exphechos!='',1,0) as exphechos, if(expedientes.exprtaest!='',1,0) as exprtaest, asignacion_caso.id as asig_caso_id, exptipoproce_id, expestado_id from expedientes
		 join asignacion_caso on asignacion_caso.asigexp_id = expedientes.expid join `users` on expedientes.expidnumberest = users.idnumber join sede_expedientes on expedientes.id = sede_expedientes.expediente_id
		 where expedientes.expidnumberest = asignacion_caso.asigest_id and (expestado_id != 5 and expestado_id != 2) 
		 and expedientes.expidnumberest = '1085947437'
		 and asignacion_caso.activo = 1 and fecha_asig < '".$dateiniciocorte."' and sede_expedientes.sede_id=".session('sede')->id_sede ));
		 
	
// and expedientes.expidnumberest = '1085947437'

		 $docente_id = \Auth::user()->idnumber;
		 $exps=[];
		

		foreach ($expedientes as $key => $expediente) {	
			$expediente = $expedientes[3];
			if (($expediente->exptipoproce_id == 3 and $expediente->exphechos == 0) || ($expediente->exptipoproce_id != 3 and ($expediente->exphechos == 0 || $expediente->exprtaest == 0 ))) {
				
				
				//se registra un cero cuando no tiene informacion en datos del caso
				$data = [ 
					'ntaaplicacion'=>0,
					'ntaconocimiento'=>0,
					'ntaetica'=>0,   
					'ntaconcepto'=>'Sin información de hechos o respuesta del caso. ',
					'orgntsid'=>4,
					'segid'=>$segmento->id,
					'perid'=>$segmento->perid,
					'tpntid'=>2,
					'expidnumber'=>$expediente->expid,
					'estidnumber'=>$expediente->expidnumberest,
					'docidnumber'=>$docente_id,
					'tbl_org_id'=>$expediente->id,
				  ]; 
				  
				// $this->Asignotasnewdatos($data);
				 
			}else{
				
				//cuanto tiempo paso al llenar la informacion desde asignado el caso
				$historial =
				 $historial =  HistorialDatosCaso::where('hisdc_expidnumber',$expediente->expid)
				//->join('users', 'users.idnumber','=','historial_datos_casos.hisdc_idnumberest_id')
				//->join('asignacion_caso', 'asignacion_caso.asigexp_id','=','historial_datos_casos.hisdc_expidnumber')
				->select('historial_datos_casos.created_at')
				//->whereDate('fecha_asig', '>=', $dateiniciocorte)
				->where('hisdc_idnumberest_id',$expediente->expidnumberest)
				->orderBy('historial_datos_casos.created_at', 'ASC')
				->first();				
				

				if ($historial) {
					$datehistor=Carbon::parse($historial->created_at);
					$dateasig=Carbon::parse($expediente->fecha_asig);					
					if ($dateasig->diffInDays($datehistor) > 5) {
						
						//Se evalua que no la haya hecho en vacaciones
						$vacations_days_r = $this->hasVacations($datehistor,$datehistor);
						$expedientemodel = Expediente::where('expid', $expediente->expid)->first();
						$notas =  $expedientemodel->get_has_nota_final();
						///return response()->json([$vacations_days_r,"465",$notas]);

						if($vacations_days_r<=0){
							//Se evalua que hayan habido vacaciones
							$vacations_days = $this->hasVacationsNext($dateasig,$datehistor);							
							$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($datehistor);
								
							if($vacations_days>0){						
								$vacaciones = $this->getVacations($datehistor,$datehistor);
								$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($vacaciones->fecha_inicio);
								$dias_sinhechos = $dias_sinhechos + Carbon::parse($vacaciones->fecha_fin)->diffInDays($datehistor);
							}

		

							if($dias_sinhechos>5){
										
							$data = [ 
								'ntaaplicacion'=>0,
								'ntaconocimiento'=>0,
								'ntaetica'=>0,   
								'ntaconcepto'=>'Demora en colocar información en datos del caso. '.date('Y-m-d'),
								'orgntsid'=>4,
								'segid'=>$segmento->id,
								'perid'=>$segmento->perid,
								'tpntid'=>2,
								'expidnumber'=>$expediente->expid,
								'estidnumber'=>$expediente->expidnumberest,
								'docidnumber'=>$docente_id,
								'tbl_org_id'=>$expediente->id,
							]; 
			
						//	$this->Asignotasnewdatos($data);
			
							}
						}
						
					}
				}
				
			} 

			

			$asigdocencaso=AsigDocenteCaso::where(['asig_caso_id'=> $expediente->asig_caso_id, 'activo'=>'1'])
			->first();
			if(!$asigdocencaso){
				$data = [ 
					'ntaaplicacion'=>0,
					'ntaconocimiento'=>0,
					'ntaetica'=>0,   
					'ntaconcepto'=>'No tiene docente asignado '.date('Y-m-d'),
					'orgntsid'=>4,
					'segid'=>$segmento->id,
					'perid'=>$segmento->perid,
					'tpntid'=>2,
					'expidnumber'=>$expediente->expid,
					'estidnumber'=>$expediente->expidnumberest,
					'docidnumber'=>$docente_id,
					'tbl_org_id'=>$expediente->id,
				  ]; 
				// $this->Asignotasnewdatos($data);
				// return response()->json("Docente");
				
			}
			//Evalua actuaciones
			
			if($expediente->exptipoproce_id != '1'){
				
				$actuaciones = Actuacion::where('actexpid',$expediente->expid)
				->where('actidnumberest',$expediente->expidnumberest)
				->whereDate('actfecha','<=',$datefinalcortereal)
				->limit(1)->get();	
				
				
					if(count($actuaciones)<=0){
							$data = [ 
								'ntaaplicacion'=>0,
								'ntaconocimiento'=>0,
								'ntaetica'=>0,   
								'ntaconcepto'=>'No tiene actuaciones '.date('Y-m-d'),
								'orgntsid'=>4,
								'segid'=>$segmento->id,
								'perid'=>$segmento->perid,
								'tpntid'=>2,
								'expidnumber'=>$expediente->expid,
								'estidnumber'=>$expediente->expidnumberest,
								'docidnumber'=>$docente_id,
								'tbl_org_id'=>$expediente->id,
							  ]; 
			
							//$this->Asignotasnewdatos($data);						
					}else{

						//Se verifica si se realizaron actuaciones cada mes en los casos viejos
						// durante el corte evaluado

						$actuacionsmes = DB::select(DB::Raw("SELECT date_format(actfecha, '%Y-%m-%d') as fechas 
						FROM actuacions
						 WHERE actexpid = '".$expediente->expid."' 						 
						 AND actestado_id  <> '235' 
						 AND actidnumberest = '".$expediente->expidnumberest."' 
						 AND actusercreated = '".$expediente->expidnumberest."' 
						 AND (actfecha >= '".$dateiniciocorte."' 
						 AND  actfecha <= '".$datefinalcortereal."') 
						 GROUP BY 1 ORDER BY 1 ASC"));

						 //$iseva = $this->isActuacionEval($actuacionsmes,$dateiniciocorte,$segmento->fecha_fin);

						// return response()->json([$iseva,'sjsj',$actuacionsmes]);
						$res_act = $this->isActuacionEval($actuacionsmes,$dateiniciocorte,$segmento->fecha_fin);
						
						return response()->json([$res_act,$actuacionsmes,$expediente]); 

						if($res_act[0]){
							$data = [ 
								'ntaaplicacion'=>0, 
								'ntaconocimiento'=>0,
								'ntaetica'=>0,   
								'ntaconcepto'=>'No tiene actuaciones o anexos requeridos en más 30 días, requeridos a lo largo del corte. '.$res_act[1], //cambiado el texto
								'orgntsid'=>4,
								'segid'=>$segmento->id,
								'perid'=>$segmento->perid,
								'tpntid'=>2,
								'expidnumber'=>$expediente->expid,
								'estidnumber'=>$expediente->expidnumberest,
								'docidnumber'=>$docente_id,
								'tbl_org_id'=>$expediente->id,
							  ]; 
			
							// $this->Asignotasnewdatos($data);
						}	
					}
					

			} 
			
			if($expediente->exptipoproce_id ==  1) {
				
				$expedientemodel=Expediente::where('expid', $expediente->expid)->first();
				$notas =  $expedientemodel->get_has_nota_final();
				$days = $expedientemodel->getDaysOrColorForClose('dias',$datefinalcortereal);
				
				//return response()->json([$datefinalcortereal,$days]);

				if($days<=0 || $days===true) {   
				
					
				if($expedientemodel->expestado_id != 5 AND $expedientemodel->expestado_id != 2){
				$notas =  $expedientemodel->get_has_nota_final();
			   
				if (count($notas) <= 0) {
				  $data = [
						  'ntaaplicacion'=>0,
						  'ntaconocimiento'=>0,
						  'ntaetica'=>0,
						  'ntaconcepto'=>'Evaluado por el sistema - Tiempo 30 días agotado',
						  'orgntsid'=>'4',
						  'segid'=>$segmento->id,
						  'perid'=>$segmento->perid,
						  'tpntid'=>'1',
						  'expidnumber'=>$expedientemodel->expid,
						  'estidnumber'=>$expedientemodel->expidnumberest,
						  'docidnumber'=>\Auth::user()->idnumber, 
						  'tbl_org_id'=>$expedientemodel->id, 
						]; 
						//$expedientemodel->asignarNotas($data);
						$expedientemodel->expestado_id = 5;
						//$expedientemodel->save();
				}
				}
			  }		
			  }
		}

		
		///////////////fin foreach todos los casos


		//consulta sobre los casos asignados solo durante el corte para notas sobre tiempos limites de inicio
		$expedientescorte = DB::select(DB::Raw("Select asignacion_caso.fecha_asig,
		expedientes.id , expid , expedientes.expidnumberest, if(expedientes.exphechos!='',1,0) as exphechos, if(expedientes.exprtaest!='',1,0) as exprtaest, asignacion_caso.id as asig_caso_id, exptipoproce_id, expestado_id from expedientes
		join asignacion_caso on asignacion_caso.asigexp_id = expedientes.expid join `users` on expedientes.expidnumberest = users.idnumber join sede_expedientes on expedientes.id = sede_expedientes.expediente_id
		where expedientes.expidnumberest = asignacion_caso.asigest_id and (expestado_id != 5 and expestado_id != 2)
		and expedientes.expidnumberest = '1085947437'
		and (fecha_asig >= '".$dateiniciocorte."' and fecha_asig <= '".$datemenosquincediasfinalcorte."') 
		and sede_expedientes.sede_id=".session('sede')->id_sede ));

		
	
//and expedientes.expidnumberest = '1006106455'

		
		foreach ($expedientescorte as $key => $expediente) {
			//$expediente = $expedientescorte[2];
			if (($expediente->exptipoproce_id == 3 and $expediente->exphechos == 0) || ($expediente->exptipoproce_id != 3 and ($expediente->exphechos == 0 || $expediente->exprtaest == 0 ))) {

					$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($datefinalcortereal);
					// se registra un cero cuando no tiene informacion en datos del caso				
					if($dias_sinhechos>5){
					$fecha_vence = Carbon::parse($expediente->fecha_asig)->addDays(5);
					//si vence en vacaciones
					$vacations_days = $this->hasVacations($fecha_vence,$fecha_vence);
					if($vacations_days>0){						
						$vacaciones = $this->getVacations($fecha_vence,$datefinalcortereal);
						$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($vacaciones->fecha_inicio);
						$dias_sinhechos = $dias_sinhechos + Carbon::parse($vacaciones->fecha_fin)->diffInDays($datefinalcortereal);
						if($vacaciones)$vacations_days = $this->hasVacationsNext($vacaciones->fecha_fin,$datefinalcortereal);
						
					}
					if($dias_sinhechos>5){
						//se obtienen los dias de vacaciones desde el fin de las vacaciones hasta el cierre de corte;
						$dias_sinhechos = $dias_sinhechos -$vacations_days;

					}
					if($dias_sinhechos>5){
						$data = [ 
							'ntaaplicacion'=>0,
							'ntaconocimiento'=>0,
							'ntaetica'=>0,   
							'ntaconcepto'=>'Sin información de hechos o respuesta del caso  '.date('Y-m-d'),
							'orgntsid'=>4,
							'segid'=>$segmento->id,
							'perid'=>$segmento->perid,
							'tpntid'=>2,
							'expidnumber'=>$expediente->expid,
							'estidnumber'=>$expediente->expidnumberest,
							'docidnumber'=>$docente_id,
							'tbl_org_id'=>$expediente->id,
						  ];
						 // $this->Asignotasnewdatos($data);
					}			
				}
				
			} else {
				//cuanto tiempo paso al llenar la informacion desde asignado el caso
				$historial = HistorialDatosCaso::where('hisdc_expidnumber',$expediente->expid)
				//->join('users', 'users.idnumber','=','historial_datos_casos.hisdc_idnumberest_id')
				//->join('asignacion_caso', 'asignacion_caso.asigexp_id','=','historial_datos_casos.hisdc_expidnumber')
				->select('historial_datos_casos.created_at')
				//->whereDate('fecha_asig', '>=', $dateiniciocorte)
				->where('hisdc_idnumberest_id',$expediente->expidnumberest)
				->orderBy('historial_datos_casos.created_at', 'ASC')
				->first();				
				

				if ($historial) {
					$datehistor=Carbon::parse($historial->created_at);
					$dateasig=Carbon::parse($expediente->fecha_asig);					
					if ($dateasig->diffInDays($datehistor) > 5) {
						//Se evalua que no la haya hecho en vacaciones
						$vacations_days_r = $this->hasVacations($datehistor,$datehistor);
						if($vacations_days_r<=0){
							//Se evalua que hayan habido vacaciones
							$vacations_days = $this->hasVacationsNext($dateasig,$datehistor);							
							$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($datehistor);
								
							if($vacations_days>0){						
								$vacaciones = $this->getVacations($datehistor,$datehistor);
								$dias_sinhechos = Carbon::parse($expediente->fecha_asig)->diffInDays($vacaciones->fecha_inicio);
								$dias_sinhechos = $dias_sinhechos + Carbon::parse($vacaciones->fecha_fin)->diffInDays($datehistor);
							}

						/* 	return response()->json([
								$dias_sinhechos,
							$vacations_days,
							Carbon::parse($vacaciones->fecha_fin)->diffInDays($datehistor),
							Carbon::parse($expediente->fecha_asig)->diffInDays($vacaciones->fecha_inicio)]);
 */

						if($dias_sinhechos>5){
									
						$data = [ 
							'ntaaplicacion'=>0,
							'ntaconocimiento'=>0,
							'ntaetica'=>0,   
							'ntaconcepto'=>'Demora en colocar información en datos del caso  '.$dateasig."***".$datehistor,
							'orgntsid'=>4,
							'segid'=>$segmento->id,
							'perid'=>$segmento->perid,
							'tpntid'=>2,
							'expidnumber'=>$expediente->expid,
							'estidnumber'=>$expediente->expidnumberest,
							'docidnumber'=>$docente_id,
							'tbl_org_id'=>$expediente->id,
						  ]; 
		
						// $this->Asignotasnewdatos($data);
		
						}
						}
						
					}
				}

			}

			$asigdocencaso=AsigDocenteCaso::where(['asig_caso_id'=> $expediente->asig_caso_id, 'activo'=>'1'])->first();
			if(!$asigdocencaso){
				$data = [ 
					'ntaaplicacion'=>0,
					'ntaconocimiento'=>0,
					'ntaetica'=>0,   
					'ntaconcepto'=>'No tiene docente asignado '.date('Y-m-d'),
					'orgntsid'=>4,
					'segid'=>$segmento->id,
					'perid'=>$segmento->perid,
					'tpntid'=>2,
					'expidnumber'=>$expediente->expid,
					'estidnumber'=>$expediente->expidnumberest,
					'docidnumber'=>$docente_id,
					'tbl_org_id'=>$expediente->id,
				  ]; 
				  
				// $this->Asignotasnewdatos($data);
				// 
			}
			

			if($expediente->exptipoproce_id != '1' and $key==0){
				
				$actuaciones = Actuacion::where([
					['actexpid',$expediente->expid],					
					['actidnumberest',$expediente->expidnumberest]				
					])
					->whereDate('actfecha','>',$dateiniciocorte)
					->whereDate('actfecha','<',$datefinalcortereal)
					->get() ;

					
					
					if(count($actuaciones)<=0){
						$fechaasig = Carbon::parse($expediente->fecha_asig);//revisar
						// Se evalua ha hayan vacaciones
						$vacaciones = $this->hasVacationsNext($fechaasig,$segmento->fecha_fin);
						$dias_sin_act = intval($fechaasig->diffInDays(Carbon::parse($segmento->fecha_fin))) - $vacaciones ;
						//return response()->json([$actuaciones,$fechaasig]); 
						
						if($dias_sin_act > 30) {//revisar   (si el caso no tiene actuaciones entonces verifica que entre la asignacion y el inicio del corte hayan pasado los 30 para colocar 0)
						$data = [ 
							'ntaaplicacion'=>0,
							'ntaconocimiento'=>0,
							'ntaetica'=>0,   
							'ntaconcepto'=>'No tiene actuaciones '.date('Y-m-d'),
							'orgntsid'=>4,
							'segid'=>$segmento->id,
							'perid'=>$segmento->perid,
							'tpntid'=>2,
							'expidnumber'=>$expediente->expid,
							'estidnumber'=>$expediente->expidnumberest,
							'docidnumber'=>$docente_id,
							'tbl_org_id'=>$expediente->id,
						  ]; 		
						//$this->Asignotasnewdatos($data);
						 
						}
					} else {
						//tiene actuaciones por cada mes?
					$actuacionsmes = DB::select(DB::Raw("SELECT date_format(actfecha, '%Y-%m-%d') as fechas 
						FROM actuacions
						 WHERE actexpid = '".$expediente->expid."' 
							 AND actestado_id  <> '235' 
							 AND actidnumberest = '".$expediente->expidnumberest."' 
							 AND actusercreated = '".$expediente->expidnumberest."' 
						 
						 AND (actfecha >= '".$dateiniciocorte."' 
						 AND  actfecha <= '".$datefinalcortereal."') 
						 GROUP BY 1 ORDER BY 1 ASC"));


					//

					//$iseva = $this->isActuacionEval($actuacionsmes,$expediente->fecha_asig,$segmento->fecha_fin);

					$res_act = $this->isActuacionEval($actuacionsmes,$expediente->fecha_asig,$segmento->fecha_fin);

					//return response()->json([$expedientescorte,$actuacionsmes,$key,$expediente->expid]); 

					if($res_act[0]){
						$data = [ 
							'ntaaplicacion'=>0,
							'ntaconocimiento'=>0,
							'ntaetica'=>0,   
							'ntaconcepto'=>'No tiene actuaciones o anexos requeridos en más 30 días, requeridos a lo largo del corte. '.$res_act[1], //cambiado el texto
							'orgntsid'=>4,
							'segid'=>$segmento->id,
							'perid'=>$segmento->perid,
							'tpntid'=>2,
							'expidnumber'=>$expediente->expid,
							'estidnumber'=>$expediente->expidnumberest,
							'docidnumber'=>$docente_id,
							'tbl_org_id'=>$expediente->id,
						  ]; 
		
						// $this->Asignotasnewdatos($data);
					}					
					
					//cuanto se demoro al subir la primera actuacion o anexo
					
					}
					



			} 

			if($expediente->exptipoproce_id ==  1) {
				$expedientemodel=Expediente::where('expid', $expediente->expid)->first();
				$days = $expedientemodel->getDaysOrColorForClose('dias',$datefinalcortereal);
			  
				if($days<=0 || $days===true) {   
				 
				if($expedientemodel->expestado_id != 5 AND $expedientemodel->expestado_id != 2){
				$notas =  $expedientemodel->get_has_nota_final();
			   
				if (count($notas) <= 0) {
				  $data = [
						  'ntaaplicacion'=>0,
						  'ntaconocimiento'=>0,
						  'ntaetica'=>0,
						  'ntaconcepto'=>'Evaluado por el sistema - Tiempo 30 días agotado',
						  'orgntsid'=>'4',
						  'segid'=>$segmento->id,
						  'perid'=>$segmento->perid,
						  'tpntid'=>'1',
						  'expidnumber'=>$expedientemodel->expid,
						  'estidnumber'=>$expedientemodel->expidnumberest,
						  'docidnumber'=>\Auth::user()->idnumber, 
						  'tbl_org_id'=>$expedientemodel->id, 
						]; 
						//$expedientemodel->asignarNotas($data);
						$expedientemodel->expestado_id = 5;
						//$expedientemodel->save();
				}
				}
			  }
		
		
		
			  }

			
		}


		//return response()->json([$expedientescorte]);
			//$segmento->est_evaluado = 1;
      		//$segmento->save();
			$segmentos = $this->getSegmentos();
			$view = view('myforms.frm_segmentos_list_ajax',compact('segmentos'))->render();
			return response()->json(['saved'=>true,'view'=>$view]);
		//return redirect()->back();

	}

	private function hasVacationsNext($fecha_inicial,$fecha_final){
		
		$_vacaciones = DB::table("vacaciones_periodo")            
		->whereDate('fecha_inicio','>=',$fecha_inicial)
		->whereDate('fecha_fin','<=',$fecha_final)
		->where("periodo_id",$this->periodo()->id)
		->get();
		if(count($_vacaciones)>0){
			$days_vac = 0;
			foreach ($_vacaciones as $key => $vacaciones) {
				$fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicio);
				$fecha_vaca_fin = Carbon::parse($vacaciones->fecha_fin);
				$days_vac = $days_vac + intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
			}
			return $days_vac ;
		}
		return 0 ;
	}

	private function hasVacations($fecha_inicial,$fecha_final){
		
		$_vacaciones = DB::table("vacaciones_periodo")            
		->whereDate('fecha_inicio','<=',$fecha_inicial)
		->whereDate('fecha_fin','>=',$fecha_final)
		->where("periodo_id",$this->periodo()->id)
		->get();

		if(count($_vacaciones)>0){
			$days_vac = 0;
			foreach ($_vacaciones as $key => $vacaciones) {
				$fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicio);
				$fecha_vaca_fin = Carbon::parse($vacaciones->fecha_fin);
				$days_vac = $days_vac + intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
			}
			return ($days_vac) ;
		}
		
		return 0 ;
	}
	
	private function getVacations($fecha_inicial,$fecha_final){
	   $_vacaciones = DB::table("vacaciones_periodo")            
		->whereDate('fecha_inicio','<=',$fecha_inicial)
		->whereDate('fecha_fin','<=',$fecha_final)
		->where("periodo_id",$this->periodo()->id)
		->orderBy("fecha_inicio",'desc')
		->first();;	
		if($_vacaciones){			
			return $_vacaciones ;
		}
		return $_vacaciones ;
	}

	private function hasVacationsFin($fecha_inicial,$fecha_final){
		
		$_vacaciones = DB::table("vacaciones_periodo")            
		->whereDate('fecha_inicio','>=',$fecha_inicial)
		->whereDate('fecha_fin','<=',$fecha_final)
		->where("periodo_id",$this->periodo()->id)
		->first();;	

		if($_vacaciones){
			$fecha_vaca_in = Carbon::parse($_vacaciones->fecha_inicio);
            $fecha_vaca_fin = Carbon::parse($_vacaciones->fecha_fin);
            $days_vac = $fecha_vaca_in->diffInDays($fecha_vaca_fin, false);
			return $days_vac ;
		}
		return 0 ;

	}

private function isActuacionEval($array,$fecha_asig,$fecha_fin){
	$fecha_fin = Carbon::parse($fecha_fin);
	$fechaasig = Carbon::parse($fecha_asig);
	$fechafinalcorte = $fecha_fin;

	


	foreach ($array as $key => $fechacalc) {

		
	  $fecha1 = Carbon::parse($fechacalc->fechas);	 
	  $end_fecha = Carbon::parse($fechaasig)->addDays(31); 
	  $dias_sin_act = Carbon::parse($fechaasig)->diffInDays($fecha1);
	  
	

	


	  if ($dias_sin_act > 31) {		
		 $vacations_days = $this->hasVacations($end_fecha,$end_fecha);
		 
		//Si la actuacion se vence en vacaciones		
		if($vacations_days > 0){ 
			$vacations = $this->getVacations($end_fecha,$fechafinalcorte);
			//if($key == 1) return " nones ".$fechaasig->diffInDays($fecha1)."  ".$end_fecha;
			$v_date_fin = Carbon::parse($vacations->fecha_fin);
			//Si la actuacion se hizo en vacaciones 
			$vacations_days_r = $this->hasVacations($fecha1,$fecha1);
			if($vacations_days_r>0){	
				//Si no tiene mas actuaciones
				if (!array_key_exists($key+1,$array)) {					
					$dias_sin_act = $v_date_fin->diffInDays($fechafinalcorte);
					if($dias_sin_act>31){
						//SI hay vacaciones
						$vacations_days_r = $this->hasVacationsNext($v_date_fin,$fechafinalcorte);
						$dias_sin_act = $dias_sin_act - $vacations_days_r;
						if($dias_sin_act>30){

							return [
								true,
								"Período evaluado desde fin de vacaciones hasta final de corte: <b> ".Carbon::parse($vacations->fecha_fin)->format('Y-m-d')." hasta ".Carbon::parse($fechafinalcorte)->format('Y-m-d').".</b> ".$dias_sin_act." Días"
								
							];
						} ;//$v_date_fin." *se evalua 713* ".$dias_sin_act."--**--".$vacations_days_r;
					}
					
				}
				
			}else{
				//si no se hizo en vacaciones
				$dias_sin_act = Carbon::parse($v_date_fin)->diffInDays($fecha1);
							
				if($dias_sin_act>31){
					//Se evalua que hayan vacaciones	
					$vacations_days = $this->hasVacationsNext($v_date_fin,$fecha1);					
					$dias_sin_act = ($dias_sin_act) - $vacations_days;

					if($dias_sin_act>31) {
						return [
							true,
							"Período evaluado desde final de vacaciones:<b> ".Carbon::parse($v_date_fin)->format('Y-m-d')." hasta ".Carbon::parse($fecha1)->format('Y-m-d').".</b> ".$dias_sin_act." Días"
							
						];
					}// $key." *se evalua 727* ".$dias_sin_act;
					//return $vacations_days." *se evalua 727* ".$dias_sin_act."***".$v_date_fin;
				}
				if (!array_key_exists($key+1,$array)) {								 
					$dias_sin_act = Carbon::parse($fecha1)->diffInDays($fechafinalcorte);
					if($dias_sin_act>31){
						$vacations_days = $this->hasVacationsNext($fecha1,$fechafinalcorte);					
						$dias_sin_act = ($dias_sin_act) - $vacations_days;
						if($dias_sin_act>31) {
							return [
								true,
								"Período evaluado desde última actuación hasta final de corte:<b> ".Carbon::parse($fecha1)->format('Y-m-d')." hasta ".Carbon::parse($fechafinalcorte)->format('Y-m-d').".</b> ".$dias_sin_act." Días."
								
							];	
						}//$vacations_days." *se evalua 732* ".$dias_sin_act;
					}
				}				
				
			}

		}else{
			//Si la actuacion anterior se hizo en vacaciones 
			$vacations_days_r = $this->hasVacations($fechaasig,$fechaasig);
			if($vacations_days_r>0){
				$vacations = $this->getVacations($fechaasig,$fecha1);			
				$v_date_fin = Carbon::parse($vacations->fecha_fin);				
				$dias_sin_act = $v_date_fin->diffInDays($fecha1);
				if($dias_sin_act>31){
					//Si la actuacion no se hizo en vacaciones 
					$vacations_days_r = $this->hasVacations($fecha1,$fecha1);					
					if($vacations_days_r<=0) {
						return [
							true,
							"Período evaluado desde final de vacaciones: <b>".Carbon::parse($v_date_fin)->format('Y-m-d')." hasta ".Carbon::parse($fecha1)->format('Y-m-d').".</b> ".$dias_sin_act."  Días."					
						];
					}
				}
					
			}else{
				//Si fue antes de vacaciones o no hay vacaciones
				$dias_sin_act = Carbon::parse($fechaasig)->diffInDays($fecha1);			
				if($dias_sin_act>31){
					$vacations_days = $this->hasVacationsNext($fechaasig,$fecha1);
					$dias_sin_act = $dias_sin_act - $vacations_days;
					if($dias_sin_act>31) {
						return [
							true,
							"Período evaluado desde: <b>".Carbon::parse($fechaasig)->format('Y-m-d')." hasta ".Carbon::parse($fecha1)->format('Y-m-d').".</b>".$dias_sin_act." Días."					
						];
					}// $key." *se evalua 752* ".$dias_sin_act . "**".$fechaasig."-*-".$vacations_days;
				}
			}
			 
		}
	  }else{
		//Si no esta vencida
		//Si no tiene mas actuaciones
		if (!array_key_exists($key+1,$array)) {				
			//Si la ultima actuacion se hizo en vacaciones 			
			$vacations_days_r = $this->hasVacations($fecha1,$fecha1);
			if($vacations_days_r>0){
				$vacations = $this->getVacations($fecha1,$fechafinalcorte);			
				$v_date_fin = Carbon::parse($vacations->fecha_fin);				
				$dias_sin_act = $v_date_fin->diffInDays($fechafinalcorte);
				if($dias_sin_act>31){
					//Si no hay mas vacaciones					 			
					$vacations_days_r = $this->hasVacationsNext($v_date_fin,$fechafinalcorte);
					$dias_sin_act = $dias_sin_act - $vacations_days_r;
					if($dias_sin_act>31){
						return [
							true,
							"Período evaluado desde fin vacaciones hasta final corte:<b> ".Carbon::parse($v_date_fin)->format('Y-m-d')." hasta ".Carbon::parse($fechafinalcorte)->format('Y-m-d').". </b>".$dias_sin_act." Días."				
						];
					}// $vacations_days_r." *se evalua 770* ".$dias_sin_act. " ".$v_date_fin;
				}
				
			}else{
				$dias_sin_act = Carbon::parse($fecha1)->diffInDays($fechafinalcorte);
				if($dias_sin_act>31){
					//Se evalua que no hayan vacaciones					
					$vacations_days_r = $this->hasVacationsNext($fecha1,$fechafinalcorte);
					//$dias_sin_act = $dias_sin_act - $vacations_days_r;					
					if($dias_sin_act>31){
						return [
							true,
							"Período evaluado desde la asignación: ".$fechaasig." hasta ".$fecha1.". ".$dias_sin_act." Días"				
						];
					}// $dias_sin_act." *se evalua 795* ".$vacations_days_r;
					
				}
			}
		}
	  }
	  if (array_key_exists($key+1,$array)) {
		$fechaasig = $fecha1;				
	  } 
	 
	}
	return [false];
}

	private function verMeses($a){

		$f1 = new \DateTime( $a[0] );
		$f2 = new \DateTime( $a[1] );	 	 
		// obtener la diferencia de fechas
		$d = $f1->diff($f2);
		$difmes =  $d->format('%m');
		$fechas = [];	 
		$impf = $f1;
		for($i = 1; $i <= $difmes; $i++){
			// despliega los meses
			$impf->add(new \DateInterval('P1M'));
			$fechas[] =  $impf->format('d-m-Y');
		}
		return $fechas;
	 }
	 private function Asignotasnew($request){

		Nota::create([
                                    
			'nota'=>$request['ntaetica'], //cotrte1
			'cptnotaid'=>3, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
		 'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);
Nota::create([
			
			'nota'=>$request['ntaconcepto'], //cotrte1
			'cptnotaid'=>4, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
			'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);
	 } 
	 private function Asignotasnewdatos($request){
		Nota::create([
                                    
			'nota'=>$request['ntaconocimiento'], //cotrte1
			'cptnotaid'=>1, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
		 'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);
		Nota::create([
                                    
			'nota'=>$request['ntaaplicacion'], //cotrte1
			'cptnotaid'=>2, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
		 'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);

		 Nota::create([
                                    
			'nota'=>$request['ntaetica'], //cotrte1
			'cptnotaid'=>3, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
		 'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);
Nota::create([
			
			'nota'=>$request['ntaconcepto'], //cotrte1
			'cptnotaid'=>4, //competencia
			'orgntsid'=> $request['orgntsid'], //expedientes
			'segid'=> $request['segid'],//id tabla asignaciones
			'tpntid'=> $request['tpntid'],
			'perid'=> $request['perid'],//id tabla procedencia
			'estidnumber'=> $request['estidnumber'],                                    
			'expidnumber'=> $request['expidnumber'],
			'docidnumber'=> $request['docidnumber'],
			'tbl_org_id'=> $request['tbl_org_id'],
		 ]);
	 } 
	 


}

<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\RamaDerecho;
use App\Cptonota;
use App\Segmento;
use App\MotivoEstadoCaso;
use App\Estado;
use App\ReqAsistencia;
use App\Periodo;
use App\RefAsignacionCaso;
use App\MotivoAsigCaso;
use App\ReferencesData;
use App\Services\PeriodosService;
use App\Services\ReferenciasService;
use App\Services\SegmentosService;
use DB;
use Illuminate\Support\Facades\App;

/**
 * 
 */
class ExpedientesComposer
{

	private $referenciasService;
	private $segmentosService;
	private $periodosService;

	public function __construct()
	{
		
		$this->referenciasService = App::make(ReferenciasService::class);
		$this->segmentosService = App::make(SegmentosService::class);
		$this->periodosService = App::make(PeriodosService::class);
	}


	public function compose(View $view)
	{
		
		//$reframa_derecho = $this->referenciasService->getRamasDerechoForExpediente();
		$estados = $this->referenciasService->getEstadosForExpediente();
		$tipo_proceso = $this->referenciasService->getTipoProcesoForExpediente();;
		$est_projexp = $this->referenciasService->getReferenciasByFilter(
			['tabla_ref' => 'expediente_procesos', 'categoria' => 'projudexp_estado']
		);
		$cat_projexp = $this->referenciasService->getReferenciasByFilter(
			['tabla_ref' => 'expediente_procesos', 'categoria' => 'projudexp_categoria']
		);
		$rama_derecho_defensas = $rama_derecho = RamaDerecho::where('categoria', 'defensas')
		->pluck('ramadernombre', 'id');;
		//$tipo_archivo = TipoArchivo::pluck('tiparchinombre','id');
		//$reqasis = DB::table('ref_reqasis')->select('reqid_refasis','ref_reqasistencia')->get();
		$reqasis = ReqAsistencia::all();
		$rama_derecho = RamaDerecho::where('categoria', 'expedientes')
			->pluck('ramadernombre', 'id');

		$cptonota = Cptonota::pluck('cpntnombre', 'id');
		$segmento = $this->segmentosService->getSegmentoActivo();
		$periodo = $this->periodosService->getPeriodoActivo();;	
		$motivos_cierre = MotivoEstadoCaso::all();
		$estadosPluck = Estado::pluck('nombre_estado', 'id');
		$motivo_asig = MotivoAsigCaso::pluck('nom_motivo', 'id');
	    $genero = $this->referenciasService->getReferenciasByFilter(
			['tabla_ref' => 'users', 'categoria' => 'genero']
		);		
		$estcivil = DB::table('referencias_tablas')
			->where(['tabla_ref' => 'users', 'categoria' => 'estado_civil'])
			->where('ref_nombre', '<>', 'Sin definir')->get();
		$estrato = DB::table('referencias_tablas')->where(
			['tabla_ref' => 'users', 'categoria' => 'estrato']
		)
			->get();
		$muncpios = DB::table('referencias_tablas')->where(['tabla_ref' => 'expedientes', 'categoria' => 'exp_municipios '])->pluck('ref_nombre', 'id');
		$deptos = DB::table('referencias_tablas')->where(['tabla_ref' => 'expedientes', 'categoria' => 'exp_depto'])->pluck('ref_nombre', 'id');
		$tipvivienda = DB::table('referencias_tablas')->where(['tabla_ref' => 'expedientes', 'categoria' => 'exp_tipo_vivienda'])->pluck('ref_nombre', 'id');
		$act_estados = DB::table('referencias_tablas')->where(['tabla_ref' => 'actuaciones', 'categoria' => 'act_estado'])->pluck('ref_nombre', 'id');



		$rdata_enf_dif = ReferencesData::where([
			'section' => 'enfoque_diferencial',
			'table' => 'users'
		])->get();
		$rdata_discap = ReferencesData::where([
			'section' => 'discapacidad',
			'table' => 'users'
		])->get();

		$rdata_gretnc = ReferencesData::where([
			'section' => 'grupo_etnico',
			'table' => 'users'
		])->get();

		$rdata_sin_secc = ReferencesData::where([
			'section' => 'sin_seccion',
			'table' => 'users'
		])->get();

		$rdata_info_soc_ec = ReferencesData::where([
			'section' => 'socio_economica',
			'table' => 'users'
		])->get();

		$rdatos_personales = ReferencesData::where([
			'section' => 'datos_personales',
			'table' => 'users'
		])->get();

		//	$user = \App\User::find(10458);


		$view->with([
			'rama_derecho' => $rama_derecho,
			'cat_projexp' => $cat_projexp,
			'est_projexp' => $est_projexp            
		])
			->with(['rdata_enf_dif' => $rdata_enf_dif])
			->with(['rdata_info_soc_ec' => $rdata_info_soc_ec])
			->with(['rdatos_personales' => $rdatos_personales])
			->with(['rdata_sin_secc' => $rdata_sin_secc])
			->with(['rdata_discap' => $rdata_discap])
			->with(['rdata_gretnc' => $rdata_gretnc])
			->with(['segmento' => $segmento])
			->with(['rama_derecho_defensas' => $rama_derecho_defensas])
			->with(['genero' => $genero])
			->with(['tipvivienda' => $tipvivienda])
			->with(['muncpios' => $muncpios])
			->with(['deptos' => $deptos])
			->with(['estcivil' => $estcivil])
			->with(['estrato' => $estrato])
			->with(['motivos_cierre' => $motivos_cierre])
			->with(['estados' => $estados])
			->with(['estadosPluck' => $estadosPluck])
			->with(['tipo_proceso' => $tipo_proceso])
			->with(['reqasis' => $reqasis])
			->with(['periodo' => $periodo])
			->with(['motivo_asig' => $motivo_asig])
			->with(['act_estados' => $act_estados])
			->with(['cptonota' => $cptonota]);
	}
}

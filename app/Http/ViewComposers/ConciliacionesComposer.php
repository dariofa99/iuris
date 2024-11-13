<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Periodo;
use App\Services\ReferenciasService;
use App\TablaReferencia;
use Illuminate\Support\Facades\App;

/**
*  
*/
class ConciliacionesComposer
{

	private $referenciasService;


	public function __construct()
	{
		
		$this->referenciasService = App::make(ReferenciasService::class);
		
	}
	
	public function compose(View $view)
	{

		$colores_turno = $this->referenciasService->getReferenciasByFilter(
			['tabla_ref' => 'turnos',
			 'categoria' => 'color']
		);

		$cursando_turno = $this->referenciasService->getReferenciasByFilter(
			['tabla_ref' => 'turnos',
			 'categoria' => 'cursando']
		);


		$tipopers = TablaReferencia::where(['categoria'=>'tipo_persona','tabla_ref'=>'users'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 

		$types_status = TablaReferencia::where(['categoria'=>'type_status',
		'tabla_ref'=>'conciliaciones'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 
		$types_users = TablaReferencia::where(['categoria'=>'type_user_conciliacion',
		'tabla_ref'=>'conciliaciones_has_user'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id');

		$types_status_pretension = TablaReferencia::where([
			'categoria'=>'type_status',
			'tabla_ref'=>'conc_hechos_preten'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 

		$types_firma_users = TablaReferencia::where(['categoria'=>'type_user_firm_conciliacion',
		'tabla_ref'=>'pdf_reportes_users'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id');

	/* 	$categories_report = ReferencesData::where(['categories'=>'pdf_reportes',
		'table'=>'pdf_reportes'])
		->where('name','<>','Sin definir')
		->get(); */

		$types_categories_report = TablaReferencia::where(['categoria'=>'tipo_reporte',
		'tabla_ref'=>'pdf_reportes'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id');

		$periodo = Periodo::where('estado',1)->first();

		$view->with([
			'tipopers'=>$tipopers,
			'types_firma_users'=>	$types_firma_users ,
			'types_status'=>$types_status,
			'types_users'=>$types_users,
			'types_status_pretension'=>$types_status_pretension,
			'colores_turno'=>$colores_turno,
			'types_categories_report'=>$types_categories_report,
			'periodo'=>$periodo,
			"cursando_turno"=>$cursando_turno
		]);
	}

	
}



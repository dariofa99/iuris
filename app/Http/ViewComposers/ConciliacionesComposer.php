<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\RamaDerecho;
use App\User;
use App\Segmento;
use App\MotivoEstadoCaso;
use App\Estado;
use App\ReqAsistencia;
use App\Periodo;
use App\Role;
use App\RefAsignacionCaso;
use App\MotivoAsigCaso;
use DB;
use App\Sede;
use App\TablaReferencia;
use App\ReferencesData;



/**
*  
*/
class ConciliacionesComposer
{
	
	public function compose(View $view)
	{

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
			/* 'categories_report'=>$categories_report, */
			'types_categories_report'=>$types_categories_report,
			'periodo'=>$periodo
		]);
	}

	
}



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
use DB;




/**
*  
*/
class DefensasOficioComposer
{
	
	public function compose(View $view)
	{

		$reqasis = ReqAsistencia::all();
		$rama_derecho = RamaDerecho::where('categoria','defensas')->pluck('ramadernombre','id');
		$rama_derecho2 = RamaDerecho::where('categoria','expedientes')->orWhere('categoria','defensas')->pluck('ramadernombre','id'); 
		$cptonota = Cptonota::pluck('cpntnombre','id');
		$segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
		->where('sg.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		$periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		//$motivos_cierre = MotivoEstadoCaso::pluck('nombre_motivo','id');
		$tipo_proceso = DB::table('ref_tipproceso')->select('ref_tipproceso','id')->get();
		$motivos_cierre = MotivoEstadoCaso::all();
		$estadosPluck = Estado::pluck('nombre_estado','id');
		$motivo_asig = MotivoAsigCaso::pluck('nom_motivo','id');
		$tipodoc = DB::table('referencias_tablas')->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])->where('ref_nombre','<>','Sin definir')->get();
		$genero = DB::table('referencias_tablas')->where(['tabla_ref'=>'users','categoria'=>'genero'])->where('ref_nombre','<>','Sin definir')->get();
		$estcivil = DB::table('referencias_tablas')->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])->where('ref_nombre','<>','Sin definir')->get();
		$estrato = DB::table('referencias_tablas')->where(['tabla_ref'=>'users','categoria'=>'estrato'])->get();
		$muncpios = DB::table('referencias_tablas')->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios '])->pluck('ref_nombre','id');
		$deptos = DB::table('referencias_tablas')->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])->pluck('ref_nombre','id');
		$tipvivienda = DB::table('referencias_tablas')->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])->pluck('ref_nombre','id');
		$act_estados = DB::table('referencias_tablas')->where(['tabla_ref'=>'actuaciones','categoria'=>'act_estado'])->pluck('ref_nombre','id');
		$estados = Estado::all(); 
		$view->with(['rama_derecho'=>$rama_derecho])
		->with(['rama_derecho2'=>$rama_derecho2])  
		->with(['segmento'=>$segmento])
		->with(['tipodoc'=>$tipodoc])
		->with(['genero'=>$genero])
		->with(['tipvivienda'=>$tipvivienda])
		->with(['muncpios'=>$muncpios])
		->with(['deptos'=>$deptos]) 
		->with(['estcivil'=>$estcivil])
		->with(['estrato'=>$estrato])
		->with(['motivos_cierre'=>$motivos_cierre]) 
		->with(['estados'=>$estados]) 
		->with(['estadosPluck'=>$estadosPluck])
		->with(['tipo_proceso'=>$tipo_proceso]) 
		->with(['reqasis'=>$reqasis])
		->with(['periodo'=>$periodo])
		->with(['motivo_asig'=>$motivo_asig])
		->with(['act_estados'=>$act_estados])
		->with(['cptonota'=>$cptonota]);
	}

	
}



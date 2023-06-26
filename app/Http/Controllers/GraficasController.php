<?php

namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB; 
use Input;
use App\User;
use Carbon\Carbon; 

class GraficasController extends Controller
{

protected $delimiter  = ';'; 

    public function index()
    { 
        return view('myforms.frm_reportes_graficas');
    }
    
    public function getFilters()
    { 
        $data = [];

        
        return view('myforms.frm_reportes_graficas');
    }
    


  public function search_data(Request $request){
    $consulta=[];
    if ($request->table=='expedientes') {
        if ($request->option_table =='rama_derecho') {
             
              if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                $consulta = DB::table('expedientes')
              ->join('rama_derecho','rama_derecho.id','=','expedientes.expramaderecho_id')
              ->select('rama_derecho.id as id','rama_derecho.ramadernombre as label',DB::raw('COUNT(expramaderecho_id) as value'))
              ->groupBy('expedientes.expramaderecho_id')
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->get();
              }else{
              $consulta = DB::table('expedientes')
            ->join('rama_derecho','rama_derecho.id','=','expedientes.expramaderecho_id')
            ->select('rama_derecho.id as id','rama_derecho.ramadernombre as label',DB::raw('COUNT(expramaderecho_id) as value'))
            ->groupBy('expedientes.expramaderecho_id')
            ->get();
              }

            if ($request->option_table_cruce =='estado') {
              $estados = DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expramaderecho_id'=>$consult->id,'expestado_id'=>$estad->id])
                      ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')->where(['expramaderecho_id'=>$consult->id,'expestado_id'=>$estad->id])->count();

                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados 
              ];
            }
            if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados = DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expramaderecho_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='tipo_doc') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
              //->where('ref_nombre','<>','Sin definir')
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expramaderecho_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados,
                //'estados'=>
              ];
            }

            if ($request->option_table_cruce =='genero') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'genero'])
              //->where('ref_nombre','<>','Sin definir')
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='departamento') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='municipio') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='tipo_vivienda') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expramaderecho_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='estrato') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='estado_civil') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expramaderecho_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }



        }
        if ($request->option_table =='estado') {
      
        if ($request->fecha_ini!='' and $request->fecha_fin!='') {
              $consulta = DB::table('expedientes')
              ->join('ref_estados','ref_estados.id','=','expedientes.expestado_id')
              ->select('ref_estados.id as id','ref_estados.nombre_estado as label',DB::raw('COUNT(expestado_id) as value'))
              ->groupBy('expedientes.expestado_id') ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
            ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
            ->get();
            }else{
                $consulta = DB::table('expedientes')
        ->join('ref_estados','ref_estados.id','=','expedientes.expestado_id')
        ->select('ref_estados.id as id','ref_estados.nombre_estado as label',DB::raw('COUNT(expestado_id) as value'))
        ->groupBy('expedientes.expestado_id')
        ->get();

            }
         if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expestado_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados = DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->where(['expestado_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='tipo_doc') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expestado_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados,
                //'estados'=>
              ];
            }

            if ($request->option_table_cruce =='genero') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'genero'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                 
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='departamento') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expestado_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                   $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();   
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='municipio') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                 
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='tipo_vivienda') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expestado_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='estrato') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='estado_civil') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                 
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expestado_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

        }

        if ($request->option_table =='tipo_procedimiento') {
          if ($request->fecha_ini!='' and $request->fecha_fin!='') {
            $consulta = DB::table('expedientes')
            ->join('ref_tipproceso','ref_tipproceso.id','=','expedientes.exptipoproce_id')
            ->select('ref_tipproceso.id as id','ref_tipproceso.ref_tipproceso as label',DB::raw('COUNT(exptipoproce_id) as value'))
            ->groupBy('expedientes.exptipoproce_id')
             ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                ->get();
            }else{
              $consulta = DB::table('expedientes')
            ->join('ref_tipproceso','ref_tipproceso.id','=','expedientes.exptipoproce_id')
            ->select('ref_tipproceso.id as id','ref_tipproceso.ref_tipproceso as label',DB::raw('COUNT(exptipoproce_id) as value'))
            ->groupBy('expedientes.exptipoproce_id')
            ->get();
          }


          if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipoproce_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado') {
              $estados = DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }


             if ($request->option_table_cruce =='tipo_doc') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {                  
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['exptipoproce_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados,
              ];
            }

            if ($request->option_table_cruce =='genero') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'genero'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='departamento') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipoproce_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='municipio') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipoproce_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='tipo_vivienda') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipoproce_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['exptipoproce_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

            if ($request->option_table_cruce =='estrato') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
              ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                    if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='estado_civil') {
              $estados =  DB::table('referencias_tablas')
              ->select('ref_nombre as value_graph','id')
              ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
               ->get();   
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipoproce_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }


        }

        if ($request->option_table=='tipo_doc') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.tipodoc_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.tipodoc_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('users.tipodoc_id')
              ->get();
            }else{
            $consulta = DB::table('expedientes')
            ->join('users','users.idnumber','=','expedientes.expidnumber')
            ->join('referencias_tablas','referencias_tablas.id','=','users.tipodoc_id')        
            ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.tipodoc_id) as value'))
            ->groupBy('users.tipodoc_id')
            ->get();

            }

             if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['tipodoc_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['tipodoc_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }          
        }
      if ($request->option_table =='genero') {
          if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.genero_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.genero_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('users.genero_id')
              ->get();
            }else{
            $consulta = DB::table('expedientes')
            ->join('users','users.idnumber','=','expedientes.expidnumber')
            ->join('referencias_tablas','referencias_tablas.id','=','users.genero_id')        
            ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.genero_id) as value'))
            ->groupBy('users.genero_id')
            ->get();

            }

             if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                    ->join('users','users.idnumber','=','expedientes.expidnumber')
                    ->where(['genero_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                    ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                    ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                    ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='tipo_doc') {
            $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
             ->get();  
          
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['genero_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['genero_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
        }

        if ($request->option_table=='departamento') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.expdepto_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.expdepto_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('expedientes.expdepto_id')
              ->get();
            }else{
             $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.expdepto_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.expdepto_id) as value'))
              ->groupBy('expedientes.expdepto_id')
              ->get();

            }

             if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expdepto_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expdepto_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expdepto_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expdepto_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expdepto_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expdepto_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expdepto_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expdepto_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_doc') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expdepto_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expdepto_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expdepto_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expdepto_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expdepto_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                 ->where(['expdepto_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expdepto_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expdepto_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expdepto_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expdepto_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }          
        } 

        if ($request->option_table=='municipio') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.expmunicipio_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.expmunicipio_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('expedientes.expmunicipio_id')
              ->get();
            }else{
             $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.expmunicipio_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.expmunicipio_id) as value'))
              ->groupBy('expedientes.expmunicipio_id')
              ->get();

            }
            if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expmunicipio_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expmunicipio_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expmunicipio_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expmunicipio_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expmunicipio_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['expmunicipio_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expmunicipio_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expmunicipio_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expmunicipio_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['expmunicipio_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_doc') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expmunicipio_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expmunicipio_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['expmunicipio_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                 ->where(['expmunicipio_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expmunicipio_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expmunicipio_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['expmunicipio_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['expmunicipio_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
        }

        if ($request->option_table=='tipo_vivienda') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.exptipovivien_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.exptipovivien_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('expedientes.exptipovivien_id')
              ->get();
            }else{
             $consulta = DB::table('expedientes')
              ->join('referencias_tablas','referencias_tablas.id','=','expedientes.exptipovivien_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(expedientes.exptipovivien_id) as value'))
              ->groupBy('expedientes.exptipovivien_id')
              ->get();

            }

            if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipovivien_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['exptipovivien_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipovivien_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['exptipovivien_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipovivien_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                   ->where(['exptipovivien_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['exptipovivien_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipovivien_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipovivien_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->where(['exptipovivien_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->where(['exptipovivien_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->where(['exptipovivien_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_doc') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['exptipovivien_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipovivien_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['exptipovivien_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipovivien_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                      ->where(['exptipovivien_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['exptipovivien_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
      } 

      if ($request->option_table=='estrato') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
               ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.estrato_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.estrato_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('users.estrato_id')
              ->get();
            }else{
               $consulta = DB::table('expedientes')
               ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.estrato_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.estrato_id) as value'))
               ->groupBy('users.estrato_id')
              ->get();

            }

            if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')   
                     ->where(['estrato_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_doc') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                    ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estado_civil') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estrato_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estrato_id'=>$consult->id,'estadocivil_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados,
              ];
            }

        }

         if ($request->option_table=='estado_civil') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
               $consulta = DB::table('expedientes')
              ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.estadocivil_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.estadocivil_id) as value'))
              ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
              ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
              ->groupBy('users.estadocivil_id')
              ->get();
            }else{
               $consulta = DB::table('expedientes')
               ->join('users','users.idnumber','=','expedientes.expidnumber')
              ->join('referencias_tablas','referencias_tablas.id','=','users.estadocivil_id')        
              ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(users.estadocivil_id) as value'))
               ->groupBy('users.estadocivil_id')
              ->get();

            }

             if ($request->option_table_cruce =='rama_derecho') {
              $estados = DB::table('rama_derecho')
              ->select('ramadernombre as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'expramaderecho_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='estado') {
              $estados =  DB::table('ref_estados')
              ->select('nombre_estado as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'expestado_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'expestado_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_procedimiento') {
              $estados =  DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value_graph','id')
              ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'exptipoproce_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

             if ($request->option_table_cruce =='genero') {
            $estados =  DB::table('referencias_tablas')
            ->select('ref_nombre as value_graph','id')
            ->where(['tabla_ref'=>'users','categoria'=>'genero'])
           ->get(); 
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                 if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'genero_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'genero_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='departamento') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
              ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                      ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'expdepto_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'expdepto_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='municipio') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'expmunicipio_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_vivienda') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
             ->get();  
              $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')   
                     ->where(['estadocivil_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                }else{
                  $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'exptipovivien_id'=>$estad->id])
                  ->count();
                }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }

              if ($request->option_table_cruce =='tipo_doc') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                    ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                     $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'tipodoc_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados
              ];
            }
            if ($request->option_table_cruce =='estrato') {
             $estados =  DB::table('referencias_tablas')
             ->select('ref_nombre as value_graph','id')
             ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
             ->get(); 
            $estados_data=[];
              $response=[];
              foreach ($consulta as $key => $consult) {
                foreach ($estados as $key_2 => $estad) {
                  if ($request->fecha_ini!='' and $request->fecha_fin!='') {
                     $estado = DB::table('expedientes')
                     ->join('users','users.idnumber','=','expedientes.expidnumber')
                     ->where(['estadocivil_id'=>$consult->id,'estrato_id'=>$estad->id])
                     ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
                     ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
                     ->count();
                  }else{
                    $estado = DB::table('expedientes')
                  ->join('users','users.idnumber','=','expedientes.expidnumber')
                  ->where(['estadocivil_id'=>$consult->id,'estrato_id'=>$estad->id])
                  ->count();
                  }
                  $estados_data['encabezado'] = $consult->label;
                  $estados_data[$estad->value_graph] = $estado;
                }
                $response[] = $estados_data;
              }
              $consulta = [
                'data'=>$response,
                'graph'=>$estados,
              ];
            }


      }

    }

    if ($request->table=='actuaciones') {
     if ($request->fecha_ini!='' and $request->fecha_fin!='') {
          $consulta = DB::table('actuacions')
            ->join('referencias_tablas','referencias_tablas.id','=','actuacions.actestado_id')
            ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(actestado_id) as value'))
            ->whereDate('actuacions.created_at','>=',$request->fecha_ini)
            ->whereDate('actuacions.created_at','<=',$request->fecha_fin)
            ->groupBy('actuacions.actestado_id')
            ->get();               
        }else{            
      $consulta = DB::table('actuacions')
            ->join('referencias_tablas','referencias_tablas.id','=','actuacions.actestado_id')
            ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',DB::raw('COUNT(actestado_id) as value'))
            ->groupBy('actuacions.actestado_id')
            ->get();
        }      
  }

   if ($request->table=='requerimientos') {
            if ($request->fecha_ini!='' and $request->fecha_fin!='') {
            $consulta = DB::table('requerimientos')
            ->select(
                DB::raw('SUM(if(reqentregado = "0", 1, 0)) AS sin_entregar'),
                DB::raw('SUM(if(reqentregado = "1", 1, 0)) AS entregado') 
            )
            ->whereDate('created_at','>=',$request->fecha_ini)
            ->whereDate('created_at','<=',$request->fecha_fin)
            //->groupBy('requerimientos.reqentregado')
            ->get();              
        }else{

      $consulta = DB::table('requerimientos')
            ->select(
                DB::raw('SUM(if(reqentregado = "0", 1, 0)) AS sin_entregar'),
                DB::raw('SUM(if(reqentregado = "1", 1, 0)) AS entregado') 
            )
           ->get();

        }  
        $consulta_2 =[];
        $consulta_2[0] =
          [
            'id'=>0,
            'label'=>'Sin Entregar',
            'value'=>$consulta[0]->sin_entregar
          ];
        $consulta_2[1] =
          [
            'id'=>1,
            'label'=>'Entregado',
            'value'=>$consulta[0]->entregado
          ]; 
        $consulta = $consulta_2;       
  }

  if ($request->table=='conciliaciones') {
    if ($request->fecha_ini!='' and $request->fecha_fin!='') {
      $consulta = DB::table('conciliaciones')
      ->join('referencias_tablas','referencias_tablas.id','=','conciliaciones.estado_id')
      ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',
       DB::raw('COUNT(estado_id) as value'))
       ->whereDate('conciliaciones.created_at','>=',$request->fecha_ini)
       ->whereDate('conciliaciones.created_at','<=',$request->fecha_fin)
      ->groupBy('conciliaciones.estado_id')
      ->get();

                       
       }else{            
     $consulta = DB::table('conciliaciones')
           ->join('referencias_tablas','referencias_tablas.id','=','conciliaciones.estado_id')
           ->select('referencias_tablas.id as id','referencias_tablas.ref_nombre as label',
            DB::raw('COUNT(estado_id) as value'))
           ->groupBy('conciliaciones.estado_id')
           ->get();
       }      
 }


      $data = ['consulta'=>$consulta];

      return response()->json($data);
    }

      


    
}
